namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'otp', 'type', 'attempts', 'expires_at', 'used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public static function generate(string $email, string $type = 'email_verification'): self
    {
        // Delete old OTPs
        self::where('email', $email)->where('type', $type)->delete();

        return self::create([
            'email' => $email,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $type,
            'expires_at' => now()->addMinutes(config('auth.otp_expiry_minutes', 10)),
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function maxAttemptsReached(): bool
    {
        return $this->attempts >= config('auth.max_otp_attempts', 3);
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }
}