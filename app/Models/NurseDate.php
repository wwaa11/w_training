<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function dateThai(): Attribute
    {
        $strTime   = strtotime($this->date);
        $dayOfWeek = date('l', $strTime);

        switch ($dayOfWeek) {
            case "Monday":
                $fullDay = "จันทร์";
                break;
            case "Tuesday":
                $fullDay = "อังคาร";
                break;
            case "Wednesday":
                $fullDay = "พุธ";
                break;
            case "Thursday":
                $fullDay = "พฤหัสบดี";
                break;
            case "Friday":
                $fullDay = "ศุกร์";
                break;
            case "Saturday":
                $fullDay = "เสาร์";
                break;
            case "Sunday":
                $fullDay = "อาทิตย์";
                break;
        }

        return new Attribute(
            get: fn() => $fullDay,
        );
    }

    protected function monthThai(): Attribute
    {
        $strTime = strtotime($this->date);
        $month   = date('m', $strTime);

        $months = [
            "01" => "มกราคม",
            "02" => "กุมภาพันธ์",
            "03" => "มีนาคม",
            "04" => "เมษายน",
            "05" => "พฤษภาคม",
            "06" => "มิถุนายน",
            "07" => "กรกฎาคม",
            "08" => "สิงหาคม",
            "09" => "กันยายน",
            "10" => "ตุลาคม",
            "11" => "พฤศจิกายน",
            "12" => "ธันวาคม",
        ];
        $fullmonth = $months[$month];

        return new Attribute(
            get: fn() => $fullmonth . ' ' . date('Y', $strTime),
        );
    }

    public function projectData()
    {
        return $this->belongsTo(NurseProject::class, 'nurse_project_id');
    }

    public function timeData()
    {
        return $this->hasMany(NurseTime::class)->where('active', true)->orderBy('time_start', 'asc');
    }
}
