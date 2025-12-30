<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrOnebook extends Model
{
    protected $fillable = [
        'project_id',
        'skip_hours',
    ];

    public function project()
    {
        return $this->belongsTo(HrProject::class, 'project_id');
    }
}
