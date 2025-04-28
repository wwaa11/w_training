<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function mytransaction()
    {
        return $this->HasMany(NurseTransaction::class)->where('user_id', auth()->user()->userid)->where('active', true)->orderBy('date_time', 'asc');
    }

    public function projecttransaction()
    {
        return $this->HasOne(NurseTransaction::class)->where('user_id', auth()->user()->userid)->where('active', true);
    }
}
