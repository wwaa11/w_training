<?php
namespace App\Exports;

use App\Models\NurseDate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseDateLectureExport implements FromView, ShouldAutoSize, WithDrawings
{
    protected $date_id;

    public function __construct($date_id)
    {
        $this->date_id = $date_id;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

        $date = NurseDate::find($this->date_id);
        $row  = 0;
        foreach ($date->lecturesData as $index => $lecture) {
            if ($lecture->userData->sign !== null) {
                $base64 = explode(',', $lecture->userData->sign, 2);
                $sign   = imagecreatefromstring(base64_decode($base64[1]));
                imagesavealpha($sign, true);

                $drawing = new MemoryDrawing();
                $drawing->setImageResource($sign);
                $drawing->setHeight(15);
                $drawing->setWidth(120);
                $drawing->setCoordinates('G' . ($row + 5));
                $drawings[] = $drawing;
            }

            $row += 1;
        }

        return $drawings;
    }

    public function view(): view
    {
        $date    = NurseDate::find($this->date_id);
        $project = $date->projectData;

        $transactions = [];
        foreach ($date->timeData as $time) {
            foreach ($time->transactionData as $index => $transaction) {
                if ($transaction->active) {
                    $transactions[] = $transaction;
                }
            }
        }

        $project_date = $date->title;
        $project_time = [];
        foreach ($date->timeData as $time) {
            $project_time[] = $time->title;
        }

        return view('nurse.admin.export.Lecturer')->with(compact('project', 'project_date', 'project_time'));
    }
}
