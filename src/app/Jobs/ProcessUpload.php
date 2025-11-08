namespace App\Jobs;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Upload $upload) {}

    public function handle(): void
    {
        // Virus scanning (if ClamAV is available)
        if (class_exists('\Xenolope\Quahog\Client')) {
            try {
                $scanner = new \Xenolope\Quahog\Client(
                    new \Socket\Raw\Factory(),
                    'unix:///var/run/clamav/clamd.ctl'
                );

                $filePath = Storage::disk('private')->path($this->upload->file_path);
                $result = $scanner->scanLocalFile($filePath);

                if ($result['status'] === 'FOUND') {
                    $this->upload->update([
                        'status' => 'rejected',
                        'admin_comment' => 'File contains malware: ' . $result['reason'],
                    ]);

                    Storage::disk('private')->delete($this->upload->file_path);
                }
            } catch (\Exception $e) {
                \Log::warning('Virus scan failed', [
                    'upload_id' => $this->upload->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}