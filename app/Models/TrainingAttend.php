<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class TrainingAttend extends Model
{

    public function user()
    {
        return $this->belongsTo(TrainingUser::class, 'user_id');
    }

    public function date()
    {
        return $this->belongsTo(TrainingDate::class, 'date_id');
    }

    protected function dateName(): Attribute
    {
        return Attribute::make(
            get: function () {
                $date  = $this->name;
                $isYmd = false;
                if ($date) {
                    $dt    = \DateTime::createFromFormat('Y-m-d', $date);
                    $isYmd = $dt && $dt->format('Y-m-d') === $date;
                }

                if (! $isYmd) {
                    // Fallback value if not a valid date
                    return $date ?: '-';
                }

                $days   = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
                $months = [
                    1  => 'มกราคม',
                    2  => 'กุมภาพันธ์',
                    3  => 'มีนาคม',
                    4  => 'เมษายน',
                    5  => 'พฤษภาคม',
                    6  => 'มิถุนายน',
                    7  => 'กรกฎาคม',
                    8  => 'สิงหาคม',
                    9  => 'กันยายน',
                    10 => 'ตุลาคม',
                    11 => 'พฤศจิกายน',
                    12 => 'ธันวาคม',
                ];
                $dt        = date_create($date);
                $dayOfWeek = $days[(int) date_format($dt, 'w')];
                $day       = (int) date_format($dt, 'j');
                $month     = $months[(int) date_format($dt, 'n')];
                $year      = (int) date_format($dt, 'Y');

                return "$day $month $year";
            }
        );
    }
}
