namespace App\Http/Controllers;

use App\Jobs\SendBulkNotifications;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where(function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                    ->orWhere(function ($q) use ($request) {
                        $q->whereNull('user_id')
                          ->where('branch_id', $request->user()->branch_id)
                          ->where('year', $request->user()->year);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $this->authorize('create', Notification::class);
        
        $branches = Branch::active()->get();
        return view('notifications.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Notification::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:general,announcement',
            'branch_id' => 'required_without:user_id|exists:branches,id',
            'year' => 'required_without:user_id|integer|between:1,4',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($request->user_id) {
            Notification::create($validated);
        } else {
            SendBulkNotifications::dispatch(
                $validated['title'],
                $validated['body'],
                $validated['branch_id'],
                $validated['year'],
                $validated['type']
            );
        }

        ActivityLog::log('notification_sent', null, $validated);

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notification sent successfully.');
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id === auth()->id()) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}