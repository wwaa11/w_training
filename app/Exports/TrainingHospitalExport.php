<?php
namespace App\Exports;

use App\Models\TrainingDate;
use DateTime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class TrainingHospitalExport implements FromView, WithDrawings
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function drawings()
    {
        $date     = $this->date;
        $drawings = [];

        $drawing = new Drawing();
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

        $index = 0;
        $dates = TrainingDate::where('name', $date)->get();
        foreach ($dates as $trainingDate) {
            foreach ($trainingDate->time->users as $user) {
                if ($user->userData->sign !== null) {
                    $base64 = explode(',', $user->userData->sign, 2);
                    $sign   = imagecreatefromstring(base64_decode($base64[1]));
                    imagesavealpha($sign, true);

                    $drawing = new MemoryDrawing();
                    $drawing->setImageResource($sign);
                    $drawing->setHeight(15);
                    $drawing->setWidth(120);
                    $drawing->setCoordinates('I' . ($index + 6));
                    $drawings[] = $drawing;

                    $drawing = new MemoryDrawing();
                    $drawing->setImageResource($sign);
                    $drawing->setHeight(15);
                    $drawing->setWidth(120);
                    $drawing->setCoordinates('J' . ($index + 6));
                    $drawings[] = $drawing;
                    $index++;
                }
            }
        }

        return $drawings;
    }

    public function dateFull($date)
    {
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
        $year      = (int) date_format($dt, 'Y') + 543;

        $arr = [
            'dayOfWeek' => $dayOfWeek,
            'day'       => $day,
            'month'     => $month,
            'year'      => $year,
        ];

        return $arr;
    }

    public function view(): view
    {
        $date     = $this->date;
        $dateFull = $this->dateFull($date);

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

        return view('training.admin.exports.hospital_forms')->with(compact('dateFull', 'exportRows'));
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
            'id_card'    => $user->userData->refNo,
            'user_id'    => $user->userData->userid,
            'name'       => $user->userData->name,
            'position'   => $user->userData->position,
            'department' => $user->userData->department,
            'gender'     => $user->userData->gender,
        ];
    }
}
