<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTime extends Model
{
    protected $fillable = [
        'session_id',
        'name',
        'max_seat',
        'available_seat',
        'status',
    ];

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function dates()
    {
        return $this->hasMany(TrainingDate::class, 'time_id')->orderby('name', 'asc');
    }

    public function users()
    {
        return $this->hasMany(TrainingUser::class, 'time_id');

    }

}
