namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with('branch');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ILIKE', "%{$request->search}%")
                  ->orWhere('email', 'ILIKE', "%{$request->search}%")
                  ->orWhere('roll_number', 'ILIKE', "%{$request->search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $branches = Branch::active()->get();

        return view('admin.users.index', compact('users', 'branches'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $branches = Branch::active()->get();
        return view('admin.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'branch_id' => 'required|exists:branches,id',
            'year' => 'required|integer|between:1,4',
            'semester' => 'nullable|integer|between:1,8',
            'role' => 'required|in:student,teacher,cr,admin',
            'phone' => 'nullable|string|max:15',
            'roll_number' => 'nullable|string|unique:users,roll_number,' . $user->id,
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        ActivityLog::log('user_updated', null, [
            'user_id' => $user->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        ActivityLog::log('user_deleted', null, ['deleted_user_id' => $user->id]);

        return back()->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('update', $user);

        $user->is_active = !$user->is_active;
        $user->save();

        ActivityLog::log('user_status_toggled', null, [
            'user_id' => $user->id,
            'is_active' => $user->is_active,
        ]);

        return back()->with('success', 'User status updated.');
    }
}
