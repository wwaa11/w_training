<?php
namespace App\Exports;

use App\Models\NurseProject;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseLectureExport implements FromView, ShouldAutoSize, WithDrawings
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

        $project_id = $this->project_id;
        $project    = NurseProject::find($project_id);
        $row        = 0;
        foreach ($project->dateData as $date) {
            foreach ($date->lecturesData as $index => $lecture) {
                $base64 = explode(',', $lecture->userData->sign, 2);
                if (isset($base64[1])) {
                    $sign = imagecreatefromstring(base64_decode($base64[1]));
                    if ($sign !== false) {
                        imagesavealpha($sign, true);

                        $drawing = new MemoryDrawing();
                        $drawing->setImageResource($sign);
                        $drawing->setHeight(15);
                        $drawing->setWidth(120);
                        $drawing->setCoordinates('G' . ($row + 5));
                        $drawings[] = $drawing;
                    }
                }

                $row += 1;
            }
        }

        return $drawings;
    }

    public function view(): view
    {
        $project_id = $this->project_id;
        $project    = NurseProject::find($project_id);

        $project_date = "";
        $project_time = [];
        foreach ($project->dateData as $date) {
            $project_date .= $date->title . " - ";
            foreach ($date->timeData as $time) {
                $project_time[] = $time->title;
            }
        }

        return view('nurse.admin.export.Lecturer')->with(compact('project', 'project_date', 'project_time'));
    }
}
