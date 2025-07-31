<?php
namespace App\Exports\Hr;

use App\Models\HrAttend;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class AllDateExport implements FromArray, ShouldAutoSize, WithEvents, WithHeadings
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงาน',
            'ชื่อ - นามสกุล',
            'ตำแหน่ง',
            'แผนก',
            'วันที่',
            'เวลา',
            'CHECK-IN',
            'HR-Approve',
            'ที่นั่ง',
        ];
    }

    public function array(): array
    {
        $attendArray = [];
        $attends     = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $this->project_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->get();

        foreach ($attends as $index => $attend) {
            $attendArray[] = [
                $index + 1,
                $attend->user ? $attend->user->userid : 'N/A',
                $attend->user ? $attend->user->name : 'N/A',
                $attend->user ? $attend->user->position : 'N/A',
                $attend->user ? $attend->user->department : 'N/A',
                $attend->date ? $attend->date->date_title : 'N/A',
                $attend->time ? $attend->time->time_title : 'N/A',
                $attend->attend_datetime ? $attend->attend_datetime->format('Y-m-d H:i') : null,
                $attend->approve_datetime ? $attend->approve_datetime->format('Y-m-d H:i') : null,
                $attend->seat ? $attend->seat->seat_number : null,
            ];
        }

        return $attendArray;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                                                                      // Set column widths
                $event->sheet->getColumnDimension('A')->setWidth(8);  // ลำดับ
                $event->sheet->getColumnDimension('B')->setWidth(15); // รหัสพนักงาน
                $event->sheet->getColumnDimension('C')->setWidth(25); // ชื่อ - นามสกุล
                $event->sheet->getColumnDimension('D')->setWidth(20); // ตำแหน่ง
                $event->sheet->getColumnDimension('E')->setWidth(20); // แผนก
                $event->sheet->getColumnDimension('F')->setWidth(15); // วันที่
                $event->sheet->getColumnDimension('G')->setWidth(15); // เวลา
                $event->sheet->getColumnDimension('H')->setWidth(15); // CHECK-IN
                $event->sheet->getColumnDimension('I')->setWidth(15); // HR-Approve
                $event->sheet->getColumnDimension('J')->setWidth(10); // ที่นั่ง

                // Style the header row
                $event->sheet->getStyle('A1:J1')->applyFromArray([
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

                // Add borders to all cells
                $event->sheet->getStyle('A1:J' . ($event->sheet->getHighestRow()))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
