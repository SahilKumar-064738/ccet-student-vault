namespace App\Http/Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'phone' => ['nullable', 'string', 'max:15'],
            'branch_id' => ['required', 'exists:branches,id'],
            'year' => ['required', 'integer', 'between:1,4'],
            'semester' => ['nullable', 'integer', 'between:1,8'],
        ];
    }
}