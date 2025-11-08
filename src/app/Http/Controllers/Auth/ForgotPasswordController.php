namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendOtpEmail;
use App\Models\ActivityLog;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        
        $otp = Otp::generate($user->email, 'password_reset');
        SendOtpEmail::dispatch($user, $otp->otp, 'password_reset');

        ActivityLog::log('password_reset_requested', $user->id);

        return redirect()
            ->route('password.reset.show')
            ->with('email', $request->email)
            ->with('success', 'Password reset OTP sent to your email.');
    }

    public function showResetForm(Request $request)
    {
        if (!$request->session()->has('email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', [
            'email' => $request->session()->get('email')
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $otp = Otp::where('email', $request->email)
            ->where('type', 'password_reset')
            ->where('used', false)
            ->first();

        if (!$otp || $otp->isExpired() || $otp->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password;
        $user->save();

        $otp->markAsUsed();

        ActivityLog::log('password_reset_completed', $user->id);

        return redirect()->route('login')->with('success', 'Password reset successfully!');
    }
}