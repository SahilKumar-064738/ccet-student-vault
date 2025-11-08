namespace App\Http/Controllers;

use App\Models\ActivityLog;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_uploads' => Upload::count(),
            'approved_uploads' => Upload::approved()->count(),
            'pending_uploads' => Upload::pending()->count(),
            'total_downloads' => DB::table('downloads')->count(),
        ];

        // Uploads by type
        $uploadsByType = Upload::select('upload_type', DB::raw('count(*) as count'))
            ->groupBy('upload_type')
            ->get();

        // Uploads by branch
        $uploadsByBranch = Upload::select('branches.name', DB::raw('count(*) as count'))
            ->join('branches', 'uploads.branch_id', '=', 'branches.id')
            ->groupBy('branches.name')
            ->get();

        // Recent activity
        $recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.reports.index', compact(
            'stats',
            'uploadsByType',
            'uploadsByBranch',
            'recentActivity'
        ));
    }
}