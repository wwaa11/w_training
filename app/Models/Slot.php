<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class Slot extends Model
{
    protected $fillable = [
        'project_id',
        'slot_name',
        'slot_active',
    ];

    protected function dateThai(): Attribute
    {
        App::setLocale('th');
        $dayOfWeek = Carbon::createFromTimestamp($this->slot_date)->translatedFormat('l');

        return new Attribute(
            get: fn() => $dayOfWeek,
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class)->where('item_active', true)->orderBy('item_index', 'asc');
    }
}
