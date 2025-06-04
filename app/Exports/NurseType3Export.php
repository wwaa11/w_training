<?php
namespace App\Exports;

use App\Models\NurseProject;
use App\Models\NurseTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseType3Export implements FromView, WithDrawings
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function drawings()
    {
        $project        = NurseProject::find($this->project_id);
        $lectures_count = 0;
        foreach ($project->dateData as $date) {
            foreach ($date->lecturesData as $lecture) {
                $lectures_count++;
            }
        }

        $transactions = NurseTransaction::where('nurse_project_id', $this->project_id)
            ->where('active', true)
            ->orderby('date_time', 'ASC')
            ->get();

        $drawings = [];

        $drawing = new Drawing();
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

        foreach ($transactions as $index => $transaction) {
            if ($transaction->userData->sign !== null) {
                $base64 = explode(',', $transaction->userData->sign, 2);
                $sign   = imagecreatefromstring(base64_decode($base64[1]));
                imagesavealpha($sign, true);

                $drawing = new MemoryDrawing();
                $drawing->setImageResource($sign);
                $drawing->setHeight(15);
                $drawing->setWidth(120);
                $drawing->setCoordinates('E' . ($index + 10 + $lectures_count));
                $drawings[] = $drawing;
            }
        }
        return $drawings;
    }

    public function view(): view
    {
        $project      = NurseProject::find($this->project_id);
        $transactions = NurseTransaction::where('nurse_project_id', $this->project_id)
            ->where('active', true)
            ->orderby('date_time', 'ASC')
            ->get();
        $lectures = [];
        foreach ($project->dateData as $date) {
            foreach ($date->lecturesData as $lecture) {
                $lectures[] = [
                    'userid'   => $lecture->user_id,
                    'name'     => $lecture->userData->name,
                    'position' => $lecture->userData->position,
                ];
            }
        }

        return view('nurse.admin.export.type_3')->with(compact('project', 'lectures', 'transactions'));
    }

}
