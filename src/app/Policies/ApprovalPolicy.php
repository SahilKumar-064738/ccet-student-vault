namespace App\Policies;

use App\Models\Approval;
use App\Models\User;

class ApprovalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canApprove();
    }

    public function view(User $user, Approval $approval): bool
    {
        return $user->canApprove();
    }
}