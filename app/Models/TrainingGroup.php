<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingGroup extends Model
{
    public function teacher()
    {
        return $this->hasMany(TrainingTeacher::class, 'group_id');
    }
}
