<?php
namespace App\Exports;

use App\Models\Slot;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DateExport implements FromArray, ShouldAutoSize, WithHeadings
{
    protected $slot_id;

    public function __construct($slot_id)
    {
        $this->slot_id = $slot_id;
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
            'รอบ',
        ];
    }

    public function array(): array
    {
        $transactionArray = [];
        $slot             = Slot::find($this->slot_id);
        $index            = 0;
        foreach ($slot->items as $item) {
            foreach ($item->transactions as $index => $transaction) {
                if ($transaction->transaction_active) {
                    $transactionArray[] = [
                        $index += 1,
                        $transaction->user,
                        $transaction->userData->name,
                        $transaction->userData->position,
                        $transaction->userData->department,
                        ($transaction->checkin) ? date('Y-m-d H:i', strtotime($transaction->checkin_datetime)) : null,
                        ($transaction->hr_approve) ? date('Y-m-d H:i', strtotime($transaction->hr_approve_datetime)) : null,
                        $item->item_name,
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
