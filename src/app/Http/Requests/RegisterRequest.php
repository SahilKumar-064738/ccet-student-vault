namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $domain = config('auth.college_email_domain', 'ccet.ac.in');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) use ($domain) {
                    if (!str_ends_with($value, "@{$domain}")) {
                        $fail("Email must be a valid college email ({$domain})");
                    }
                },
            ],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            'branch_id' => ['required', 'exists:branches,id'],
            'year' => ['required', 'integer', 'between:1,4'],
            'semester' => ['nullable', 'integer', 'between:1,8'],
            'role' => ['sometimes', Rule::in(['student', 'teacher', 'cr', 'admin'])],
            'phone' => ['nullable', 'string', 'max:15'],
            'roll_number' => ['nullable', 'string', 'max:20', 'unique:users'],
        ];
    }
}
