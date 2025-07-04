<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    protected $fillable = [
        'teacher_id',
        'name',
        'status',
    ];

    public function teacher()
    {
        return $this->belongsTo(TrainingTeacher::class, 'teacher_id');
    }

    public function times()
    {
        return $this->hasMany(TrainingTime::class, 'session_id');
    }
}
