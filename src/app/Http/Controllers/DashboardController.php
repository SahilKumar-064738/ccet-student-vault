namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get statistics
        $stats = [
            'my_uploads' => Upload::where('user_id', $user->id)->count(),
            'approved' => Upload::where('user_id', $user->id)->approved()->count(),
            'pending' => Upload::where('user_id', $user->id)->pending()->count(),
            'rejected' => Upload::where('user_id', $user->id)->rejected()->count(),
        ];

        if ($user->canApprove()) {
            $stats['pending_approvals'] = Upload::pending()
                ->when($user->isCR(), function ($query) use ($user) {
                    $query->where('branch_id', $user->branch_id)
                          ->where('year', $user->year);
                })
                ->count();
        }

        // Recent uploads
        $recentUploads = Upload::with(['subject', 'branch', 'user'])
            ->approved()
            ->public()
            ->where('branch_id', $user->branch_id)
            ->where('year', $user->year)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Unread notifications
        $notifications = Notification::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereNull('user_id')
                          ->where('branch_id', $user->branch_id)
                          ->where('year', $user->year);
                    });
            })
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentUploads', 'notifications'));
    }
}