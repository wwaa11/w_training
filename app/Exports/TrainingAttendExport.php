<?php
namespace App\Exports;

use App\Models\TrainingDate;
use DateTime;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingAttendExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function array(): array
    {
        $date = $this->date;

        $dates      = TrainingDate::where('name', $date)->get();
        $exportRows = [];
        foreach ($dates as $trainingDate) {
            $totalHours = $this->calculateTrainingHours($trainingDate->time->name);
            foreach ($trainingDate->time->users as $user) {
                $exportRows[] = $this->buildExportRow($user, $trainingDate, $date, $totalHours);
            }
        }
        foreach ($exportRows as $index => $row) {
            $exportRows[$index] = ['ลำดับ' => $index + 1] + $row;
        }

        return $exportRows;
    }

    private function calculateTrainingHours($timeRange)
    {
        $parts = explode('-', $timeRange);
        if (count($parts) === 2) {
            list($start, $end) = array_map('trim', $parts);
            $startTime         = DateTime::createFromFormat('H:i', $start);
            $endTime           = DateTime::createFromFormat('H:i', $end);
            if ($startTime && $endTime) {
                $interval = $startTime->diff($endTime);
                $hours    = $interval->h;
                $minutes  = $interval->i;
                return $hours . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT);
            }
        }
        return '-';
    }

    private function buildExportRow($user, $trainingDate, $date, $totalHours)
    {
        $attend = $user->attend($date)->first();
        return [
            'รหัสพนักงงาน'   => $user->userData->userid,
            'ชื่อ - นามสกุล' => $user->userData->name,
            'ตำแหน่ง'        => $user->userData->position,
            'แผนก'           => $user->userData->department,
            'วันที่เรียน'    => $date,
            'เวลาที่เรียน'   => $trainingDate->time->name,
            'Teacher'        => $trainingDate->time->session->teacher->name,
            'ชั่วโมงอบรม'    => $totalHours,
            'CHECK-IN'       => $attend && $attend->user ? $attend->user_date : '',
            'HR-Approve'     => $attend && $attend->admin ? $attend->admin_date : '',
        ];
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงงาน',
            'ชื่อ - นามสกุล',
            'ตำแหน่ง',
            'แผนก',
            'วันที่เรียน',
            'เวลาที่เรียน',
            'Teacher',
            'ชั่วโมงอบรม',
            'CHECK-IN',
            'HR-Approve',
        ];
    }
}
