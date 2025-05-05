<?php
namespace App\Exports;

use App\Models\NurseDate;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NurseDateExport implements FromArray, ShouldAutoSize, WithHeadings
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
}
