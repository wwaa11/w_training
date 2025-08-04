<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'attend_id',
        'user_id',
        'result_1',
        'result_2',
        'result_3',
        'result_4',
        'result_5',
        'result_6',
        'result_7',
        'result_8',
        'result_9',
        'result_10',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }

    public function attend()
    {
        return $this->belongsTo(HrAttend::class, 'attend_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function group()
    {
        return $this->hasOne(HrGroup::class, 'user_id', 'user_id')
            ->where('project_id', $this->project_id);
    }

    // Helper methods
    public function getResultsArrayAttribute()
    {
        return collect(range(1, 10))
            ->map(fn($i) => $this->{"result_{$i}"})
            ->filter()
            ->values()
            ->toArray();
    }

    public function getFormattedResultsAttribute()
    {
        $header = $this->project->resultHeader;
        if (! $header) {
            return [];
        }

        return collect(range(1, 10))
            ->filter(fn($i) => ! empty($header->{"result_{$i}_name"}))
            ->mapWithKeys(fn($i) => [
                $header->{"result_{$i}_name"} => $this->{"result_{$i}"},
            ])
            ->toArray();
    }
};
