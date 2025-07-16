<?php
namespace App\Exports;

use App\Models\NurseProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseUserExport implements FromArray, ShouldAutoSize, WithDrawings
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
            foreach ($date->timeData as $time) {
                foreach ($time->transactionData as $index => $transaction) {
                    if ($transaction->userData->sign !== null) {
                        $base64 = explode(',', $transaction->userData->sign, 2);
                        $sign   = imagecreatefromstring(base64_decode($base64[1]));
                        imagesavealpha($sign, true);

                        $drawing = new MemoryDrawing();
                        $drawing->setImageResource($sign);
                        $drawing->setHeight(15);
                        $drawing->setWidth(120);
                        $drawing->setCoordinates('J' . ($row + 2));
                        $drawings[] = $drawing;
                    }

                    $row += 1;
                }
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
            'รอบ',
            'รหัสพนักงงาน',
            'ชื่อ - สกุล',
            'ตำแหน่ง',
            'แผนก',
            'CHECK IN',
            'APPROVE',
        ];
        foreach ($project->dateData as $date) {
            foreach ($date->timeData as $time) {
                foreach ($time->transactionData as $index => $transaction) {
                    $data[] = [
                        $index + 1,
                        $date->title,
                        $time->title,
                        $transaction->user_id,
                        $transaction->userData->name,
                        $transaction->userData->position,
                        $transaction->userData->department,
                        $transaction->user_sign,
                        $transaction->admin_sign,
                    ];
                }
            }
        }

        return $data;
    }
}
