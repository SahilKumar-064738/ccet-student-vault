namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds."
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit($key, 300);
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if ($user->isLocked()) {
            return back()->withErrors([
                'email' => 'Your account is temporarily locked due to multiple failed login attempts.'
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user->incrementFailedLogins();
            RateLimiter::hit($key, 300);
            
            ActivityLog::log('failed_login', $user->id, ['ip' => $request->ip()]);
            
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        if (!$user->email_verified_at) {
            Auth::logout();
            return redirect()->route('verify-otp.show')->with('email', $user->email);
        }

        // Reset failed login attempts
        $user->resetFailedLogins();
        $user->last_login_at = now();
        $user->save();

        RateLimiter::clear($key);

        ActivityLog::log('user_login', $user->id);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        ActivityLog::log('user_logout');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}