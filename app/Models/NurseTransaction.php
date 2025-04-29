<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NurseTransaction extends Model
{
    protected $table = 'nurse_transactions';

    protected $fillable = [
        'nurse_project_id',
        'nurse_time_id',
        'date_time',
        'user_id',
    ];

    public function ProjectData()
    {
        return $this->belongsTo(NurseProject::class, 'nurse_project_id');
    }

    public function timeData()
    {
        return $this->belongsTo(NurseTime::class, 'nurse_time_id');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id', 'userid');
    }
}
