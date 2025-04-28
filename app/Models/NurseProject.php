<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NurseProject extends Model
{
    protected $table = 'nurse_projects';

    protected $fillable = [
        'title',
        'detail',
        'location',
        'register_start',
        'register_end',
    ];

    public function dates()
    {
        return $this->hasMany(NurseDate::class);
    }

}
