<?php
namespace App\Exports;

use App\Models\User;
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
                                       // Get all users from the system to provide sample data
        $users = User::take(5)->get(); // Only show first 5 as examples

        $sampleData = collect();

        // Add instruction row
        $sampleData->push([
            'user_id'    => 'คำแนะนำ: กรอกรหัสพนักงานในระบบ',
            'group_name' => 'คำแนะนำ: กรอกชื่อกลุ่มที่ต้องการจัด',
        ]);

        // Add sample data
        foreach ($users as $user) {
            $sampleData->push([
                'user_id'    => $user->userid,
                'group_name' => 'กลุ่ม A',
            ]);
        }

        return $sampleData;
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
