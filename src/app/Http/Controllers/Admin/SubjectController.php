namespace App\Http/Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Subject::class);

        $query = Subject::with('branch');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $subjects = $query->orderBy('name')->paginate(20);
        $branches = Branch::active()->get();

        return view('admin.subjects.index', compact('subjects', 'branches'));
    }

    public function create()
    {
        $this->authorize('create', Subject::class);
        $branches = Branch::active()->get();
        return view('admin.subjects.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Subject::class);

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'year' => 'required|integer|between:1,4',
            'semester' => 'nullable|integer|between:1,8',
        ]);

        $subject = Subject::create($validated);

        ActivityLog::log('subject_created', null, ['subject_id' => $subject->id]);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $this->authorize('update', $subject);
        $branches = Branch::active()->get();
        return view('admin.subjects.edit', compact('subject', 'branches'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'year' => 'required|integer|between:1,4',
            'semester' => 'nullable|integer|between:1,8',
            'is_active' => 'boolean',
        ]);

        $subject->update($validated);

        ActivityLog::log('subject_updated', null, ['subject_id' => $subject->id]);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $this->authorize('delete', $subject);

        $subject->delete();

        ActivityLog::log('subject_deleted', null, ['subject_id' => $subject->id]);

        return back()->with('success', 'Subject deleted successfully.');
    }

    public function getSubjects(Request $request)
    {
        $subjects = Subject::active()
            ->where('branch_id', $request->branch_id)
            ->where('year', $request->year)
            ->get();

        return response()->json($subjects);
    }
}