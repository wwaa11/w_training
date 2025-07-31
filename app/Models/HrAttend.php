<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrAttend extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'date_id',
        'time_id',
        'user_id',
        'attend_datetime',
        'approve_datetime',
        'attend_delete',
    ];

    protected $casts = [
        'attend_datetime'  => 'datetime',
        'approve_datetime' => 'datetime',
        'attend_delete'    => 'boolean',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    public function date()
    {
        return $this->belongsTo(HrDate::class, 'date_id');
    }

    public function time()
    {
        return $this->belongsTo(HrTime::class, 'time_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function result()
    {
        return $this->hasOne(HrResult::class, 'attend_id');
    }

    public function seat()
    {
        return $this->hasOne(HrSeat::class, 'user_id', 'user_id')
            ->where('time_id', $this->time_id)
            ->where('seat_delete', false);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('attend_delete', false);
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approve_datetime');
    }

    public function scopePending($query)
    {
        return $query->whereNull('approve_datetime');
    }
}
