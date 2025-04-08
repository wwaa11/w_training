<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slot extends Model
{

    protected $fillable = [
        'project_id',
        'slot_name',
        'slot_active',
    ];

    protected function dateThai(): Attribute
    {
        $strTime   = strtotime($this->slot_date);
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
        $strTime = strtotime($this->slot_date);
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class)->where('item_active', true)->orderBy('item_index', 'asc');
    }
}
