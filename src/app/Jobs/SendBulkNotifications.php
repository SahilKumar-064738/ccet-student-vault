namespace App\Jobs;

use App\Mail\NotificationMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBulkNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $title,
        public string $body,
        public int $branchId,
        public int $year,
        public string $type = 'general'
    ) {}

    public function handle(): void
    {
        $users = User::where('branch_id', $this->branchId)
            ->where('year', $this->year)
            ->where('is_active', true)
            ->get();

        foreach ($users as $user) {
            $notification = Notification::create([
                'title' => $this->title,
                'body' => $this->body,
                'branch_id' => $this->branchId,
                'year' => $this->year,
                'user_id' => $user->id,
                'type' => $this->type,
            ]);

            Mail::to($user->email)->send(
                new NotificationMail($notification)
            );
        }
    }
}