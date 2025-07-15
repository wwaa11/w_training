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

        // Fetch all active projects, ordered by register_start
        $projects = NurseProject::where('active', true)
            ->orderBy('register_start', 'asc')
            ->get();

        // Header row
        $header = [
            'รหัสพนักงงาน',
            'ชื่อ - สกุล',
            'ตำแหน่ง',
        ];
        foreach ($projects as $project) {
            $header[] = $project->title;
        }
        $header[] = 'วิทยากร';
        $header[] = 'Total';
        $data[0]  = $header;

        // Fetch nurses in the department
        $nurses = User::where('department', $this->department)
            ->orderBy('department', 'asc')
            ->orderBy('userid', 'asc')
            ->get();

        // Fetch all relevant transactions and lectures
        $transactions = NurseTransaction::where('active', true)
            ->whereNotNull('user_sign')
            ->whereNotNull('admin_sign')
            ->get();

        $lectures = NurseLecture::where('active', true)->get();

        foreach ($nurses as $nurse) {
            $row = [
                'user'     => $nurse->userid,
                'name'     => $nurse->name,
                'position' => $nurse->position,
            ];

            $totalScore   = 0;
            $lectureScore = 0;

            // Count transactions for each project
            foreach ($projects as $project) {
                $count = $transactions->where('user_id', $nurse->userid)
                    ->where('nurse_project_id', $project->id)
                    ->count();
                $row[$project->id] = $count > 0 ? $count : null;
                $totalScore += $count;
            }

            // Sum lecture scores
            $nurseLectures = $lectures->where('user_id', $nurse->userid);
            foreach ($nurseLectures as $lecture) {
                $lectureScore += $lecture->score;
                $totalScore += $lecture->score;
            }

            $row[] = $lectureScore;
            $row[] = $totalScore;

            $data[$nurse->userid] = $row;
        }

        return $data;
    }
}
