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

    public function time()
    {
        return $this->belongsTo(NurseTime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
