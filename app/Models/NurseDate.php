<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NurseDate extends Model
{
    protected $table = 'nurse_dates';

    protected $fillable = [
        'nurse_project_id',
        'title',
        'detail',
        'location',
        'date',
    ];

    public function project()
    {
        return $this->belongsTo(NurseProject::class);
    }
    public function times()
    {
        return $this->hasMany(NurseTime::class);
    }
}
