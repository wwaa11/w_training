<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    protected $fillable = [
        'slot_id',
        'item_name',
        'item_available',
        'item_detail',
        'item_note_1_active',
        'item_note_1_title',
        'item_note_1_value',
        'item_note_2_active',
        'item_note_2_title',
        'item_note_2_value',
        'item_note_3_active',
        'item_note_3_title',
        'item_note_3_value',
        'item_active',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class)->orderBy('slot_date', 'asc')->orderBy('slot_index', 'asc');
    }

    public function seats(): HasOne
    {
        return $this->hasOne(Seat::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->where('transaction_active', true)->orderBy('user', 'asc');
    }

}
