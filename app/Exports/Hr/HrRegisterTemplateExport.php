<?php
namespace App\Exports\Hr;

use App\Models\HrProject;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HrRegisterTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    private $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection()
    {
                                                        // Get all users from the system to provide sample data
        $users = User::inRandomOrder()->take(5)->get(); // Only show first 5 as examples

        $sampleData = collect();

        // Add instruction row
        $sampleData->push([
            'user_id'          => 'คำแนะนำ: กรอกรหัสพนักงานในระบบ',
            'date'             => 'คำแนะนำ: กรอกวันที่',
            'time'             => 'คำแนะนำ: กรอกช่วงเวลา *ช่วงเวลาต้องมีอยู่ในระบบ',
            'attend_datetime'  => 'คำแนะนำ: กรอกวันที่และเวลาที่ Check In',
            'approve_datetime' => 'คำแนะนำ: กรอกวันที่และเวลาที่ Approve',
        ]);

        $project = HrProject::find($this->projectId);
        try {
            $firstDate   = $project->dates()->first();
            $firstTime   = $firstDate->times()->first();
            $date        = substr($firstDate->date_datetime, 0, 10);
            $time        = trim($firstTime->time_title);
            $expoldeTime = explode('-', $time);
        } catch (\Throwable $th) {
            $date        = '';
            $time        = '';
            $expoldeTime = ['', ''];
        }

        // Add sample data
        foreach ($users as $user) {
            $sampleData->push([
                'user_id'          => $user->userid,
                'date'             => $date,
                'time'             => $time,
                'attend_datetime'  => $date . ' ' . $expoldeTime[0],
                'approve_datetime' => $date . ' ' . $expoldeTime[1],
            ]);
        }

        return $sampleData;
    }

    public function headings(): array
    {
        return [
            'user_id',
            'date',
            'time',
            'attend_datetime',
            'approve_datetime',
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
