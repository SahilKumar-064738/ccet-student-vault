namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Branch::class);
        
        $branches = Branch::withCount('users', 'uploads')
            ->orderBy('name')
            ->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $this->authorize('create', Branch::class);
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Branch::class);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:branches',
            'name' => 'required|string|max:255',
        ]);

        $branch = Branch::create($validated);

        ActivityLog::log('branch_created', null, ['branch_id' => $branch->id]);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        $this->authorize('update', $branch);
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:branches,code,' . $branch->id,
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        ActivityLog::log('branch_updated', null, ['branch_id' => $branch->id]);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        if ($branch->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete branch with associated users.']);
        }

        $branch->delete();

        ActivityLog::log('branch_deleted', null, ['branch_id' => $branch->id]);

        return back()->with('success', 'Branch deleted successfully.');
    }
}
