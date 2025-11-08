namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyOtpController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('email')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', [
            'email' => $request->session()->get('email')
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $otp = Otp::where('email', $request->email)
            ->where('type', 'email_verification')
            ->where('used', false)
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        if ($otp->isExpired()) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($otp->maxAttemptsReached()) {
            return back()->withErrors(['otp' => 'Maximum OTP attempts reached. Please request a new one.']);
        }

        if ($otp->otp !== $request->otp) {
            $otp->incrementAttempts();
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Verify user email
        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();
        $user->save();

        // Log activity
        ActivityLog::log('email_verified', $user->id);

        // Log user in
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return back()->withErrors(['email' => 'Email already verified.']);
        }

        $otp = Otp::generate($user->email);
        \App\Jobs\SendOtpEmail::dispatch($user, $otp->otp);

        ActivityLog::log('otp_resent', $user->id);

        return back()->with('success', 'OTP has been resent to your email.');
    }
}