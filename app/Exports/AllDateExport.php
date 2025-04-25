<?php
namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class AllDateExport implements FromArray, WithDrawings, ShouldAutoSize, WithEvents, WithHeadings
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
            'รหัสพนักงงาน',
            'ชื่อ - นามสกุล',
            'ตำแหน่ง',
            'แผนก',
            'CHECK-IN',
            'HR-Approve',
            // 'ลายเซ็นต์',
        ];
    }

    public function drawings()
    {
        $transactions = Transaction::where('project_id', $this->project_id)
            ->where('transaction_active', true)
            ->orderBy('user', 'ASC')
            ->get();
        $drawings = [];
        foreach ($transactions as $index => $transaction) {
            if ($transaction->userData->sign !== null) {
                $base64 = explode(',', $transaction->userData->sign, 2);
                $sign   = imagecreatefromstring(base64_decode($base64[1]));
                imagesavealpha($sign, true);

                $drawing = new MemoryDrawing();
                $drawing->setImageResource($sign);
                $drawing->setHeight(15);
                $drawing->setWidth(120);
                $drawing->setCoordinates('H' . ($index + 2));
                // $drawings[] = $drawing;
            }
        }
        return $drawings;
    }

    public function array(): array
    {
        $transactionArray = [];
        $transactions     = Transaction::where('project_id', $this->project_id)
            ->where('transaction_active', true)
            ->orderBy('user', 'ASC')
            ->get();
        foreach ($transactions as $index => $transaction) {

            $transactionArray[] = [
                $index + 1,
                $transaction->user,
                $transaction->userData->name,
                $transaction->userData->position,
                $transaction->userData->department,
                ($transaction->checkin) ? date('Y-m-d H:i', strtotime($transaction->checkin_datetime)) : null,
                ($transaction->hr_approve) ? date('Y-m-d H:i', strtotime($transaction->hr_approve_datetime)) : null,
            ];
        }

        return $transactionArray;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                for ($i = 0; $i < 2000; $i++) {
                    // $event->sheet->getRowDimension($i)->setRowHeight(50);
                }
            },
        ];
    }
}
