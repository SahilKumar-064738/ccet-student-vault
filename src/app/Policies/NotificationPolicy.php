namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Notification $notification): bool
    {
        return $notification->user_id === $user->id || 
               ($notification->user_id === null && 
                $notification->branch_id === $user->branch_id && 
                $notification->year === $user->year);
    }

    public function create(User $user): bool
    {
        return $user->canApprove();
    }
}