<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTeacher extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'status',
    ];

    public function team()
    {
        return $this->belongsTo(TrainingTeam::class, 'team_id');
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class, 'teacher_id');
    }
}
