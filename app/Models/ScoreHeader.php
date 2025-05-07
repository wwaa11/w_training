<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoreHeader extends Model
{
    protected $table = 'score_headers';

    protected $fillable = [
        'project_id',
    ];

    public function projectData()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
