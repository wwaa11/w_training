<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'userid',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->where('transaction_active', true);
    }

    public function nurse_lectures(): HasMany
    {
        return $this->hasMany(NurseLecture::class, 'user_id')->where('user_id', auth()->user()->userid);
    }

    public function nurse_transactions(): HasMany
    {
        return $this->hasMany(NurseTransaction::class, 'user_id')->where('user_id', auth()->user()->userid);
    }
}
