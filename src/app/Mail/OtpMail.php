namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otp,
        public string $type = 'email_verification'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === 'email_verification' 
            ? 'Verify Your Email - CCET Student Vault' 
            : 'Password Reset OTP - CCET Student Vault';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'user' => $this->user,
                'otp' => $this->otp,
                'type' => $this->type,
                'expiryMinutes' => config('auth.otp_expiry_minutes', 10),
            ]
        );
    }
}