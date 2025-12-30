<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_id',
        'time_title',
        'time_detail',
        'time_start',
        'time_end',
        'time_limit',
        'time_max',
        'time_active',
        'time_delete',
    ];

    protected $casts = [
        'time_start'  => 'datetime:H:i',
        'time_end'    => 'datetime:H:i',
        'time_limit'  => 'boolean',
        'time_active' => 'boolean',
        'time_delete' => 'boolean',
    ];

    // Relationships
    public function date()
    {
        return $this->belongsTo(HrDate::class, 'date_id');
    }

    public function attends()
    {
        return $this->hasMany(HrAttend::class, 'time_id');
    }

    public function activeAttendsCount($projectId)
    {
        return $this->attends()
            ->where('attend_delete', false)
            ->where('project_id', $projectId)
            ->count();
    }

    public function activeAttends()
    {
        return $this->hasMany(HrAttend::class, 'time_id')->where('attend_delete', false);
    }

    public function seats()
    {
        return $this->hasMany(HrSeat::class, 'time_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('time_active', true)->where('time_delete', false);
    }

    // Accessors
    public function getAvailableSlotsAttribute()
    {
        if (! $this->time_limit) {
            return null;
        }
        return $this->time_max - $this->activeAttends()->count();
    }
}
