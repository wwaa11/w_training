<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'date_title',
        'date_detail',
        'date_location',
        'date_datetime',
        'date_active',
        'date_delete',
    ];

    protected $casts = [
        'date_datetime' => 'datetime',
        'date_active'   => 'boolean',
        'date_delete'   => 'boolean',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    public function times()
    {
        return $this->hasMany(HrTime::class, 'date_id');
    }

    public function attends()
    {
        return $this->hasMany(HrAttend::class, 'date_id');
    }

    public function activeAttends()
    {
        return $this->hasMany(HrAttend::class, 'date_id')->where('attend_delete', false);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('date_active', true)->where('date_delete', false);
    }
}
