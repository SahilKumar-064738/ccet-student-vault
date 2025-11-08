namespace App\Policies;

use App\Models\Upload;
use App\Models\User;

class UploadPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Upload $upload): bool
    {
        return $upload->isApproved() || $upload->user_id === $user->id || $user->canApprove();
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->email_verified_at !== null;
    }

    public function update(User $user, Upload $upload): bool
    {
        return $upload->user_id === $user->id && $upload->isPending();
    }

    public function delete(User $user, Upload $upload): bool
    {
        return $upload->user_id === $user->id || $user->isAdmin();
    }

    public function download(User $user, Upload $upload): bool
    {
        if (!$upload->isApproved()) {
            return $upload->user_id === $user->id || $user->canApprove();
        }

        if (!$upload->is_public) {
            return $upload->user_id === $user->id;
        }

        return true;
    }

    public function approve(User $user, Upload $upload): bool
    {
        if (!$user->canApprove() || !$upload->isPending()) {
            return false;
        }

        // CR can only approve for their branch/year
        if ($user->isCR()) {
            return $upload->branch_id === $user->branch_id && $upload->year === $user->year;
        }

        return true;
    }

    public function reject(User $user, Upload $upload): bool
    {
        return $this->approve($user, $upload);
    }
}
