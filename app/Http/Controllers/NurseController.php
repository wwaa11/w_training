<?php
namespace App\Http\Controllers;

use App\Exports\NurseDateExport;
use App\Exports\NurseDateLectureExport;
use App\Exports\NurseLectureExport;
use App\Exports\NurseScoreExport;
use App\Exports\NurseUserExport;
use App\Models\NurseDate;
use App\Models\NurseLecture;
use App\Models\NurseProject;
use App\Models\NurseTime;
use App\Models\NurseTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class NurseController extends Controller
{
    public function Index()
    {
        $projects = NurseProject::where('active', true)
            ->whereDate('register_start', '<=', date('Y-m-d'))
            ->whereDate('register_end', '>=', date('Y-m-d'))
            ->orderBy('register_start', 'asc')
            ->get();

        $myTransaction = NurseTransaction::where('user_id', Auth::user()->userid)
            ->where('active', true)
            ->orderBy('date_time', 'asc')
            ->get();

        return view('nurse.index', compact('projects', 'myTransaction'));
    }
    public function History()
    {
        $transactions = NurseTransaction::where('user_id', Auth::user()->userid)
            ->where('active', true)
            ->orderBy('date_time', 'desc')
            ->get();

        $lectures = NurseLecture::where('user_id', Auth::user()->userid)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        $myscore = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->user_sign !== null && $transaction->admin_sign !== null) {
                $myscore += 1;
            }
        }

        $myscore += (count($lectures) * 5);

        return view('nurse.history', compact('transactions', 'lectures', 'myscore'));
    }
    public function ProjectIndex($project_id)
    {
        $project = NurseProject::find($project_id);
        if ($project !== null &&
            date("Y-m-d") >= date("Y-m-d", strtotime($project->register_start)) &&
            date("Y-m-d") <= date("Y-m-d", strtotime($project->register_end))) {

            return view('nurse.project', compact('project'));
        }

        return redirect()->back()->with('error', 'Project not found.');
    }
    public function TransactionCreate(Request $request)
    {
        $response = [
            'status'  => 'failed',
            'message' => 'รอบที่เลือกเต็มแล้ว!',
        ];

        $time = NurseTime::find($request->time_id);
        if ($time->max == 0) {

            $new                   = new NurseTransaction();
            $new->nurse_project_id = $request->project_id;
            $new->nurse_time_id    = $request->time_id;
            $new->date_time        = $time->dateData->date;
            $new->user_id          = Auth::user()->userid;
            $new->save();

            $response = [
                'status'  => 'success',
                'message' => 'ทำการลงทำเบียนสำเร็จ!',
            ];

        } else if ($time->max != 0 && $time->free > 0) {
            $time->free -= 1;
            $time->save();

            $new                   = new NurseTransaction();
            $new->nurse_project_id = $request->project_id;
            $new->nurse_time_id    = $request->time_id;
            $new->date_time        = $time->dateData->date;
            $new->user_id          = Auth::user()->userid;
            $new->save();

            $response = [
                'status'  => 'success',
                'message' => 'ทำการลงทำเบียนสำเร็จ!',
            ];
        }

        return response()->json($response, 200);
    }
    public function TransactionDelete(Request $request)
    {
        $transaction = NurseTransaction::where('nurse_project_id', $request->project_id)
            ->where('user_id', Auth::user()->userid)
            ->where('active', true)
            ->first();

        $transaction->active = false;
        $transaction->save();

        $time = NurseTime::where('id', $transaction->nurse_time_id)->first();
        if ($time->max != 0) {
            $time->free += 1;
            $time->save();
        }

        Log::channel('nurse_delete')->info('User : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete transaction id: ' . $transaction->id);

        $response = [
            'status'  => 'success',
            'message' => 'ทำการเปลี่ยนรอบการลงทะเบียนสำเร็จ!',
        ];

        return response()->json($response, 200);
    }
    public function TransactionSign(Request $req)
    {
        $transaction            = NurseTransaction::find($req->transaction_id);
        $transaction->user_sign = date('Y-m-d H:i');
        $transaction->save();

        $response = [
            'status'  => 'success',
            'message' => 'ลงชื่อสำเร็จ!',
        ];

        return response()->json($response, 200);
    }
    // Admin
    public function adminProjectIndex()
    {
        $projects = NurseProject::where('active', true)
            ->orderBy('register_start', 'asc')
            ->get();

        return view('nurse.admin.project_index', compact('projects'));
    }
    public function adminProjectCreate()
    {

        return view('nurse.admin.project_create');
    }
    public function FulldateTH($date)
    {
        $dateTime = strtotime($date);
        $day      = date('d', $dateTime);
        $month    = date('m', $dateTime);
        $year     = date('Y', $dateTime);

        switch ($month) {
            case '01':
                $fullmonth = 'มกราคม';
                break;
            case '02':
                $fullmonth = 'กุมภาพันธ์';
                break;
            case '03':
                $fullmonth = 'มีนาคม';
                break;
            case '04':
                $fullmonth = 'เมษายน';
                break;
            case '05':
                $fullmonth = 'พฤษภาคม';
                break;
            case '06':
                $fullmonth = 'มิถุนายน';
                break;
            case '07':
                $fullmonth = 'กรกฎาคม';
                break;
            case '08':
                $fullmonth = 'สิงหาคม';
                break;
            case '09':
                $fullmonth = 'กันยายน';
                break;
            case '10':
                $fullmonth = 'ตุลาคม';
                break;
            case '11':
                $fullmonth = 'พฤศจิกายน';
                break;
            case '12':
                $fullmonth = 'ธันวาคม';
                break;
        }
        $year = $year + 543;

        $birthDate = date_create($date);
        $nowDate   = date_create(date('Y-m-d'));
        $diff      = $birthDate->diff($nowDate);

        $data = $day . ' ' . $fullmonth . ' ' . $year;

        return $data;
    }
    public function adminProjectStore(Request $request)
    {
        $request->validate([
            'title'          => 'required',
            'location'       => 'required',
            'register_start' => 'required|date',
            'register_end'   => 'required|date|after_or_equal:register_start',
            'time'           => 'required|array',
            'date'           => 'required|array',
        ], [
            'time.required' => 'โปรดระบุรอบการลงทะเบียน',
        ]);

        $project = NurseProject::create([
            'title'          => $request->title,
            'detail'         => $request->detail,
            'location'       => $request->location,
            'register_start' => $request->register_start,
            'register_end'   => $request->register_end,
        ]);

        foreach ($request->date as $date) {
            $dateCreate = NurseDate::create([
                'nurse_project_id' => $project->id,
                'title'            => $date['title'],
                'date'             => $date['date'],
            ]);

            foreach ($request->time as $time) {
                NurseTime::create([
                    'nurse_date_id' => $dateCreate->id,
                    'title'         => $time['title'],
                    'time_start'    => $date['date'] . ' ' . $time['start'],
                    'time_end'      => $date['date'] . ' ' . $time['end'],
                    'max'           => $time['max'],
                    'free'          => $time['max'],
                ]);
            }
        }

        return redirect()->route('NurseAdminIndex')->with('success', 'Project created successfully.');
    }

    public function adminProjectManagement($project_id)
    {
        $project = NurseProject::find($project_id);
        if ($project !== null) {

            return view('nurse.admin.project_management', compact('project'));
        }

        return redirect()->back()->with('error', 'Project not found.');
    }
    public function adminProjectTransaction($project_id)
    {
        $project = NurseProject::find($project_id);

        return view('nurse.admin.project_transaction', compact('project'));
    }
    public function adminProjectCreateTransaction(Request $request)
    {
        $response = [
            'status'  => 'failed',
            'message' => 'รอบที่เลือกเต็มแล้ว!',
        ];

        $userid   = $request->user;
        $userData = User::where('userid', $userid)->first();
        if ($userData == null) {
            $responseAPI = Http::withHeaders(['token' => env('API_KEY')])
                ->post('http://172.20.1.12/dbstaff/api/getuser', [
                    'userid' => $userid,
                ])
                ->json();
            $response['message'] = 'ไม่พบรหัสพนักงานนี้';

            if ($responseAPI['status'] == 1) {
                $userData              = new User();
                $userData->userid      = $userid;
                $userData->password    = Hash::make($userid);
                $userData->name        = $responseAPI['user']['name'];
                $userData->position    = $responseAPI['user']['position'];
                $userData->department  = $responseAPI['user']['department'];
                $userData->division    = $responseAPI['user']['division'];
                $userData->hn          = $responseAPI['user']['HN'];
                $userData->last_update = date('Y-m-d H:i:s');
                $userData->save();
            }
        }

        $old_transaction = NurseTransaction::where('nurse_project_id', $request->project_id)
            ->where('user_id', $userid)
            ->where('active', true)
            ->first();

        $project = NurseProject::find($request->project_id);
        if (! $project->multiple && $old_transaction !== null) {

            $old_transaction->active = false;
            $old_transaction->save();

            $time = NurseTime::where('id', $old_transaction->nurse_time_id)->first();
            if ($time->max != 0) {
                $time->free += 1;
                $time->save();
            }

            Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete transaction id: ' . $old_transaction->id . ' for user: ' . $userData->userid . ' ' . $userData->name);
        }

        if ($userData !== null) {
            $NurseTime = NurseTime::find($request->time_id);
            if ($NurseTime->max == 0) {

                $new                   = new NurseTransaction();
                $new->nurse_project_id = $request->project_id;
                $new->nurse_time_id    = $request->time_id;
                $new->date_time        = $NurseTime->dateData->date;
                $new->user_id          = $request->user;
                $new->save();

                Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' add transaction id: ' . $new->id . ' for user: ' . $userData->userid . ' ' . $userData->name);

                $response = [
                    'status'  => 'success',
                    'message' => 'ทำการลงทำเบียนสำเร็จ!',
                    'time'    => $new->timeData->title,
                    'name'    => $userData->userid . ' ' . $userData->name,
                ];

            } else if ($NurseTime->max !== 0 && $NurseTime->free > 0) {

                $NurseTime->free -= 1;
                $NurseTime->save();

                $new                   = new NurseTransaction();
                $new->nurse_project_id = $request->project_id;
                $new->nurse_time_id    = $request->time_id;
                $new->date_time        = $NurseTime->dateData->date;
                $new->user_id          = $request->user;
                $new->save();

                Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' add transaction id: ' . $new->id . ' for user: ' . $userData->userid . ' ' . $userData->name);

                $response = [
                    'status'  => 'success',
                    'message' => 'ทำการลงทำเบียนสำเร็จ!',
                    'time'    => $new->timeData->title,
                    'name'    => $userData->userid . ' ' . $userData->name,
                ];
            }
        }

        return response()->json($response, 200);
    }
    public function adminProjectDeleteTransaction(Request $request)
    {
        $transaction         = NurseTransaction::find($request->transaction_id);
        $transaction->active = false;
        $transaction->save();

        $time = NurseTime::find($transaction->nurse_time_id);
        if ($time->max != 0) {
            $time->free += 1;
            $time->save();
        }

        Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete transaction id: ' . $transaction->id);

        $data = [
            'status'  => 'success',
            'message' => 'ลบข้อมูลการลงทะเบียนสำเร็จ',
        ];

        return response()->json($data, 200);
    }

    public function adminProjectApprove(Request $request)
    {
        $query = [
            'sign' => false,
            'time' => null,
        ];
        foreach ($request->query as $q => $value) {
            if ($q == 'project') {
                $project_id = $value;
            }
            if ($q == 'sign') {
                $sign          = $value;
                $query['sign'] = $value;
            }
            if ($q == 'time') {
                $query['time'] = $value;
            }
        }
        $optionTime = [];
        $project    = NurseProject::find($project_id);
        foreach ($project->dateData as $date) {
            foreach ($date->timeData as $time) {
                $timeText = date("H:i", strtotime($time->time_start));
                if (! in_array($timeText, $optionTime)) {
                    $optionTime[] = $timeText;
                }
            }
        }
        $query['option'] = $optionTime;

        $transactions = NurseTransaction::where('nurse_project_id', $project_id)
            ->where('active', true)
            ->whereDate('user_sign', date('Y-m-d'))
            ->where(function ($q) use ($query) {
                if ($query['sign'] == 'false') {
                    $q->whereNull('admin_sign');
                } else {
                    $q->whereNotNull('admin_sign');
                }
                if ($query['time'] !== 'all') {
                    $q->whereTime('date_time', $query['time']);
                }
            })
            ->get();

        return view('nurse.admin.project_approve', compact('project', 'transactions', 'query'));
    }
    public function adminProjectApproveUser(Request $request)
    {
        $transaction             = NurseTransaction::find($request->id);
        $transaction->admin_sign = date('Y-m-d H:i:s');
        $transaction->save();

        $data = [
            'status'  => 'success',
            'message' => 'Approve สำเร็จ',
        ];

        return response()->json($data, 200);
    }
    public function adminProjectApproveUserArray(Request $request)
    {
        $transactions = NurseTransaction::whereIn('id', $request->id)->get();
        foreach ($transactions as $transaction) {
            $transaction->admin_sign = date('Y-m-d H:i:s');
            $transaction->save();
        }
        $data = [
            'status'  => 'success',
            'message' => 'Approve สำเร็จ',
        ];

        return response()->json($data, 200);
    }

    public function adminAddLecture(Request $request)
    {
        $response = [
            'status'  => 'failed',
            'message' => 'Error!',
        ];
        $userid = $request->user;

        $lecture = NurseLecture::where('nurse_date_id', $request->date_id)->where('user_id', $userid)->where('active', true)->first();
        if ($lecture == null) {

            $userData = User::where('userid', $userid)->first();
            if ($userData == null) {
                $responseAPI = Http::withHeaders(['token' => env('API_KEY')])
                    ->post('http://172.20.1.12/dbstaff/api/getuser', [
                        'userid' => $userid,
                    ])
                    ->json();
                $response['message'] = 'ไม่พบรหัสพนักงานนี้';

                if ($responseAPI['status'] == 1) {
                    $userData              = new User();
                    $userData->userid      = $userid;
                    $userData->password    = Hash::make($userid);
                    $userData->name        = $responseAPI['user']['name'];
                    $userData->position    = $responseAPI['user']['position'];
                    $userData->department  = $responseAPI['user']['department'];
                    $userData->division    = $responseAPI['user']['division'];
                    $userData->hn          = $responseAPI['user']['HN'];
                    $userData->last_update = date('Y-m-d H:i:s');
                    $userData->save();
                }
            }

            if ($userData !== null) {
                $lecture                = new NurseLecture;
                $lecture->nurse_date_id = $request->date_id;
                $lecture->user_id       = $request->user;
                $lecture->save();

                $response = [
                    'status'  => 'success',
                    'message' => 'ทำการเพิ่มวิทยากรสำเร็จ!',
                ];
            }
        } else {
            $response = [
                'status'  => 'success',
                'message' => 'มีวิทยากรท่านนี้อยู่แล้ว!',
            ];
        }

        return response()->json($response, 200);
    }
    public function adminDeleteLecture(Request $request)
    {
        $lecture         = NurseLecture::where('id', $request->lecture_id)->where('active', true)->first();
        $lecture->active = false;
        $lecture->save();

        $response = [
            'status'  => 'success',
            'message' => 'ลบวิทยากรสำเร็จ!',
        ];

        return response()->json($response, 200);
    }

    public function ExcelUserExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        $name    = $project->title . '_ผู้ฝึกอบรม';

        return Excel::download(new NurseUserExport($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelLectureExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        $name    = $project->title . '_วิทยากร';

        return Excel::download(new NurseLectureExport($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDateUserExport($date_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $date = NurseDate::find($date_id);
        $name = $date->projectData->title . '_' . $date->title;

        return Excel::download(new NurseDateExport($date_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDateLectureExport($date_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $date = NurseDate::find($date_id);
        $name = $date->projectData->title . '_' . $date->title;

        return Excel::download(new NurseDateLectureExport($date_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }

    public function UserScore(Request $request)
    {
        foreach ($request->query as $parameter => $value) {
            if ($parameter == 'department') {
                $department = $value;
            }
        }

        $departmentArray = [
            'แผนกฉุกเฉิน',
            'แผนกหอผู้ป่วยวิกฤต ICU - CCU',
            'แผนกไตเทียม',
            'แผนกหอผู้ป่วยในชั้น 6',
            'แผนกหอผู้ป่วยในชั้น 7',
            'แผนกหอผู้ป่วยในชั้น 10',
            'แผนกหอผู้ป่วยในชั้น 14',
            'แผนกหอผู้ป่วยในชั้น 15',
            'แผนกหอผู้ป่วยในชั้น 15(ตึกB)',
            'แผนกหอผู้ป่วยในชั้น 16',
            'แผนกหอผู้ป่วยในชั้น 17(ตึกB)',
            'แผนกห้องส่องกล้องระบบทางเดินอาหาร',
            'แผนกศูนย์ทางเดินอาหารและตับ(ตึกB)',
            'แผนกห้องพักฟื้น',
            'แผนกห้องผ่าตัด',
            'แผนกห้องคลอด',
            'หน่วยบริการเปล',
            'แผนกจ่ายกลาง',
            'แผนกอายุรกรรม',
            'แผนกศัลยกรรม',
            'แผนกสถาบันหัวใจและหลอดเลือด',
            'แผนกสูตินรีเวช',
        ];

        $projects = NurseProject::where('active', true)
            ->orderBy('register_start', 'asc')
            ->get();

        $nurses = User::where('department', $department)
            ->orderBy('department', 'asc')
            ->orderBy('userid', 'asc')
            ->get();

        $transactions = NurseTransaction::where('active', true)
            ->whereNotNull('user_sign')
            ->whereNotNull('admin_sign')
            ->get();

        $lecture = NurseLecture::where('active', true)->get();

        $data = [];
        foreach ($nurses as $index => $nurse) {
            $data[$nurse->department][$nurse->userid] = [
                'user'     => $nurse->userid,
                'name'     => $nurse->name,
                'position' => $nurse->position,
                'lecture'  => null,
            ];
            $lectureCount = collect($lecture)->where('user_id', $nurse->userid)->count();
            $score        = null;
            if ($lectureCount > 0) {
                $data[$nurse->department][$nurse->userid]['lecture'] = $lectureCount * 5;
                $score                                               = $lectureCount * 5;
            }
            foreach ($projects as $project) {
                $data[$nurse->department][$nurse->userid][$project->title] = null;
                $countTransaction                                          = collect($transactions)->where('user_id', $nurse->userid)->where('nurse_project_id', $project->id)->count();
                if ($countTransaction > 0) {
                    $data[$nurse->department][$nurse->userid][$project->title] = $countTransaction;
                    $score += $countTransaction;
                }
            }
            $data[$nurse->department][$nurse->userid]['total'] = $score;
        }

        return view('nurse.admin.user_reports', compact('projects', 'data', 'departmentArray', 'department'));
    }
    public function UserScoreExport($department)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $name = 'nurseScore_' . $department;

        return Excel::download(new NurseScoreExport($department), $name . date('d-m-Y') . '.xlsx');
    }
}
