<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTeam extends Model
{
    protected $fillable = ['name', 'status'];

    public function teachers()
    {
        return $this->hasMany(TrainingTeacher::class, 'team_id');
    }
}
