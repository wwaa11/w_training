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
        'max',
        'free',
    ];

    public function dateData()
    {
        return $this->belongsTo(NurseDate::class, 'nurse_date_id');
    }
    public function transactionData()
    {
        return $this->hasMany(NurseTransaction::class)->where('active', true)->orderBy('date_time', 'asc');
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
