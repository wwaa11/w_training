<?php
namespace App\Exports\Hr;

use App\Models\HrProject;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultsTemplateExport implements FromArray, WithHeadings, WithStyles
{
    protected $project;

    public function __construct(HrProject $project)
    {
        $this->project = $project;
    }

    public function array(): array
    {
        $data = [];

        // Get attendance records grouped by user_id to avoid duplicates
        $attends = $this->project->attends()
            ->where('attend_delete', false)
            ->get()
            ->groupBy('user_id')
            ->map(function ($userAttends) {
                // Return the first attendance record for each user
                return $userAttends->first();
            })
            ->values();

        foreach ($attends as $index => $attend) {
            $row = [
                $index + 1,
                $attend->user->userid ?? '',
                $attend->user->name ?? '',
                $attend->user->position ?? '',
                $attend->user->department ?? '',
                '', // result_1
                '', // result_2
                '', // result_3
                '', // result_4
                '', // result_5
                '', // result_6
                '', // result_7
                '', // result_8
                '', // result_9
                '', // result_10
            ];
            $data[] = $row;
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงาน',
            'ชื่อ-นามสกุล',
            'ตำแหน่ง',
            'หน่วยงาน',
            'ผลการประเมิน 1',
            'ผลการประเมิน 2',
            'ผลการประเมิน 3',
            'ผลการประเมิน 4',
            'ผลการประเมิน 5',
            'ผลการประเมิน 6',
            'ผลการประเมิน 7',
            'ผลการประเมิน 8',
            'ผลการประเมิน 9',
            'ผลการประเมิน 10',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
