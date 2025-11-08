namespace App\Mail;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Upload $upload,
        public string $action
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->action === 'approved' 
            ? 'Upload Approved - CCET Student Vault'
            : 'Upload Rejected - CCET Student Vault';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-notification',
            with: [
                'upload' => $this->upload,
                'action' => $this->action,
            ]
        );
    }
}