<?php
namespace App\Exports\Hr;

use App\Models\HrAttend;
use App\Models\HrDate;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DateExport implements FromArray, ShouldAutoSize, WithHeadings
{
    protected $date_id;

    public function __construct($date_id)
    {
        $this->date_id = $date_id;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงาน',
            'ชื่อ - นามสกุล',
            'ตำแหน่ง',
            'แผนก',
            'เวลา',
            'CHECK-IN',
            'HR-Approve',
            'ที่นั่ง',
        ];
    }

    public function array(): array
    {
        $attendArray = [];
        $date        = HrDate::find($this->date_id);

        if (! $date) {
            return $attendArray;
        }

        $attends = HrAttend::with(['user', 'time'])
            ->where('date_id', $this->date_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->get();

        foreach ($attends as $index => $attend) {
            $attendArray[] = [
                $index + 1,
                $attend->user ? $attend->user->userid : 'N/A',
                $attend->user ? $attend->user->name : 'N/A',
                $attend->user ? $attend->user->position : 'N/A',
                $attend->user ? $attend->user->department : 'N/A',
                $attend->time ? $attend->time->time_title : 'N/A',
                $attend->attend_datetime ? $attend->attend_datetime->format('Y-m-d H:i') : null,
                $attend->approve_datetime ? $attend->approve_datetime->format('Y-m-d H:i') : null,
                $attend->seat ? $attend->seat->seat_number : null,
            ];
        }

        return $attendArray;
    }
}
