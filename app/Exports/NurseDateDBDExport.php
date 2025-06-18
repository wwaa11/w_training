<?php
namespace App\Exports;

use App\Models\NurseDate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseDateDBDExport implements FromView, WithDrawings, WithColumnFormatting
{
    protected $date_id;

    public function __construct($date_id)
    {
        $this->date_id = $date_id;
    }

    public function drawings()
    {
        $date = NurseDate::find($this->date_id);

        $drawings = [];

        $drawing = new Drawing();
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

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
            if (! in_array($time->title, $project_time)) {
                $project_time[] = $time->title;
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
