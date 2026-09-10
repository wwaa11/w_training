<?php
namespace App\Exports\Hr;

use App\Models\HrGroup;
use App\Models\HrProject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HrGroupsTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    private $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection()
    {
        $project = HrProject::findOrFail($this->projectId);

        $groupAssignments = HrGroup::where('project_id', $this->projectId)
            ->get()
            ->keyBy('user_id');

        $participants = $project->activeAttends()
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($userAttends) {
                return $userAttends->first();
            })
            ->sortBy(function ($attend) {
                return $attend->user->userid ?? '';
            })
            ->values();

        $rows = collect();

        $rows->push([
            'user_id'    => 'คำแนะนำ: กรอกรหัสพนักงานในระบบ',
            'group_name' => 'คำแนะนำ: กรอกชื่อกลุ่มที่ต้องการจัด',
        ]);

        foreach ($participants as $attend) {
            $user = $attend->user;

            if (! $user || ! $user->userid) {
                continue;
            }

            $rows->push([
                'user_id'    => $user->userid,
                'group_name' => $groupAssignments->get($user->id)?->group ?? '',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'user_id',
            'group_name',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
    }
}
