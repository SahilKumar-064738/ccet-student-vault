namespace App\Http/Controllers;

use App\Http\Requests\UploadRequest;
use App\Jobs\ProcessUpload;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\Download;
use App\Models\Subject;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function index(Request $request)
    {
        $query = Upload::with(['subject', 'branch', 'user'])
            ->approved()
            ->public();

        // Apply filters
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        } else {
            $query->where('branch_id', $request->user()->branch_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', $request->user()->year);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('upload_type')) {
            $query->where('upload_type', $request->upload_type);
        }

        if ($request->filled('teacher_name')) {
            $query->where('teacher_name', 'ILIKE', "%{$request->teacher_name}%");
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $uploads = $query->paginate(20)->withQueryString();

        $branches = Branch::active()->get();
        $subjects = Subject::active()
            ->where('branch_id', $request->get('branch_id', $request->user()->branch_id))
            ->where('year', $request->get('year', $request->user()->year))
            ->get();

        return view('uploads.index', compact('uploads', 'branches', 'subjects'));
    }

    public function create()
    {
        $branches = Branch::active()->get();
        $subjects = Subject::active()
            ->where('branch_id', auth()->user()->branch_id)
            ->where('year', auth()->user()->year)
            ->get();

        return view('uploads.create', compact('branches', 'subjects'));
    }

    public function store(UploadRequest $request)
    {
        try {
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads', $fileName, 'private');

            $upload = Upload::create([
                'user_id' => $request->user()->id,
                'subject_id' => $request->subject_id,
                'branch_id' => $request->branch_id,
                'upload_type' => $request->upload_type,
                'teacher_name' => $request->teacher_name,
                'year' => $request->year,
                'semester' => $request->semester,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'description' => $request->description,
                'exam_year' => $request->exam_year,
                'is_public' => $request->boolean('is_public', true),
                'status' => $request->user()->isTeacher() ? 'approved' : 'pending',
            ]);

            ActivityLog::log('upload_created', null, [
                'upload_id' => $upload->id,
                'file_name' => $upload->file_name,
            ]);

            // Dispatch job for virus scanning if needed
            // ProcessUpload::dispatch($upload);

            return redirect()
                ->route('uploads.my-uploads')
                ->with('success', 'File uploaded successfully and is pending approval.');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Upload failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Upload $upload)
    {
        $this->authorize('view', $upload);

        return view('uploads.show', compact('upload'));
    }

    public function download(Upload $upload)
    {
        $this->authorize('download', $upload);

        // Record download
        Download::create([
            'upload_id' => $upload->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        $upload->incrementDownloads();

        ActivityLog::log('file_downloaded', null, [
            'upload_id' => $upload->id,
            'file_name' => $upload->file_name,
        ]);

        return Storage::disk('private')->download($upload->file_path, $upload->file_name);
    }

    public function myUploads(Request $request)
    {
        $uploads = Upload::where('user_id', $request->user()->id)
            ->with(['subject', 'branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('uploads.my-uploads', compact('uploads'));
    }

    public function destroy(Upload $upload)
    {
        $this->authorize('delete', $upload);

        Storage::disk('private')->delete($upload->file_path);
        $upload->delete();

        ActivityLog::log('upload_deleted', null, ['upload_id' => $upload->id]);

        return back()->with('success', 'Upload deleted successfully.');
    }
}