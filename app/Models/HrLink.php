<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'link_name',
        'link_url',
        'link_limit',
        'link_time_start',
        'link_time_end',
        'link_delete',
    ];

    protected $casts = [
        'link_limit'      => 'boolean',
        'link_time_start' => 'datetime',
        'link_time_end'   => 'datetime',
        'link_delete'     => 'boolean',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('link_delete', false);
    }

    public function scopeAvailable($query)
    {
        return $query->where('link_delete', false)
            ->where(function ($query) {
                $query->where('link_limit', false)
                    ->orWhere(function ($query) {
                        $query->where('link_limit', true)
                            ->where('link_time_start', '<=', now())
                            ->where('link_time_end', '>=', now());
                    });
            });
    }
}
