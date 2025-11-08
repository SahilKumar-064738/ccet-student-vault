namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxSize = config('filesystems.max_upload_size', 52428800); // 50MB
        $allowedTypes = config('filesystems.allowed_types', 'pdf,png,jpg,jpeg,docx');
        
        return [
            'file' => "required|file|max:{$maxSize}|mimes:{$allowedTypes}",
            'upload_type' => ['required', 'in:question_paper,notes,assignment,mst,other'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'branch_id' => ['required', 'exists:branches,id'],
            'year' => ['required', 'integer', 'between:1,4'],
            'semester' => ['nullable', 'integer', 'between:1,8'],
            'teacher_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'exam_year' => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'is_public' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'File size must not exceed 50MB.',
            'file.mimes' => 'Only PDF, PNG, JPG, JPEG, and DOCX files are allowed.',
        ];
    }
}
