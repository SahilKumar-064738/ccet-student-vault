namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendOtpEmail;
use App\Models\ActivityLog;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'branch_id' => $request->branch_id,
                'year' => $request->year,
                'semester' => $request->semester,
                'role' => $request->role ?? 'student',
                'phone' => $request->phone,
                'roll_number' => $request->roll_number,
            ]);

            // Generate and send OTP
            $otp = Otp::generate($user->email);
            SendOtpEmail::dispatch($user, $otp->otp);

            // Log activity
            ActivityLog::log('user_registered', $user->id, [
                'email' => $user->email,
                'role' => $user->role,
            ]);

            return redirect()
                ->route('verify-otp.show')
                ->with('email', $user->email)
                ->with('success', 'Registration successful! Please check your email for OTP.');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Registration failed. Please try again.'])
                ->withInput();
        }
    }
}
