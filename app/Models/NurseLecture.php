<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NurseLecture extends Model
{
    protected $table = 'nurse_lecturers';

    protected $fillable = [
        'nurse_time_id',
        'user_id',
    ];

    public function dateData()
    {
        return $this->belongsTo(NurseDate::class, 'nurse_date_id');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'user_id', 'userid');
    }
}
