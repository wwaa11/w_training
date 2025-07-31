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

    public function training_team(): HasMany
    {
        return $this->hasMany(TrainingUser::class, 'user_id')->where('user_id', auth()->user()->userid);
    }

    // HR System Relationships
    public function hrAttends(): HasMany
    {
        return $this->hasMany(HrAttend::class, 'user_id');
    }

    public function hrSeats(): HasMany
    {
        return $this->hasMany(HrSeat::class, 'user_id');
    }

    public function hrResults(): HasMany
    {
        return $this->hasMany(HrResult::class, 'user_id');
    }
}
