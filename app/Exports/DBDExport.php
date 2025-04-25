<?php
namespace App\Exports;

use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class DBDExport implements FromView, WithDrawings, WithColumnFormatting
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function drawings()
    {
        $transactions = Transaction::where('project_id', $this->project_id)
            ->where('transaction_active', true)
            ->orderby('user', 'ASC')
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
        $project      = Project::find($this->project_id);
        $transactions = Transaction::where('project_id', $this->project_id)
            ->where('transaction_active', true)
            ->orderby('user', 'ASC')
            ->get();

        return view('hr.admin.export.DBD')->with(compact('project', 'transactions'));
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Set column B to text format
        ];
    }
}
