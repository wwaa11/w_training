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

    public function dateData()
    {
        return $this->hasMany(NurseDate::class)->where('active', true)->orderBy('date', 'asc');
    }

    public function mytransaction()
    {
        return $this->HasOne(NurseTransaction::class, 'nurse_project_id')->where('user_id', auth()->user()->userid)->where('active', true);
    }

    public function transactionData()
    {
        return $this->hasMany(NurseTransaction::class, 'nurse_project_id')->where('active', true)->orderBy('date_time', 'asc');
    }

    public function transactionApproveData()
    {
        return $this->hasMany(NurseTransaction::class, 'nurse_project_id')->where('active', true)->whereNull('sign')->orderBy('date_time', 'asc');
    }
    public function transactionNotApproveData()
    {
        return $this->hasMany(NurseTransaction::class, 'nurse_project_id')->where('active', true)->whereNotNull('sign')->orderBy('date_time', 'asc');
    }
}
