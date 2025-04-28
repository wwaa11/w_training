<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NurseLecture extends Model
{
    protected $table = 'nurse_lectures';

    protected $fillable = [
        'nurse_time_id',
        'user_id',
    ];

    public function time()
    {
        return $this->belongsTo(NurseTime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
