<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'item_id',
        'user',
        'transaction_active',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->orderBy('item_index','asc');
    }

    public function userData(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'userid');
    }
}
