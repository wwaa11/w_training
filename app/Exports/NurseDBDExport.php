<?php
namespace App\Exports;

use App\Models\NurseProject;
use App\Models\NurseTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseDBDExport implements FromView, WithDrawings, WithColumnFormatting
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function drawings()
    {
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
                $drawing->setCoordinates('I' . ($index + 6));
                $drawings[] = $drawing;

                $drawing = new MemoryDrawing();
                $drawing->setImageResource($sign);
                $drawing->setHeight(15);
                $drawing->setWidth(120);
                $drawing->setCoordinates('J' . ($index + 6));
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

        $project_date = "";
        $project_time = [];
        foreach ($project->dateData as $date) {
            $project_date .= $date->title . " - ";
            foreach ($date->timeData as $time) {
                if (! in_array($time->title, $project_time)) {
                    $project_time[] = $time->title;
                }
            }
        }

        return view('nurse.admin.export.DBD')->with(compact('project', 'transactions', 'project_date', 'project_time'));
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Set column B to text format
        ];
    }
}
