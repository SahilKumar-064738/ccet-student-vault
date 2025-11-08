namespace App\Http\Controllers;

use App\Http\Requests\ApprovalRequest;
use App\Jobs\SendApprovalNotification;
use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\Notification;
use App\Models\Upload;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Approval::class);

        $query = Upload::with(['user', 'subject', 'branch'])
            ->pending();

        // CR can only approve for their branch/year
        if ($request->user()->isCR()) {
            $query->where('branch_id', $request->user()->branch_id)
                  ->where('year', $request->user()->year);
        }

        $uploads = $query->orderBy('created_at', 'asc')->paginate(20);

        return view('approvals.index', compact('uploads'));
    }

    public function approve(ApprovalRequest $request, Upload $upload)
    {
        $this->authorize('approve', $upload);

        $upload->update([
            'status' => 'approved',
            'admin_comment' => $request->comment,
        ]);

        Approval::create([
            'upload_id' => $upload->id,
            'approver_id' => $request->user()->id,
            'action' => 'approve',
            'comment' => $request->comment,
        ]);

        // Create notification for uploader
        Notification::create([
            'title' => 'Upload Approved',
            'body' => "Your upload '{$upload->file_name}' has been approved.",
            'user_id' => $upload->user_id,
            'type' => 'upload_approved',
        ]);

        ActivityLog::log('upload_approved', null, [
            'upload_id' => $upload->id,
            'approver_id' => $request->user()->id,
        ]);

        SendApprovalNotification::dispatch($upload, 'approved');

        return back()->with('success', 'Upload approved successfully.');
    }

    public function reject(ApprovalRequest $request, Upload $upload)
    {
        $this->authorize('reject', $upload);

        $upload->update([
            'status' => 'rejected',
            'admin_comment' => $request->comment,
        ]);

        Approval::create([
            'upload_id' => $upload->id,
            'approver_id' => $request->user()->id,
            'action' => 'reject',
            'comment' => $request->comment,
        ]);

        // Create notification for uploader
        Notification::create([
            'title' => 'Upload Rejected',
            'body' => "Your upload '{$upload->file_name}' has been rejected. Reason: {$request->comment}",
            'user_id' => $upload->user_id,
            'type' => 'upload_rejected',
        ]);

        ActivityLog::log('upload_rejected', null, [
            'upload_id' => $upload->id,
            'approver_id' => $request->user()->id,
        ]);

        SendApprovalNotification::dispatch($upload, 'rejected');

        return back()->with('success', 'Upload rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $this->authorize('viewAny', Approval::class);

        $request->validate([
            'upload_ids' => 'required|array',
            'upload_ids.*' => 'exists:uploads,id',
        ]);

        $uploads = Upload::whereIn('id', $request->upload_ids)->get();

        foreach ($uploads as $upload) {
            if ($request->user()->can('approve', $upload)) {
                $upload->update(['status' => 'approved']);
                
                Approval::create([
                    'upload_id' => $upload->id,
                    'approver_id' => $request->user()->id,
                    'action' => 'approve',
                ]);

                Notification::create([
                    'title' => 'Upload Approved',
                    'body' => "Your upload '{$upload->file_name}' has been approved.",
                    'user_id' => $upload->user_id,
                    'type' => 'upload_approved',
                ]);
            }
        }

        return back()->with('success', 'Selected uploads approved successfully.');
    }
}