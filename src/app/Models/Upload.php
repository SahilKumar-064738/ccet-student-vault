namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'subject_id', 'branch_id', 'upload_type', 'teacher_name',
        'year', 'semester', 'file_path', 'file_name', 'file_size', 'mime_type',
        'description', 'exam_year', 'status', 'admin_comment', 'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'exam_year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('description', 'ILIKE', "%{$search}%")
              ->orWhere('teacher_name', 'ILIKE', "%{$search}%")
              ->orWhere('file_name', 'ILIKE', "%{$search}%");
        });
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }
}
