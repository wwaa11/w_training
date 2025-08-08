<?php
namespace App\Exports\Hr;

use App\Models\HrAttend;
use App\Models\HrProject;
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
        $attends = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $this->project_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->get();

        $drawings = [];

        // Add logo
        $drawing = new Drawing();
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

        // Add signatures
        foreach ($attends as $index => $attend) {
            if ($attend->user && $attend->user->sign !== null) {
                $base64 = explode(',', $attend->user->sign, 2);
                if (count($base64) > 1) {
                    $sign = imagecreatefromstring(base64_decode($base64[1]));
                    if ($sign) {
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
        }
        return $drawings;
    }

    public function view(): View
    {
        $project = HrProject::find($this->project_id);
        $attends = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $this->project_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->get();

        return view('hrd.admin.export.dbd-report')->with(compact('project', 'attends'));
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Set column B to text format
        ];
    }
}
