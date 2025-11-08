namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function createForUser(User $user, string $title, string $body, string $type = 'general'): Notification
    {
        return Notification::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $user->id,
            'type' => $type,
        ]);
    }

    public function createForBranchYear(int $branchId, int $year, string $title, string $body, string $type = 'general'): void
    {
        $users = User::where('branch_id', $branchId)
            ->where('year', $year)
            ->where('is_active', true)
            ->get();

        foreach ($users as $user) {
            $this->createForUser($user, $title, $body, $type);
        }
    }

    public function getUnreadCount(User $user): int
    {
        return Notification::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereNull('user_id')
                          ->where('branch_id', $user->branch_id)
                          ->where('year', $user->year);
                    });
            })
            ->unread()
            ->count();
    }
}