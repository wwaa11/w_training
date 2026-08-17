<?php
namespace App\Exports\Hr;

use App\Models\HrLecture;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class LectureExport implements FromArray, ShouldAutoSize, WithDrawings, WithEvents, WithHeadings
{
    protected $project_id;

    protected $date_id;

    protected $lectures;

    public function __construct($project_id, $date_id = null)
    {
        $this->project_id = $project_id;
        $this->date_id    = $date_id;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'วันที่',
            'รหัสพนักงาน',
            'ชื่อ - นามสกุล',
            'ตำแหน่ง',
            'แผนก',
            'ลายเซ็นต์',
        ];
    }

    /**
     * Row order must stay identical between the data rows and the signature
     * drawings, so both read from this single cached query.
     */
    protected function lectures()
    {
        if ($this->lectures === null) {
            $query = HrLecture::with(['user', 'date'])
                ->where('active', true)
                ->whereHas('date', function ($dateQuery) {
                    $dateQuery->where('project_id', $this->project_id)
                        ->where('date_delete', false);
                });

            if ($this->date_id) {
                $query->where('date_id', $this->date_id);
            }

            $this->lectures = $query->orderBy('date_id', 'ASC')
                ->orderBy('user_id', 'ASC')
                ->get();
        }

        return $this->lectures;
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->lectures() as $index => $lecture) {
            if (! $lecture->user || $lecture->user->sign === null) {
                continue;
            }

            $base64 = explode(',', $lecture->user->sign, 2);
            if (count($base64) < 2) {
                continue;
            }

            $sign = imagecreatefromstring(base64_decode($base64[1]));
            if (! $sign) {
                continue;
            }

            imagesavealpha($sign, true);

            // Width is left to scale proportionally so signatures stay legible
            $drawing = new MemoryDrawing();
            $drawing->setImageResource($sign);
            $drawing->setHeight(30);
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(3);
            $drawing->setCoordinates('G' . ($index + 2));
            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function array(): array
    {
        $lectureArray = [];

        foreach ($this->lectures() as $index => $lecture) {
            $lectureArray[] = [
                $index + 1,
                $lecture->date ? $lecture->date->date_title : 'N/A',
                $lecture->user ? $lecture->user->userid : 'N/A',
                $lecture->user ? $lecture->user->name : 'N/A',
                $lecture->user ? $lecture->user->position : 'N/A',
                $lecture->user ? $lecture->user->department : 'N/A',
                null,
            ];
        }

        return $lectureArray;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getColumnDimension('A')->setWidth(8);  // ลำดับ
                $event->sheet->getColumnDimension('B')->setWidth(25); // วันที่
                $event->sheet->getColumnDimension('C')->setWidth(15); // รหัสพนักงาน
                $event->sheet->getColumnDimension('D')->setWidth(25); // ชื่อ - นามสกุล
                $event->sheet->getColumnDimension('E')->setWidth(20); // ตำแหน่ง
                $event->sheet->getColumnDimension('F')->setWidth(20); // แผนก

                // Signature images are placed here, so keep a fixed width
                $event->sheet->getColumnDimension('G')->setAutoSize(false);
                $event->sheet->getColumnDimension('G')->setWidth(30);

                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font'      => [
                        'bold'  => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill'      => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $highestRow = $event->sheet->getHighestRow();

                $event->sheet->getStyle('A1:G' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('A2:G' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Signature images need room to render inside their row
                for ($row = 2; $row <= $highestRow; $row++) {
                    $event->sheet->getRowDimension($row)->setRowHeight(35);
                }
            },
        ];
    }
}
