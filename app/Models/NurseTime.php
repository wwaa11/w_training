<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NurseTime extends Model
{
    protected $table = 'nurse_times';

    protected $fillable = [
        'nurse_date_id',
        'title',
        'detail',
        'location',
        'time_start',
        'time_end',
    ];

    public function date()
    {
        return $this->belongsTo(NurseDate::class);
    }
    public function transactions()
    {
        return $this->hasMany(NurseTransaction::class);
    }
    public function lectures()
    {
        return $this->hasMany(NurseLecture::class);
    }
    public function my_lecture()
    {
        return $this->hasMany(NurseLecture::class)->where('user_id', auth()->user()->userid);
    }
}
