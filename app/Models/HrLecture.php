<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrLecture extends Model
{
    use HasFactory;

    protected $table = 'hr_lecturers';

    protected $fillable = [
        'date_id',
        'user_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function date()
    {
        return $this->belongsTo(HrDate::class, 'date_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name'   => 'User Not Found',
            'userid' => 'N/A',
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
