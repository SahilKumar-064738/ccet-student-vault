namespace App\Jobs;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOtpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        public User $user,
        public string $otp,
        public string $type = 'email_verification'
    ) {}

    public function handle(): void
    {
        Mail::to($this->user->email)->send(
            new OtpMail($this->user, $this->otp, $this->type)
        );
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('Failed to send OTP email', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}
