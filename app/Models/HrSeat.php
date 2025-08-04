<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_id',
        'user_id',
        'department',
        'seat_number',
        'seat_delete',
    ];

    protected $casts = [
        'seat_delete' => 'boolean',
    ];

    // Relationships
    public function time()
    {
        return $this->belongsTo(HrTime::class, 'time_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('seat_delete', false);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }
}
