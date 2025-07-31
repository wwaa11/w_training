<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrResultHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'result_1_name',
        'result_2_name',
        'result_3_name',
        'result_4_name',
        'result_5_name',
        'result_6_name',
        'result_7_name',
        'result_8_name',
        'result_9_name',
        'result_10_name',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    // Helper methods
    public function getResultNamesAttribute()
    {
        return collect(range(1, 10))
            ->map(fn($i) => $this->{"result_{$i}_name"})
            ->filter()
            ->values()
            ->toArray();
    }

    public function getActiveColumnsAttribute()
    {
        return collect(range(1, 10))
            ->filter(fn($i) => ! empty($this->{"result_{$i}_name"}))
            ->values()
            ->toArray();
    }
}
