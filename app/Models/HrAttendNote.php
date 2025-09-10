<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrAttendNote extends Model
{
    //
    protected $table    = 'hr_attend_notes';
    protected $fillable = ['attend_id', 'attend_note'];

    public function attend()
    {
        return $this->hasOne('App\Models\HrAttend', 'id', 'attend_id');
    }
}
