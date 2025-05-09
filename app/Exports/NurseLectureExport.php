<?php
namespace App\Exports;

use App\Models\NurseProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseLectureExport implements FromArray, ShouldAutoSize, WithDrawings
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function drawings()
    {
        $project_id = $this->project_id;
        $project    = NurseProject::find($project_id);
        $row        = 0;
        foreach ($project->dateData as $date) {
            foreach ($date->lecturesData as $index => $lecture) {
                $base64 = explode(',', $lecture->userData->sign, 2);
                $sign   = imagecreatefromstring(base64_decode($base64[1]));
                imagesavealpha($sign, true);

                $drawing = new MemoryDrawing();
                $drawing->setImageResource($sign);
                $drawing->setHeight(15);
                $drawing->setWidth(120);
                $drawing->setCoordinates('H' . ($row + 2));
                $drawings[] = $drawing;

                $row += 1;
            }
        }

        return $drawings;
    }

    public function array(): array
    {
        $project_id = $this->project_id;
        $project    = NurseProject::find($project_id);

        $data   = [];
        $data[] = [
            '#',
            'วันที่',
            'รอบเวลา',
            'รหัสพนักงงาน',
            'ชื่อ - สกุล',
            'ตำแหน่ง',
            'แผนก',
            'ลายเซ็นต์',
        ];

        foreach ($project->dateData as $date) {
            foreach ($date->timeData as $i => $time) {
                if ($i == 0) {
                    $timeLabel = $time->title;
                } else {
                    $timeLabel .= ', ' . $time->title;

                }
            }
            foreach ($date->lecturesData as $index => $lecture) {
                $data[] = [
                    $index + 1,
                    $date->title,
                    $timeLabel,
                    $lecture->user_id,
                    $lecture->userData->name,
                    $lecture->userData->position,
                    $lecture->userData->department,
                ];
            }
        }

        return $data;
    }
}
