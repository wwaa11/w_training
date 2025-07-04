<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingDate extends Model
{
    protected $fillable = [
        'time_id',
        'name',
        'location',
        'status',
    ];

    public function time()
    {
        return $this->belongsTo(TrainingTime::class, 'time_id');
    }
}
