<?php
namespace App\Exports;

use App\Models\NurseDate;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class NurseDateExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents, WithDrawings
{
    protected $date_id;

    public function __construct($date_id)
    {
        $this->date_id = $date_id;
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
            'Approve',
            'รอบ',
        ];
    }

    public function drawings()
    {
        $date = NurseDate::find($this->date_id);
        foreach ($date->timeData as $time) {
            foreach ($time->transactionData as $index => $transaction) {
                if ($transaction->active) {
                    $base64 = explode(',', $transaction->userData->sign, 2);
                    $sign   = imagecreatefromstring(base64_decode($base64[1]));
                    imagesavealpha($sign, true);

                    $drawing = new MemoryDrawing();
                    $drawing->setImageResource($sign);
                    $drawing->setHeight(15);
                    $drawing->setWidth(120);
                    $drawing->setCoordinates('I' . ($index + 1));
                    $drawings[] = $drawing;
                }
            }
        }

        return $drawings;
    }

    public function array(): array
    {
        $transactionArray = [];
        $date             = NurseDate::find($this->date_id);
        foreach ($date->timeData as $time) {
            foreach ($time->transactionData as $index => $transaction) {
                if ($transaction->active) {
                    $transactionArray[] = [
                        $index += 1,
                        $transaction->user_id,
                        $transaction->userData->name,
                        $transaction->userData->position,
                        $transaction->userData->department,
                        ($transaction->user_sign) ? date('Y-m-d H:i', strtotime($transaction->user_sign)) : null,
                        ($transaction->admin_sign) ? date('Y-m-d H:i', strtotime($transaction->admin_sign)) : null,
                        $time->title,
                    ];
                }
            }
        }

        // usort($transactionArray, function ($item1, $item2) {
        //     return $item1[1] <=> $item2[1];
        // });

        return $transactionArray;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                for ($i = 1; $i <= 2000; $i++) {
                    $event->sheet->getRowDimension($i)->setRowHeight(50);
                }
            },
        ];
    }
}
