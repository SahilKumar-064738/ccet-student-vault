namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'name', 'code', 'year', 'semester', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForBranchAndYear($query, $branchId, $year)
    {
        return $query->where('branch_id', $branchId)->where('year', $year);
    }
}
