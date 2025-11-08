namespace App\Providers;

use App\Models\Approval;
use App\Models\Branch;
use App\Models\Notification;
use App\Models\Subject;
use App\Models\Upload;
use App\Models\User;
use App\Policies\ApprovalPolicy;
use App\Policies\BranchPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\SubjectPolicy;
use App\Policies\UploadPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Upload::class => UploadPolicy::class,
        Approval::class => ApprovalPolicy::class,
        User::class => UserPolicy::class,
        Branch::class => BranchPolicy::class,
        Subject::class => SubjectPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}