<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    protected $table = 'scores';

    protected $fillable = [
        'tranaction_id',
        'result_1',
        'result_2',
        'result_3',
        'result_4',
        'result_5',
        'result_6',
        'result_7',
        'result_8',
        'result_9',
        'result_10',
        'result_11',
        'result_12',
        'result_13',
        'result_14',
        'result_15',
        'result_16',
        'result_17',
        'result_18',
        'result_19',
        'result_20',
    ];

    public function transactionData()
    {
        return $this->belongsTo(Tranaction::class, 'tranaction_id');
    }
}
