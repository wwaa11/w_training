<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    protected $fillable = [
        'project_name',
        'project_detail',
        'project_active',
    ];

    public function link(): HasOne
    {
        return $this->hasOne(Link::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class)->where('slot_active', true)->orderBy('slot_date', 'asc');
    }

    public function tranasctionsData(): HasMany
    {
        return $this->hasMany(Transaction::class)->where('transaction_active', true)->orderBy('date', 'asc')->orderBy('user', 'asc');
    }

    public function scoreHeader(): HasOne
    {
        return $this->HasOne(ScoreHeader::class);
    }

}
