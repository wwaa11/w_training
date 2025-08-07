<?php
namespace App\Http\Controllers;

use App\Exports\TrainingAttendExport;
use App\Exports\TrainingHospitalExport;
use App\Exports\TrainingOnebookExport;
use App\Imports\TrainingTeamImport;
use App\Models\TrainingAttend;
use App\Models\TrainingDate;
use App\Models\TrainingSession;
use App\Models\TrainingTeacher;
use App\Models\TrainingTeam;
use App\Models\TrainingTime;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TrainingController extends Controller
{
    public function checkSlot()
    {

    }
    // Data Management
    public function deleteTestData()
    {
        if (env('APP_ENV') === 'local') {
            $deleted             = [];
            $deleted['teams']    = TrainingTeam::query()->delete();
            $deleted['teachers'] = TrainingTeacher::query()->delete();
            $deleted['sessions'] = TrainingSession::query()->delete();
            $deleted['times']    = TrainingTime::query()->delete();
            $deleted['dates']    = TrainingDate::query()->delete();
            $deleted['attends']  = TrainingAttend::query()->delete();
            $deleted['users']    = TrainingUser::query()->update(['time_id' => null]);

            return response()->json(['status' => 'success', 'deleted' => $deleted]);
        }

        return response()->json(['status' => 'not avaiable']);
    }

    public function seedData()
    {
        if (env('APP_ENV') === 'local') {
            // Create Group
            $teamData = ['A', 'B', 'C'];
            foreach ($teamData as $team) {
                $trainingGroup = TrainingTeam::firstorcreate(['name' => $team]);
            }

            $teacher = [
                'team'     => 'A',
                'name'     => 'Tom',
                'sessions' => ['จันทร์ - พุธ', 'อังคาร - พฤหัส'],
                'times'    => [
                    ['title' => '08:30 - 10:00', 'seat' => 18],
                    ['title' => '10:30 - 12:00', 'seat' => 18],
                    ['title' => '13:00 - 14:30', 'seat' => 18],
                    ['title' => '14:30 - 16:00', 'seat' => 18],
                ],
                'dates'    => [
                    'จันทร์ - พุธ'   => [
                        ['title' => '2025-08-04', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-06', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-11', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-13', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-18', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-20', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-25', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-01', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-03', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-08', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-10', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-15', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-17', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-22', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-24', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-01', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-06', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-08', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-13', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-15', 'location' => 'Meeting room 2 Fl.8'],
                    ],
                    'อังคาร - พฤหัส' => [
                        ['title' => '2025-08-05', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-07', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-12', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-14', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-19', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-21', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-26', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-02', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-04', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-09', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-11', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-16', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-18', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-23', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-25', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-30', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-02', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-09', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-14', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-16', 'location' => 'Meeting room 2 Fl.8'],
                    ],
                ],
            ];
            $this->createTeacher($teacher);
            $teacher = [
                'team'     => 'A',
                'name'     => 'Neill',
                'sessions' => ['จันทร์ - พุธ', 'อังคาร - พฤหัส'],
                'times'    => [
                    ['title' => '08:30 - 10:00', 'seat' => 18],
                    ['title' => '10:30 - 12:00', 'seat' => 18],
                    ['title' => '13:00 - 14:30', 'seat' => 18],
                    ['title' => '14:30 - 16:00', 'seat' => 18],
                    ['title' => '16:15 - 17:45', 'seat' => 18],
                ],
                'dates'    => [
                    'จันทร์ - พุธ'   => [
                        ['title' => '2025-08-04', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-06', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-11', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-13', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-18', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-20', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-25', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-01', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-03', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-08', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-10', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-15', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-17', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-22', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-24', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-01', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-06', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-08', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-13', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-15', 'location' => 'Practice Fl.8'],
                    ],
                    'อังคาร - พฤหัส' => [
                        ['title' => '2025-08-05', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-07', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-12', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-14', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-19', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-21', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-08-26', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-02', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-04', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-09', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-11', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-16', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-18', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-23', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-25', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-09-30', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-02', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-09', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-14', 'location' => 'Practice Fl.8'],
                        ['title' => '2025-10-16', 'location' => 'Practice Fl.8'],
                    ],
                ],
            ];
            $this->createTeacher($teacher);
            $teacher = [
                'team'     => 'A',
                'name'     => 'Gary',
                'sessions' => ['จันทร์ - พุธ', 'อังคาร - พฤหัส'],
                'times'    => [
                    ['title' => '08:30 - 10:00', 'seat' => 18],
                    ['title' => '10:30 - 12:00', 'seat' => 18],
                    ['title' => '13:00 - 14:30', 'seat' => 18],
                    ['title' => '14:30 - 16:00', 'seat' => 18],
                    ['title' => '16:15 - 17:45', 'seat' => 18],
                ],
                'dates'    => [
                    'จันทร์ - พุธ'   => [
                        ['title' => '2025-08-04', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-08-06', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-08-11', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-08-13', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-18', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-20', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-25', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-01', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-09-03', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-09-08', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-09-10', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-15', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-17', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-22', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-24', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-10-01', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-10-06', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-10-08', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-10-13', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-10-15', 'location' => 'Training. Fl 8'],
                    ],
                    'อังคาร - พฤหัส' => [
                        ['title' => '2025-08-05', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-08-07', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-08-12', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-14', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-19', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-21', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-08-26', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-02', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-09-04', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-09-09', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-09-11', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-16', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-18', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-23', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-25', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-09-30', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-10-02', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-10-09', 'location' => 'Con. Fl.8'],
                        ['title' => '2025-10-14', 'location' => 'Training. Fl 8'],
                        ['title' => '2025-10-16', 'location' => 'Training. Fl 8'],
                    ],
                ],
            ];
            $this->createTeacher($teacher);
            $teacher = [
                'team'     => 'B',
                'name'     => 'Tomm',
                'sessions' => ['จันทร์ - พุธ', 'อังคาร - พฤหัส'],
                'times'    => [
                    ['title' => '16:45 - 17:45', 'seat' => 18],
                ],
                'dates'    => [
                    'จันทร์ - พุธ'   => [
                        ['title' => '2025-08-04', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-06', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-11', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-13', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-18', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-20', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-25', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-01', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-03', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-08', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-10', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-15', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-17', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-22', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-24', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-01', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-06', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-08', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-13', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-15', 'location' => 'Meeting room 2 Fl.8'],
                    ],
                    'อังคาร - พฤหัส' => [
                        ['title' => '2025-08-05', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-07', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-12', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-14', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-19', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-21', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-08-26', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-02', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-04', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-09', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-11', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-16', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-18', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-23', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-25', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-09-30', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-02', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-09', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-14', 'location' => 'Meeting room 2 Fl.8'],
                        ['title' => '2025-10-16', 'location' => 'Meeting room 2 Fl.8'],
                    ],
                ],
            ];
            $this->createTeacher($teacher);
            $teacher = [
                'team'     => 'C',
                'name'     => 'Roel',
                'sessions' => ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์'],
                'times'    => [
                    ['title' => '08:30 - 10:00', 'seat' => 25],
                    ['title' => '13:00 - 14:30', 'seat' => 25],
                    ['title' => '15:00 - 16:30', 'seat' => 25],
                ],
                'dates'    => [
                    'จันทร์' => [
                        ['title' => '2025-07-14', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-21', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-04', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-11', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-18', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-25', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-01', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-08', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-15', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-22', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                    ],
                    'อังคาร' => [
                        ['title' => '2025-07-15', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-22', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-29', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-05', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-19', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-26', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-02', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-09', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-16', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-23', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                    ],
                    'พุธ'    => [
                        ['title' => '2025-07-16', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-23', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-30', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-06', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-13', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-20', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-27', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-03', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-10', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-17', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                    ],
                    'พฤหัส'  => [
                        ['title' => '2025-07-17', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-24', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-31', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-07', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-14', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-21', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-28', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-04', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-11', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-18', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                    ],
                    'ศุกร์'  => [
                        ['title' => '2025-07-18', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-07-25', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-08', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-15', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-22', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-08-29', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-05', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-12', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-19', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                        ['title' => '2025-09-26', 'location' => 'ห้องจัดซื้อเดิม ชั้น 6 อาคาร C'],
                    ],
                ],
            ];
            $this->createTeacher($teacher);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'not avaiable']);
    }

    public function createTeacher($teacher)
    {
        $team = TrainingTeam::where('name', $teacher['team'])->first();

        $new_teacher          = new TrainingTeacher;
        $new_teacher->team_id = $team->id;
        $new_teacher->name    = $teacher['name'];
        $new_teacher->save();

        foreach ($teacher['sessions'] as $session) {
            $dateData = $teacher['dates'][$session];

            $new_session             = new TrainingSession;
            $new_session->teacher_id = $new_teacher->id;
            $new_session->name       = $session;
            $new_session->save();

            foreach ($teacher['times'] as $time) {
                $new_time                 = new TrainingTime;
                $new_time->session_id     = $new_session->id;
                $new_time->name           = $time['title'];
                $new_time->max_seat       = $time['seat'];
                $new_time->available_seat = $time['seat'];
                $new_time->save();
                foreach ($dateData as $date) {
                    $new_date           = new TrainingDate;
                    $new_date->time_id  = $new_time->id;
                    $new_date->name     = $date['title'];
                    $new_date->location = $date['location'];
                    $new_date->save();
                }
            }
        }

    }

    public function dateFull($date)
    {
        // Thai day and month names
        $days   = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        $months = [
            1  => 'มกราคม',
            2  => 'กุมภาพันธ์',
            3  => 'มีนาคม',
            4  => 'เมษายน',
            5  => 'พฤษภาคม',
            6  => 'มิถุนายน',
            7  => 'กรกฎาคม',
            8  => 'สิงหาคม',
            9  => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];
        $dt        = date_create($date);
        $dayOfWeek = $days[(int) date_format($dt, 'w')];
        $day       = (int) date_format($dt, 'j');
        $month     = $months[(int) date_format($dt, 'n')];
        $year      = (int) date_format($dt, 'Y');

        return "$dayOfWeek $day $month $year";
    }

    public function index()
    {
        $user  = TrainingUser::where('user_id', auth()->user()->userid)->first();
        $team  = null;
        $dates = $this->getUserDates($user, true);
        if ($user) {
            $team = TrainingTeam::where('name', $user->team)->first();
        }
        return view('training.index', compact('team', 'user', 'dates'));
    }

    public function history()
    {
        $user  = TrainingUser::where('user_id', auth()->user()->userid)->first();
        $dates = $this->getUserDates($user, false);
        return view('training.history', compact('user', 'dates'));
    }

    private function getUserDates($user, $futureOnly = false)
    {
        $dates = [];
        if ($user && $user->time !== null) {
            foreach ($user->time->dates as $date) {
                if (! $futureOnly || $date->name >= date('Y-m-d')) {
                    $dates[$date->name] = [
                        'id'        => $date->id,
                        'date'      => $date->name,
                        'title'     => $this->dateFull($date->name),
                        'time'      => $date->time->name,
                        'location'  => $date->location,
                        'user'      => false,
                        'admin'     => false,
                        'checkable' => ($date->name == date('Y-m-d')) ? true : false,
                        'checked'   => false,
                    ];
                }
            }
            foreach ($user->attends as $attend) {
                if ((! $futureOnly || $attend->name >= date('Y-m-d')) && array_key_exists($attend->name, $dates)) {
                    $dates[$attend->name]['user']       = $attend->user;
                    $dates[$attend->name]['user_date']  = ($attend->user_date == null) ? '' : date('d/M/Y H:i', strtotime($attend->user_date));
                    $dates[$attend->name]['admin']      = $attend->admin;
                    $dates[$attend->name]['admin_date'] = ($attend->admin_date == null) ? '' : date('d/M/Y H:i', strtotime($attend->admin_date));
                    $dates[$attend->name]['checkable']  = false;
                    $dates[$attend->name]['checked']    = true;
                }
            }
        }
        return $dates;
    }

    public function indexGetSessions(Request $request)
    {
        $getsessions = TrainingSession::where('teacher_id', $request->teacher_id)->get();

        return response()->json(['sessions' => $getsessions], 200);
    }

    public function indexgetTimes(Request $request)
    {
        $gettimes = TrainingTime::where('session_id', $request->session_id)->get();

        return response()->json(['times' => $gettimes], 200);
    }

    public function indexRegister(Request $request)
    {
        Log::channel('training_admin')->info("User registration", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);

        $user          = TrainingUser::where('user_id', auth()->user()->userid)->first();
        $user->time_id = $request->time_id;
        $time          = TrainingTime::where('id', $request->time_id)->first();
        if ($time->max_seat == 0) {

            return response()->json(['status' => 'error', 'message' => 'รอบนี้ไม่มีที่นั่งที่สามารถลงทะเบียนได้']);
        }
        $time->available_seat = $time->available_seat - 1;
        $time->save();

        $user->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function changeRegistration(Request $request)
    {
        Log::channel('training_admin')->info("User delete registration", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);

        $user = TrainingUser::where('user_id', auth()->user()->userid)->first();

        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้']);
        }

        if (! $user->time_id) {
            return response()->json(['status' => 'error', 'message' => 'คุณยังไม่ได้ลงทะเบียนรอบใด']);
        }

        // Get the current time slot and increase available seats
        $time = TrainingTime::find($user->time_id);
        if ($time) {
            $time->available_seat = $time->available_seat + 1;
            $time->save();
        }

        // Clear the user's registration
        $user->time_id = null;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'ยกเลิกการลงทะเบียนสำเร็จ']);
    }

    public function indexCheckIn(Request $request)
    {
        $date = TrainingDate::where('id', $request->date_id)->first();

        $user            = new TrainingAttend;
        $user->user_id   = auth()->user()->userid;
        $user->date_id   = $date->id;
        $user->name      = $date->name;
        $user->user      = true;
        $user->user_date = date('Y-m-d H:i:s');
        $user->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function adminIndex()
    {
        return view('training.admin.index');
    }

    // Admin: List all users
    public function adminUserIndex(Request $request)
    {
        $query = TrainingUser::query();

        // Search by User ID
        if ($request->filled('search')) {
            $query->where('user_id', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderby('team', 'ASC')->orderby('user_id', 'ASC')->paginate(100);

        return view('training.admin.users.index', compact('users'));
    }

    // Admin: Import users from Excel
    public function adminUserImport(Request $request)
    {
        Log::channel('training_admin')->info('Imported users from Excel', [
            'user'     => auth()->user()->userid ?? null,
            'filename' => $request->file('import_file')->getClientOriginalName() ?? null,
            'request'  => $request->all(),
        ]);
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new TrainingTeamImport, $request->import_file);

        return response()->json(['status' => 'success', 'message' => 'Import completed (stub).']);
    }

    // Admin: List all teams
    public function adminTeamIndex()
    {
        $teams = TrainingTeam::all();

        return view('training.admin.teams.index', compact('teams'));
    }

    // Admin: Show create form
    public function adminTeamCreate()
    {

        return view('training.admin.teams.create');
    }

    // Admin: Store new team
    public function adminTeamStore(Request $request)
    {
        Log::channel('training_admin')->info("Created team '{$request->name}'", [
            'user'    => auth()->user()->userid ?? null,
            'status'  => $request->status,
            'request' => $request->all(),
        ]);
        $request->validate([
            'name'   => 'required|string|max:255|unique:training_teams,name',
            'status' => 'required|in:active,inactive',
        ]);

        $team         = new TrainingTeam();
        $team->name   = $request->name;
        $team->status = $request->status;
        $team->save();

        return redirect()->route('training.admin.teams.index')->with('success', 'Group created successfully');
    }

    // Admin: Show edit form
    public function adminTeamEdit($id)
    {
        $team = TrainingTeam::findOrFail($id);

        return view('training.admin.teams.edit', compact('team'));
    }

    // Admin: Update team
    public function adminTeamUpdate(Request $request, $id)
    {
        Log::channel('training_admin')->info("Updated team ID: $id to '{$request->name}'", [
            'user'    => auth()->user()->userid ?? null,
            'status'  => $request->status,
            'request' => $request->all(),
        ]);
        $request->validate([
            'name'   => 'required|string|max:255|unique:training_teams,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $team         = TrainingTeam::findOrFail($id);
        $team->name   = $request->name;
        $team->status = $request->status;
        $team->save();

        return redirect()->route('training.admin.teams.index')->with('success', 'Group updated successfully');
    }

    // Admin: Delete team
    public function adminTeamDelete($id)
    {
        Log::channel('training_admin')->info("Deleted team ID: $id", [
            'user' => auth()->user()->userid ?? null,
            'id'   => $id,
        ]);
        $team     = TrainingTeam::findOrFail($id);
        $teachers = TrainingTeacher::where('team_id', $team->id)->get();
        foreach ($teachers as $teacher) {
            $sessions = TrainingSession::where('teacher_id', $teacher->id)->get();
            foreach ($sessions as $session) {
                $times = TrainingTime::where('session_id', $session->id)->get();
                foreach ($times as $time) {
                    $users = TrainingUser::where('time_id', $time->id)->get();
                    foreach ($users as $user) {
                        $user->time_id = null;
                        $user->save();
                    }
                    $dates = TrainingDate::where('time_id', $time->id)->get();
                    foreach ($dates as $date) {
                        $date->delete();
                    }
                    $time->delete();
                }
                $session->delete();
            }
            $teacher->delete();
        }
        $team->delete();

        return redirect()->route('training.admin.teams.index')->with('success', 'Group deleted successfully');
    }

    // Admin: View teachers in a team
    public function adminTeamTeachers($id)
    {
        $team     = TrainingTeam::findOrFail($id);
        $teachers = TrainingTeacher::where('team_id', $id)->get();

        return view('training.admin.teams.teachers', compact('team', 'teachers'));
    }

    // Admin: Create teacher form
    public function adminTeacherCreate(Request $request)
    {
        $team_id = $request->get('team_id');
        $teams   = TrainingTeam::all();
        $team    = TrainingTeam::findOrFail($team_id);

        return view('training.admin.teachers.create', compact('teams', 'team'));
    }

    // Admin: Store new teacher
    public function adminTeacherStore(Request $request)
    {
        Log::channel('training_admin')->info("Created teacher '{$request->name}' in team ID: {$request->team_id}", [
            'user'    => auth()->user()->userid ?? null,
            'status'  => $request->status,
            'request' => $request->all(),
        ]);
        $request->validate([
            'name'    => 'required|string|max:255',
            'team_id' => 'required|exists:training_teams,id',
            'status'  => 'required|in:active,inactive',
        ]);

        TrainingTeacher::create([
            'name'    => $request->name,
            'team_id' => $request->team_id,
            'status'  => $request->status,
        ]);

        return redirect()->route('training.admin.teams.teachers', $request->team_id)
            ->with('success', 'Teacher created successfully');
    }

    // Admin: Edit teacher form
    public function adminTeacherEdit($id)
    {
        $teacher = TrainingTeacher::findOrFail($id);
        $teams   = TrainingTeam::all();

        return view('training.admin.teachers.edit', compact('teacher', 'teams'));
    }

    // Admin: Update teacher
    public function adminTeacherUpdate(Request $request, $id)
    {
        Log::channel('training_admin')->info("Updated teacher ID: $id to '{$request->name}'", [
            'user'    => auth()->user()->userid ?? null,
            'team_id' => $request->team_id,
            'status'  => $request->status,
            'request' => $request->all(),
        ]);
        $teacher = TrainingTeacher::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'team_id' => 'required|exists:training_teams,id',
            'status'  => 'required|in:active,inactive',
        ]);

        $teacher->update([
            'name'    => $request->name,
            'team_id' => $request->team_id,
            'status'  => $request->status,
        ]);

        return redirect()->route('training.admin.teams.teachers', $teacher->team_id)
            ->with('success', 'Teacher updated successfully');
    }

    // Admin: Delete teacher
    public function adminTeacherDelete($id)
    {
        Log::channel('training_admin')->info("Deleted teacher ID: $id", [
            'user' => auth()->user()->userid ?? null,
            'id'   => $id,
        ]);
        $teacher  = TrainingTeacher::findOrFail($id);
        $team_id  = $teacher->team_id;
        $sessions = TrainingSession::where('teacher_id', $teacher->id)->get();
        foreach ($sessions as $session) {
            $times = TrainingTime::where('session_id', $session->id)->get();
            foreach ($times as $time) {
                $users = TrainingUser::where('time_id', $time->id)->get();
                foreach ($users as $user) {
                    $user->time_id = null;
                    $user->save();
                }
                $dates = TrainingDate::where('time_id', $time->id)->get();
                foreach ($dates as $date) {
                    $date->delete();
                }
                $time->delete();
            }
            $session->delete();
        }
        $teacher->delete();

        return redirect()->route('training.admin.teams.teachers', $team_id)
            ->with('success', 'Teacher deleted successfully');
    }

    // Admin: View teacher sessions
    public function adminTeacherSessions($id)
    {
        $teacher  = TrainingTeacher::with('sessions')->findOrFail($id);
        $sessions = $teacher->sessions;

        return view('training.admin.teachers.sessions', compact('teacher', 'sessions'));
    }

    // Admin: Create session form
    public function adminSessionCreate(Request $request)
    {
        $teacher_id = $request->get('teacher_id');
        $teacher    = TrainingTeacher::findOrFail($teacher_id);

        return view('training.admin.sessions.create', compact('teacher'));
    }

    // Admin: Store new session
    public function adminSessionStore(Request $request)
    {
        Log::channel('training_admin')->info("Created session '{$request->name}' for teacher ID: {$request->teacher_id}", [
            'user'    => auth()->user()->userid ?? null,
            'status'  => $request->status,
            'request' => $request->all(),
        ]);
        $request->validate([
            'name'       => 'required|string|max:255',
            'teacher_id' => 'required|exists:training_teachers,id',
            'status'     => 'required|in:active,inactive',
        ]);

        TrainingSession::create([
            'name'       => $request->name,
            'teacher_id' => $request->teacher_id,
            'status'     => $request->status,
        ]);

        return redirect()->route('training.admin.teachers.sessions', $request->teacher_id)
            ->with('success', 'Session created successfully');
    }

    // Admin: Edit session form
    public function adminSessionEdit($id)
    {
        $session = TrainingSession::findOrFail($id);

        return view('training.admin.sessions.edit', compact('session'));
    }

    // Admin: Update session
    public function adminSessionUpdate(Request $request, $id)
    {
        Log::channel('training_admin')->info("Updated session ID: $id to '{$request->name}'", [
            'user'       => auth()->user()->userid ?? null,
            'teacher_id' => $request->teacher_id,
            'status'     => $request->status,
            'request'    => $request->all(),
        ]);
        $session = TrainingSession::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'teacher_id' => 'required|exists:training_teachers,id',
            'status'     => 'required|in:active,inactive',
        ]);

        $session->update([
            'name'       => $request->name,
            'teacher_id' => $request->teacher_id,
            'status'     => $request->status,
        ]);

        return redirect()->route('training.admin.teachers.sessions', $request->teacher_id)
            ->with('success', 'Session updated successfully');
    }

    // Admin: Delete session
    public function adminSessionDelete($id)
    {
        Log::channel('training_admin')->info("Deleted session ID: $id", [
            'user' => auth()->user()->userid ?? null,
            'id'   => $id,
        ]);
        $session = TrainingSession::findOrFail($id);
        $times   = TrainingTime::where('session_id', $session->id)->get();
        foreach ($times as $time) {
            $users = TrainingUser::where('time_id', $time->id)->get();
            foreach ($users as $user) {
                $user->time_id = null;
                $user->save();
            }
            $dates = TrainingDate::where('time_id', $time->id)->get();
            foreach ($dates as $date) {
                $date->delete();
            }
            $time->delete();
        }
        $session->delete();

        return redirect()->route('training.admin.teachers.sessions', $session->teacher_id)
            ->with('success', 'Session deleted successfully');
    }

    // Admin: Create time form
    public function adminTimeCreate(Request $request)
    {
        $session_id = $request->get('session_id');
        $session    = TrainingSession::findOrFail($session_id);

        return view('training.admin.times.create', compact('session'));

    }

    // Admin: Store new time
    public function adminTimeStore(Request $request)
    {
        Log::channel('training_admin')->info("Created time '{$request->name}' for session ID: {$request->session_id}", [
            'user'     => auth()->user()->userid ?? null,
            'max_seat' => $request->max_seat,
            'status'   => $request->status,
            'request'  => $request->all(),
        ]);
        $request->validate([
            'session_id' => 'required|exists:training_sessions,id',
            'name'       => 'required|string|max:255',
            'max_seat'   => 'required|integer|min:1',
            'status'     => 'required|in:active,inactive',
        ]);

        $time                 = new TrainingTime();
        $time->name           = $request->name;
        $time->max_seat       = $request->max_seat;
        $time->available_seat = $request->max_seat;
        $time->status         = $request->status;
        $time->session_id     = $request->session_id;
        $time->save();

        $session = TrainingSession::find($request->session_id);
        return redirect()->route('training.admin.teachers.sessions', $session->teacher_id)
            ->with('success', 'Time created successfully');
    }

    // Admin: Edit time form
    public function adminTimeEdit($id)
    {
        Log::channel('training_admin')->info("Opened edit form for time ID: $id", [
            'user' => auth()->user()->userid ?? null,
            'id'   => $id,
        ]);
        $time = TrainingTime::findOrFail($id);

        return view('training.admin.times.edit', compact('time'));
    }

    // Admin: Update time
    public function adminTimeUpdate(Request $request, $id)
    {
        Log::channel('training_admin')->info("Updated time ID: $id to '{$request->name}'", [
            'user'           => auth()->user()->userid ?? null,
            'available_seat' => $request->available_seat,
            'request'        => $request->all(),
        ]);
        $time     = TrainingTime::findOrFail($id);
        $max_seat = $time->max_seat;
        $request->validate([
            'name'           => 'required|string|max:255',
            'available_seat' => 'required|integer|min:0',
        ]);

        if ($request->available_seat > $time->available_seat) {
            $diff = $request->available_seat - $time->available_seat;
            $max_seat += $diff;
        }

        $time->update([
            'name'           => $request->name,
            'max_seat'       => $max_seat,
            'available_seat' => $request->available_seat,
        ]);
        return redirect()->route('training.admin.teachers.sessions', $time->session->teacher->id)
            ->with('success', 'Time updated successfully');
    }

    // Admin: Delete time
    public function adminTimeDelete($id)
    {
        Log::channel('training_admin')->info("Deleted time ID: $id", [
            'user' => auth()->user()->userid ?? null,
            'id'   => $id,
        ]);
        $time       = TrainingTime::findOrFail($id);
        $teacher_id = $time->session->teacher_id;
        $users      = TrainingUser::where('time_id', $time->id)->get();
        foreach ($users as $user) {
            $user->time_id = null;
            $user->save();
        }
        $dates = TrainingDate::where('time_id', $time->id)->get();
        foreach ($dates as $date) {
            $date->delete();
        }
        $time->delete();

        return redirect()->route('training.admin.teachers.sessions', $teacher_id)
            ->with('success', 'Time deleted successfully');
    }

    // Admin: Dates index for a time
    public function adminDatesIndex($time_id)
    {
        $time  = TrainingTime::with('dates')->findOrFail($time_id);
        $dates = $time->dates;
        foreach ($dates as $date) {
            $date->fulldate = $this->dateFull($date->name);
        }

        return view('training.admin.dates.index', compact('time', 'dates'));
    }

    // Admin: Create date form
    public function adminDateCreate($time_id)
    {
        $time = TrainingTime::findOrFail($time_id);

        return view('training.admin.dates.create', compact('time'));
    }

    // Admin: Store new date
    public function adminDateStore(Request $request)
    {
        Log::channel('training_admin')->info("Created dates for time ID: {$request->time_id}", [
            'user'      => auth()->user()->userid ?? null,
            'dates'     => $request->dates,
            'locations' => $request->locations,
            'request'   => $request->all(),
        ]);
        $request->validate([
            'time_id'   => 'required|exists:training_times,id',
            'dates'     => 'required|array',
            'locations' => 'required|array',
        ]);

        $skippedDates = [];
        foreach ($request->dates as $key => $dateName) {
            $existingDate = TrainingDate::where('time_id', $request->time_id)
                ->where('name', $dateName)
                ->first();
            if ($existingDate === null) {
                $newDate           = new TrainingDate();
                $newDate->time_id  = $request->time_id;
                $newDate->name     = $dateName;
                $newDate->location = $request->locations[$key];
                $newDate->save();
            } else {
                $skippedDates[] = $dateName;
            }
        }

        $response = 'Date(s) created successfully';
        if (! empty($skippedDates)) {
            $response .= ' (Some dates already existed and were not created: ' . implode(', ', $skippedDates) . ')';
        }

        return redirect()->route('training.admin.dates.index', $request->time_id)
            ->with('success', $response);
    }

    // Admin: Edit date form
    public function adminDateEdit($id)
    {
        $date = TrainingDate::findOrFail($id);
        $time = TrainingTime::findOrFail($date->time_id);

        return view('training.admin.dates.edit', compact('date', 'time'));
    }

    // Admin: Update date
    public function adminDateUpdate(Request $request, $id)
    {
        Log::channel('training_admin')->info("Updated date ID: $id to '{$request->name}'", [
            'user'     => auth()->user()->userid ?? null,
            'location' => $request->location,
            'status'   => $request->status,
            'request'  => $request->all(),
        ]);
        $date = TrainingDate::findOrFail($id);
        $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:active,inactive',
        ]);
        $date->update([
            'name'     => $request->name,
            'location' => $request->location,
            'status'   => $request->status,
        ]);

        return redirect()->route('training.admin.dates.index', $date->time_id)
            ->with('success', 'Date updated successfully');
    }

    // Admin: Delete date
    public function adminDateDelete($id)
    {
        Log::channel('training_admin')->info("Deleted date ID: $id", [
            'user' => auth()->user()->userid ?? null,
            'id'   => $id,
        ]);
        $date    = TrainingDate::findOrFail($id);
        $time_id = $date->time_id;
        $date->delete();

        return redirect()->route('training.admin.dates.index', $time_id)
            ->with('success', 'Date deleted successfully');
    }

    // Admin: Create user form
    public function adminUserCreate()
    {
        $teams = TrainingTeam::where('status', 'active')->get();

        return view('training.admin.users.create', compact('teams'));
    }

    // Admin: Store new user
    public function adminUserStore(Request $request)
    {
        Log::channel('training_admin')->info("Created user '{$request->user_id}' in team '{$request->team}'", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);
        $request->validate([
            'user_id' => 'required|string|max:255',
            'team'    => 'required|string|max:255',
        ]);

        $user = TrainingUser::where('user_id', $request->user_id)
            ->first();

        if ($user == null) {
            $user          = new TrainingUser;
            $user->user_id = $request->user_id;
        }
        $user->team = $request->team;
        $user->save();

        return redirect()->route('training.admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function adminUserDelete(Request $request)
    {
        $user = TrainingUser::find($request->id);
        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        try {
            $user->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete user.',
            ], 500);
        }
    }

    // Admin: Approve user
    public function adminApprove(Request $request)
    {
        $filterDate  = $request->input('name', date('Y-m-d'));
        $filterAdmin = $request->input('admin', 'all');
        $query       = TrainingAttend::with(['date.time.session.teacher.team'])->where('name', $filterDate);

        if ($filterAdmin === 'true') {
            $query->where('admin', true);
        } elseif ($filterAdmin === 'false') {
            $query->where(function ($q) {
                $q->whereNull('admin')->orWhere('admin', false);
            });
        }

        $attendances = $query->get();
        return view('training.admin.approve.index', compact('attendances', 'filterDate', 'filterAdmin'));
    }

    public function adminApproveUser(Request $request)
    {
        Log::channel('training_admin')->info("Approved attendance for attend ID: {$request->id}", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);
        $request->validate([
            'id' => 'required|exists:training_attends,id',
        ]);
        $attend             = TrainingAttend::findOrFail($request->id);
        $attend->admin      = true;
        $attend->admin_date = now();
        $attend->save();
        return response()->json(['status' => 'success', 'message' => 'Approved', 'id' => $attend->id]);
    }

    public function adminApproveUsers(Request $request)
    {
        Log::channel('training_admin')->info("Bulk approved attendances for date: {$request->input('name', date('Y-m-d'))}", [
            'user'         => auth()->user()->userid ?? null,
            'admin_filter' => $request->input('admin', 'all'),
            'request'      => $request->all(),
        ]);

        $query = TrainingAttend::whereIn('id', $request->ids);

        $count = $query->update(['admin' => true, 'admin_date' => now()]);

        return response()->json(['status' => 'success', 'updated' => $count]);
    }

    public function adminApproveUsersTeacher(Request $request)
    {
        Log::channel('training_admin')->info("Bulk approved attendances for date: {$request->input('name', date('Y-m-d'))}", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);
        $query = TrainingAttend::whereIn('id', json_decode($request->ids));
        $count = $query->update(['admin' => true, 'admin_date' => now()]);

        return response()->json(['status' => 'success', 'updated' => $count]);
    }

    public function teacherApproveAll(Request $request)
    {
        Log::channel('training_admin')->info("Teacher bulk approved all pending attendances for today", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);

        $query = TrainingAttend::where('name', date('Y-m-d'))
            ->where(function ($q) {
                $q->whereNull('admin')->orWhere('admin', false);
            });
        $count = $query->update(['admin' => true, 'admin_date' => now()]);

        return response()->json(['status' => 'success', 'updated' => $count]);
    }

    public function adminRegisterIndex(Request $request)
    {
        $teams    = TrainingTeam::where('status', 'active')->get();
        $teachers = TrainingTeacher::where('status', 'active')->get();
        $sessions = TrainingSession::where('status', 'active')->get();
        $times    = TrainingTime::where('status', 'active')->get();

        $hasFilter = $request->filled('team_id') || $request->filled('teacher_id') || $request->filled('session_id') || $request->filled('time_id') || $request->filled('user_id');

        if ($hasFilter) {
            $users = TrainingUser::with(['time.session.teacher']);

            if ($request->filled('team_id')) {
                $users->whereHas('time.session.teacher.team', function ($q) use ($request) {
                    $q->where('id', $request->team_id);
                });
            }
            if ($request->filled('teacher_id')) {
                $users->whereHas('time.session.teacher', function ($q) use ($request) {
                    $q->where('id', $request->teacher_id);
                });
            }
            if ($request->filled('session_id')) {
                $users->whereHas('time.session', function ($q) use ($request) {
                    $q->where('id', $request->session_id);
                });
            }
            if ($request->filled('time_id')) {
                $users->whereHas('time', function ($q) use ($request) {
                    $q->where('id', $request->time_id);
                });
            }
            if ($request->filled('user_id')) {
                $users->where('user_id', $request->user_id);
            }

            $users = $users->whereNotNull('time_id')->get();
        } else {
            $users = collect();
        }

        return view('training.admin.register.index', compact('teams', 'teachers', 'sessions', 'times', 'users'));
    }

    public function adminRegisterStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:training_users,user_id',
            'time_id' => 'required|exists:training_times,id',
        ]);

        $user_id = $request->user_id;
        $time_id = $request->time_id;
        $user    = TrainingUser::where('user_id', $user_id)->first();
        if ($user) {

            $old_time_id = $user->time_id;
            if ($old_time_id !== null) {
                $old_time = TrainingTime::find($old_time_id);
                if ($old_time) {
                    $old_time->available_seat = $old_time->available_seat + 1;
                    $old_time->save();
                }
            }

            $time = TrainingTime::find($time_id);
            if ($time->max_seat == 0) {
                $time->max_seat = $time->max_seat + 1;
            }
            $time->save();

            $user->time_id = $time_id;
            $user->save();
        }
        return response()->json(['status' => 'success', 'message' => 'User registered successfully']);
    }
    public function adminUnregisterUser(Request $request)
    {
        Log::channel('training_admin')->info("Unregistered user '{$request->user_id}' from training", [
            'user'    => auth()->user()->userid ?? null,
            'request' => $request->all(),
        ]);
        $request->validate([
            'user_id' => 'required|exists:training_users,user_id',
        ]);
        $user = TrainingUser::where('user_id', $request->user_id)->first();
        if ($user && $user->time_id) {
            $time = TrainingTime::find($user->time_id);
            if ($time) {
                $time->available_seat = $time->available_seat + 1;
                $time->save();
            }
            $user->time_id = null;
            $user->save();
        }
        return response()->json(['status' => 'success', 'message' => 'User unregistered successfully']);
    }

    public function adminExportIndex()
    {
        $dates      = TrainingDate::groupBy('name')->select('name')->get();
        $arrayDates = [];
        foreach ($dates as $date) {
            $arrayDates[] = $date->name;
        }

        return view('training.admin.exports.index', compact('arrayDates'));
    }

    public function adminExportAttends(Request $request)
    {
        $dateStart = $request->dateStart;
        $dateEnd   = $request->dateEnd;
        $date      = $dateStart . '-' . $dateEnd;
        $dates     = TrainingDate::whereDate('name', '>=', $dateStart)->whereDate('name', '<=', $dateEnd)->get();
        if ($dates->isEmpty() && $dates->time[0]->isEmpty()) {

            return response()->json(['message' => 'ไม่พบวันที่ที่ต้องการ'], 404);
        }

        return Excel::download(new TrainingAttendExport($request), 'บันทึกการเข้าเรียน_' . $date . '.xlsx');
    }

    public function adminExportHospitals(Request $request)
    {
        $date  = $request->date;
        $dates = TrainingDate::where('name', $date)->get();
        if ($dates->isEmpty() && $dates->time[0]->isEmpty()) {

            return response()->json(['message' => 'ไม่พบวันที่ที่ต้องการ'], 404);
        }

        return Excel::download(new TrainingHospitalExport($date), 'ใบบันทึกฝึกอบรมส่วนกลางโรงพยาบาล_' . $date . '.xlsx');
    }

    public function adminExportOnebooks(Request $request)
    {
        $date    = date('Y') . '-' . $request->month;
        $attends = TrainingAttend::where('user', true)->where('admin', true)->where('name', 'like', $date . '%')->get();
        if ($attends->isEmpty()) {

            return response()->json(['message' => 'ไม่พบวันที่ที่ต้องการ'], 404);
        }

        return Excel::download(new TrainingOnebookExport($date), 'Onebook' . $date . '.xlsx');
    }

}
