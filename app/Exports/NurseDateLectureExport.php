<?php
namespace App\Exports;

use App\Models\NurseDate;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseDateLectureExport implements FromArray, ShouldAutoSize, WithDrawings
{
    protected $date_id;

    public function __construct($date_id)
    {
        $this->date_id = $date_id;
    }

    public function drawings()
    {
        $date = NurseDate::find($this->date_id);
        $row  = 0;
        foreach ($date->lecturesData as $index => $lecture) {
            $base64 = explode(',', $lecture->userData->sign, 2);
            $sign   = imagecreatefromstring(base64_decode($base64[1]));
            imagesavealpha($sign, true);

            $drawing = new MemoryDrawing();
            $drawing->setImageResource($sign);
            $drawing->setHeight(15);
            $drawing->setWidth(120);
            $drawing->setCoordinates('F' . ($row + 3));
            $drawings[] = $drawing;

            $row += 1;
        }

        return $drawings;
    }

    public function array(): array
    {
        $date             = NurseDate::find($this->date_id);
        $transactionArray = [
            [
                'วิทยากร ' . $date->projectData->title,
                $date->title,
                'รอบเวลา',
            ],
            [
                'ลำดับ',
                'รหัสพนักงงาน',
                'ชื่อ - นามสกุล',
                'ตำแหน่ง',
                'แผนก',
            ],
        ];
        foreach ($date->timeData as $time) {
            $transactionArray[0][] = $time->title;
        }
        foreach ($date->lecturesData as $index => $lecture) {
            $transactionArray[] = [
                $index += 1,
                $lecture->user_id,
                $lecture->userData->name,
                $lecture->userData->position,
                $lecture->userData->department,
            ];
        }

        return $transactionArray;
    }

}
