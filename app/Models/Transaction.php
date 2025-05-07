<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'item_id',
        'user',
        'transaction_active',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->orderBy('item_index', 'asc');
    }

    public function userData(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'userid');
    }

    public function scoreHeader(): HasOne
    {
        return $this->HasOne(ScoreHeader::class, 'project_id', 'project_id');
    }

    public function scoreData(): HasOne
    {
        return $this->HasOne(Score::class);
    }
}
