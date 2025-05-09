<?php
namespace App\Exports;

use App\Models\NurseLecture;
use App\Models\NurseProject;
use App\Models\NurseTransaction;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;

class NurseScoreExport implements FromArray
{
    protected $department;

    public function __construct($department)
    {
        $this->department = $department;
    }
    public function array(): array
    {
        $data = [];

        $projects = NurseProject::where('active', true)
            ->orderBy('register_start', 'asc')
            ->get();
        $data[0] = [
            'รหัสพนักงงาน',
            'ชื่อ - สกุล',
            'ตำแหน่ง',
        ];
        foreach ($projects as $project) {
            $data[0][] = $project->title;
        }
        $data[0][] = 'วิทยากร';
        $data[0][] = 'Total';

        $nurses = User::where('department', $this->department)
            ->orderBy('department', 'asc')
            ->orderBy('userid', 'asc')
            ->get();

        $transactions = NurseTransaction::where('active', true)
            ->whereNotNull('user_sign')
            ->whereNotNull('admin_sign')
            ->get();

        $lecture = NurseLecture::where('active', true)->get();

        foreach ($nurses as $index => $nurse) {
            $data[$nurse->userid] = [
                'user'     => $nurse->userid,
                'name'     => $nurse->name,
                'position' => $nurse->position,
            ];
            $lectureCount = collect($lecture)->where('user_id', $nurse->userid)->count();
            $score        = 0;
            foreach ($projects as $project) {
                $data[$nurse->userid][$project->title] = null;
                $countTransaction                      = collect($transactions)->where('user_id', $nurse->userid)->where('nurse_project_id', $project->id)->count();
                if ($countTransaction > 0) {
                    $data[$nurse->userid][$project->title] = $countTransaction;
                    $score += $countTransaction;
                }
            }
            if ($lectureCount > 0) {
                $data[$nurse->userid]['lecture'] = $lectureCount * 5;
                $score += $lectureCount * 5;
            } else {
                $data[$nurse->userid]['lecture'] = $lectureCount * 0;
            }

            $data[$nurse->userid]['total'] = $score;
        }

        return $data;
    }
}
