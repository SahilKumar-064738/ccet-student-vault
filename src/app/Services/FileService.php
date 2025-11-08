namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function upload(UploadedFile $file, string $directory = 'uploads'): array
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($directory, $fileName, 'private');

        return [
            'path' => $filePath,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
        ];
    }

    public function delete(string $path): bool
    {
        return Storage::disk('private')->delete($path);
    }

    public function download(string $path, string $name)
    {
        return Storage::disk('private')->download($path, $name);
    }

    public function exists(string $path): bool
    {
        return Storage::disk('private')->exists($path);
    }

    public function getUrl(string $path, int $expiresInMinutes = 60): string
    {
        return Storage::disk('private')->temporaryUrl(
            $path,
            now()->addMinutes($expiresInMinutes)
        );
    }
}
