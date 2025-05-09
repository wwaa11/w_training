<?php
namespace App\Exports;

use App\Models\NurseProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NurseUserExport implements FromArray, ShouldAutoSize
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function array(): array
    {
        $project_id = $this->project_id;
        $project    = NurseProject::find($project_id);

        $data   = [];
        $data[] = [
            '#',
            'วันที่',
            'รอบ',
            'รหัสพนักงงาน',
            'ชื่อ - สกุล',
            'ตำแหน่ง',
            'แผนก',
            'CHECK IN',
            'APPROVE',
        ];
        foreach ($project->dateData as $date) {
            foreach ($date->timeData as $time) {
                foreach ($time->transactionData as $index => $transaction) {
                    $data[] = [
                        $index + 1,
                        $date->title,
                        $time->title,
                        $transaction->user_id,
                        $transaction->userData->name,
                        $transaction->userData->position,
                        $transaction->userData->department,
                        $transaction->user_sign,
                        $transaction->admin_sign,
                    ];
                }
            }
        }

        return $data;
    }
}
