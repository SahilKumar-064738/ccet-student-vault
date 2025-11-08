namespace App\Jobs;

use App\Mail\ApprovalNotificationMail;
use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Upload $upload,
        public string $action
    ) {}

    public function handle(): void
    {
        Mail::to($this->upload->user->email)->send(
            new ApprovalNotificationMail($this->upload, $this->action)
        );
    }
}