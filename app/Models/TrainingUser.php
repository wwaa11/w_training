<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingUser extends Model
{
    protected $fillable = [
        'user_id',
        'team',
        'training_time_id',
        'schedule',
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id', 'userid');
    }

    public function time()
    {
        return $this->belongsTo(TrainingTime::class, 'time_id');
    }

    public function attends()
    {
        return $this->hasMany(TrainingAttend::class, 'user_id', 'user_id');
    }

    public function attend($date)
    {
        return $this->hasMany(TrainingAttend::class, 'user_id', 'user_id')->where('name', $date);
    }
}
