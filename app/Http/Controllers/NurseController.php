<?php
namespace App\Http\Controllers;

use App\Exports\NurseDateDBDExport;
use App\Exports\NurseDateExport;
use App\Exports\NurseDateLectureExport;
use App\Exports\NurseDBDExport;
use App\Exports\NurseLectureExport;
use App\Exports\NurseOnebookExport;
use App\Exports\NurseScoreExport;
use App\Exports\NurseType1Export;
use App\Exports\NurseType2Export;
use App\Exports\NurseType3Export;
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
        foreach ($lectures as $lecture) {
            $myscore += $lecture->score;
        }

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
            $new->date_time        = $time->time_start;
            $new->user_id          = Auth::user()->userid;
            $new->save();

            $response = [
                'status'  => 'success',
                'message' => 'ทำการลงทะเบียนสำเร็จ!',
            ];

        } else if ($time->max != 0 && count($time->transactionData) < $time->max) {
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
                'message' => 'ทำการลงทะเบียนสำเร็จ!',
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
            'message' => 'ยกเลิกการลงทะเบียนสำเร็จ!',
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
    public function adminProjectIndex(Request $request)
    {
        $search = $request->input('q');

        $query = NurseProject::query()
            ->where('active', true)
            ->orderBy('register_start', 'desc')
            ->select(['id', 'title', 'detail', 'register_start', 'register_end']);

        if (! empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $projects = $query->paginate(20)->withQueryString();

        return view('nurse.admin.project_index', compact('projects', 'search'));
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
    public function adminProjectDateBetween(Request $req)
    {
        $dates = [];
        $start = $req->start;
        $end   = $req->end;

        $startDate = new \DateTime($start);
        $endDate   = new \DateTime($end . ' +1 Days');

        $interval  = new \DateInterval('P1D'); // 1 day interval
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($dateRange as $date) {
            $dates[] = [
                'date'  => $date->format('Y-m-d'),
                'title' => $this->FulldateTH($date->format('Y-m-d')),
            ];
        }

        return response()->json(['status' => 'success', 'dates' => $dates]);
    }
    public function adminProjectStore(Request $request)
    {
        $request->validate([
            'title'             => 'required',
            'location'          => 'required',
            'register_start'    => 'required|date',
            'register_end'      => 'required|date|after_or_equal:register_start',
            'time'              => 'required|array',
            'date'              => 'required|array',
            'export_type'       => 'required|in:1,2,3',
            'multiple_register' => 'required|in:0,1',
        ], [
            'time.required' => 'โปรดระบุรอบการลงทะเบียน',
        ]);

        $project = NurseProject::create([
            'title'          => $request->title,
            'detail'         => $request->detail,
            'location'       => $request->location,
            'register_start' => $request->register_start,
            'register_end'   => $request->register_end,
            'export_type'    => $request->export_type,
            'multiple'       => $request->multiple_register,
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

        return redirect()->route('nurse.admin.index')->with('success', 'Project created successfully.');
    }

    public function adminProjectEdit($project_id)
    {
        $project = NurseProject::find($project_id);
        if ($project !== null) {
            return view('nurse.admin.project_edit', compact('project'));
        }
        return redirect()->back()->with('error', 'Project not found.');
    }

    public function adminProjectUpdate(Request $request, $project_id)
    {
        $request->validate([
            'title'             => 'required',
            'location'          => 'required',
            'register_start'    => 'required|date',
            'register_end'      => 'required|date|after_or_equal:register_start',
            'export_type'       => 'required|in:1,2,3',
            'multiple_register' => 'required|in:0,1',
        ]);

        $project = NurseProject::find($project_id);
        if ($project === null) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        $project->title          = $request->title;
        $project->detail         = $request->detail;
        $project->location       = $request->location;
        $project->register_start = $request->register_start;
        $project->register_end   = $request->register_end;
        $project->export_type    = $request->export_type;
        $project->multiple       = $request->multiple_register;
        $project->save();

        // Schedule update: preserve existing, add new, deactivate removed (with transactions)
        $requestedDates = $request->input('date', []);
        $requestedTimes = $request->input('time', []);

        if (! empty($requestedDates) || ! empty($requestedTimes)) {
            // Map requested dates by date string
            $dateReqMap = [];
            foreach ($requestedDates as $d) {
                if (! empty($d['date'])) {
                    $dateReqMap[$d['date']] = $d;
                }
            }

            // Existing active dates for the project
            $existingDates   = $project->dateData()->get();
            $existingDateMap = [];
            foreach ($existingDates as $dModel) {
                $existingDateMap[$dModel->date] = $dModel;
            }

            // Process existing dates
            foreach ($existingDates as $dModel) {
                $dateStr = $dModel->date;
                if (isset($dateReqMap[$dateStr])) {
                    // Keep and update date title
                    $dModel->title = $dateReqMap[$dateStr]['title'] ?? $dModel->title;
                    $dModel->save();

                    // Build requested time keys for this date (start|end)
                    $newTimeKeys = [];
                    foreach ($requestedTimes as $t) {
                        if (! empty($t['start']) && ! empty($t['end'])) {
                            $start                                                                      = $t['start'];
                            $end                                                                        = $t['end'];
                            $newTimeKeys[date('H:i', strtotime($start)) . date('H:i', strtotime($end))] = $t;
                        }
                    }

                    // Existing active times for the date
                    $existingTimes = $dModel->timeData()->get();
                    foreach ($existingTimes as $tModel) {
                        $key = date('H:i', strtotime($tModel->time_start)) . date('H:i', strtotime($tModel->time_end));
                        if (isset($newTimeKeys[$key])) {
                            // Update matching time (keep free seats untouched)
                            $tPayload      = $newTimeKeys[$key];
                            $tModel->title = $tPayload['title'] ?? $tModel->title;
                            $tModel->max   = isset($tPayload['max']) ? $tPayload['max'] : $tModel->max;
                            $tModel->save();
                            unset($newTimeKeys[$key]);
                        } else {
                            // Time removed -> deactivate and deactivate transactions too
                            $tModel->active = false;
                            $tModel->save();
                            NurseTransaction::where('nurse_time_id', $tModel->id)
                                ->where('active', true)
                                ->update(['active' => false]);
                        }
                    }

                    // Create any new times not present previously
                    foreach ($newTimeKeys as $key => $tPayload) {
                        $start = $dateStr . ' ' . $tPayload['start'];
                        $end   = $dateStr . ' ' . $tPayload['end'];
                        NurseTime::create([
                            'nurse_date_id' => $dModel->id,
                            'title'         => $tPayload['title'] ?? ($tPayload['start'] . ' - ' . $tPayload['end']),
                            'time_start'    => $start,
                            'time_end'      => $end,
                            'max'           => $tPayload['max'] ?? 0,
                            'free'          => $tPayload['max'] ?? 0,
                        ]);
                    }
                } else {
                    // Date removed -> deactivate date, its times, and related transactions
                    $dModel->active = false;
                    $dModel->save();

                    // Deactivate Lectures
                    $lectures   = NurseLecture::where('nurse_date_id', $dModel->id)->where('active', true)->get();
                    $lectureIds = $lectures->pluck('id')->all();
                    foreach ($lectures as $l) {
                        $l->active = false;
                        $l->save();
                    }

                    // Deactivate times
                    $times   = NurseTime::where('nurse_date_id', $dModel->id)->where('active', true)->get();
                    $timeIds = $times->pluck('id')->all();
                    foreach ($times as $t) {
                        $t->active = false;
                        $t->save();
                    }
                    if (! empty($timeIds)) {
                        NurseTransaction::whereIn('nurse_time_id', $timeIds)
                            ->where('active', true)
                            ->update(['active' => false]);
                    }
                }
            }

            // Create new dates (and their times) that don't exist yet
            foreach ($requestedDates as $d) {
                if (empty($d['date'])) {
                    continue;
                }

                $dateStr = $d['date'];
                if (! isset($existingDateMap[$dateStr])) {
                    $dateCreate = \App\Models\NurseDate::create([
                        'nurse_project_id' => $project->id,
                        'title'            => $d['title'] ?? $dateStr,
                        'date'             => $dateStr,
                    ]);

                    foreach ($requestedTimes as $t) {
                        if (empty($t['start']) || empty($t['end'])) {
                            continue;
                        }

                        \App\Models\NurseTime::create([
                            'nurse_date_id' => $dateCreate->id,
                            'title'         => $t['title'] ?? ($t['start'] . ' - ' . $t['end']),
                            'time_start'    => $dateStr . ' ' . $t['start'],
                            'time_end'      => $dateStr . ' ' . $t['end'],
                            'max'           => $t['max'] ?? 0,
                            'free'          => $t['max'] ?? 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('nurse.admin.project.management', $project->id)->with('success', 'Project updated successfully.');
    }

    public function adminProjectManagement($project_id)
    {
        $project = NurseProject::find($project_id);
        if ($project !== null) {
            if ($project->export_type == 1) {
                $project->export_type_name = 'ใบบันทึกฝึกอบรม ภาคปฐมนิเทศ'; // Default export type
            } else if ($project->export_type == 2) {
                $project->export_type_name = 'ใบบันทึกฝึกอบรม ส่วนกลางโรงพยาบาล';
            } else if ($project->export_type == 3) {
                $project->export_type_name = 'ใบบันทึกการฝึกอบรมภาคอิสระ';
            } else {
                $project->export_type_name = 'ไม่ระบุประเภทการส่งออก';
            }

            return view('nurse.admin.project_management', compact('project'));
        }

        return redirect()->back()->with('error', 'Project not found.');
    }
    public function adminProjectDelete(Request $request)
    {
        $project = NurseProject::find($request->project_id);
        if ($project !== null) {
            $project->active = false;
            $project->save();

            $project->transactionData()->update(['active' => false]);
            foreach ($project->dateData as $d) {
                $d->lecturesData()->update(['active' => false]);
            }

            Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete project id: ' . $project->id);

            return redirect()->route('nurse.admin.index')->with('success', 'ลบโครงการสำเร็จ');
        }

        return redirect()->back()->with('error', 'ไม่พบโครงการนี้');
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
                $new->date_time        = $NurseTime->time_start;
                $new->user_id          = $request->user;
                $new->save();

                Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' add transaction id: ' . $new->id . ' for user: ' . $userData->userid . ' ' . $userData->name);

                $response = [
                    'status'  => 'success',
                    'message' => 'ทำการลงทะเบียนสำเร็จ!',
                    'time'    => $new->timeData->title,
                    'name'    => $userData->userid . ' ' . $userData->name,
                ];

            } else if ($NurseTime->max !== 0 && count($NurseTime->transactionData) < $NurseTime->max) {
                $NurseTime->free -= 1;
                $NurseTime->save();

                $new                   = new NurseTransaction();
                $new->nurse_project_id = $request->project_id;
                $new->nurse_time_id    = $request->time_id;
                $new->date_time        = $NurseTime->time_start;
                $new->user_id          = $request->user;
                $new->save();

                Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' add transaction id: ' . $new->id . ' for user: ' . $userData->userid . ' ' . $userData->name);

                $response = [
                    'status'  => 'success',
                    'message' => 'ทำการลงทะเบียนสำเร็จ!',
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

    public function adminProjectUpdateTransaction(Request $request)
    {
        $transaction = NurseTransaction::find($request->transaction_id);
        if ($transaction === null) {
            return response()->json(['status' => 'failed', 'message' => 'ไม่พบข้อมูลการลงทะเบียน'], 404);
        }

        $userSign  = $request->user_sign;
        $adminSign = $request->admin_sign;

        $transaction->user_sign  = ! empty($userSign) ? date('Y-m-d H:i:s', strtotime($userSign)) : null;
        $transaction->admin_sign = ! empty($adminSign) ? date('Y-m-d H:i:s', strtotime($adminSign)) : null;
        $transaction->save();

        Log::channel('nurse_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' update transaction id: ' . $transaction->id . ' user_sign: ' . ($transaction->user_sign ?? 'null') . ' admin_sign: ' . ($transaction->admin_sign ?? 'null'));

        return response()->json(['status' => 'success', 'message' => 'บันทึกข้อมูลสำเร็จ'], 200);
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
    public function updateLecturerScore(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|integer|exists:nurse_lecturers,id',
            'score'      => 'required|numeric|min:0',
        ]);

        $lecture = NurseLecture::find($request->lecture_id);
        if (! $lecture) {
            return response()->json(['success' => false, 'message' => 'Lecture not found.'], 404);
        }

        $lecture->score = $request->score;
        $lecture->save();

        return response()->json(['success' => true, 'message' => 'Score updated successfully.']);
    }

    public function ExcelUserExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        $name    = $project->title . '_ผู้ฝึกอบรม';
        $name    = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseUserExport($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDateUserExport($date_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $date = NurseDate::find($date_id);
        $name = $date->projectData->title . '_' . $date->title;
        $name = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseDateExport($date_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelLectureExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        $name    = $project->title . '_วิทยากร';
        $name    = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseLectureExport($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDateLectureExport($date_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $date = NurseDate::find($date_id);
        $name = $date->projectData->title . '_' . $date->title;
        $name = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseDateLectureExport($date_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDateDBDExport($date_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $date = NurseDate::find($date_id);
        $date = NurseDate::find($date_id);
        $name = $date->projectData->title . '_' . $date->title . '_DBD';
        $name = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseDateDBDExport($date_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDBDExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        $name    = $project->title . '_DBD';
        $name    = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseDBDExport($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }
    public function ExcelTypeExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        switch ($project->export_type) {
            case 1:
                $name = $project->title . '_ใบบันทึกฝึกอบรม ภาคปฐมนิเทศ';
                $name = str_replace(['/', '\\'], '', $name);
                return Excel::download(new NurseType1Export($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
            case 2:
                $name = $project->title . '_ใบบันทึกฝึกอบรม  ส่วนกลางโรงพยาบาล';
                $name = str_replace(['/', '\\'], '', $name);
                return Excel::download(new NurseType2Export($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
            case 3:
                $name = $project->title . '_ใบบันทึกการฝึกอบรมภาคอิสระ';
                $name = str_replace(['/', '\\'], '', $name);
                return Excel::download(new NurseType3Export($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
        }

    }
    public function ExcelOneBookExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = NurseProject::find($project_id);
        $name    = $project->title . '_onebook';
        $name    = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseOnebookExport($project_id), $name . '_' . date('d-m-Y') . '.xlsx');
    }

    public function UserScore(Request $request)
    {
        $department = null;
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
            'แผนกการพยาบาลกลาง',
        ];

        // Active projects
        $projects = NurseProject::where('active', true)
            ->orderBy('register_start', 'asc')
            ->get(['id', 'title', 'register_start']);

        // Nurses in selected department
        $nurses = User::where('department', $department)
            ->orderBy('department', 'asc')
            ->orderBy('userid', 'asc')
            ->get(['userid', 'name', 'position', 'department']);

        $userIds = $nurses->pluck('userid')->filter()->values();

        // Aggregate lecture counts per user (only active)
        $lectureCounts = NurseLecture::where('active', true)
            ->whereIn('user_id', $userIds)
            ->select('user_id', \DB::raw('COUNT(*) as cnt'))
            ->groupBy('user_id')
            ->pluck('cnt', 'user_id');

        // Aggregate transaction counts per user per project (only signed and active)
        $txRows = NurseTransaction::where('active', true)
            ->whereNotNull('user_sign')
            ->whereNotNull('admin_sign')
            ->whereIn('user_id', $userIds)
            ->select('user_id', 'nurse_project_id', \DB::raw('COUNT(*) as cnt'))
            ->groupBy('user_id', 'nurse_project_id')
            ->get();

        $txMap = [];
        foreach ($txRows as $row) {
            $uid               = $row->user_id;
            $pid               = $row->nurse_project_id;
            $txMap[$uid][$pid] = (int) $row->cnt;
        }

        // Build data structure for the view
        $data = [];
        foreach ($nurses as $nurse) {
            $deptKey = $nurse->department;
            $uid     = $nurse->userid;
            $score   = 0;

            $data[$deptKey][$uid] = [
                'user'     => $uid,
                'name'     => $nurse->name,
                'position' => $nurse->position,
                'lecture'  => null,
            ];

            // Lecture score: count * 5
            $lc = (int) ($lectureCounts[$uid] ?? 0);
            if ($lc > 0) {
                $data[$deptKey][$uid]['lecture'] = $lc * 5;
                $score += $lc * 5;
            }

            // Per-project transaction counts
            foreach ($projects as $project) {
                $countTransaction                      = (int) ($txMap[$uid][$project->id] ?? 0);
                $data[$deptKey][$uid][$project->title] = $countTransaction > 0 ? $countTransaction : null;
                $score += $countTransaction;
            }

            $data[$deptKey][$uid]['total'] = $score;
        }

        return view('nurse.admin.user_reports', compact('projects', 'data', 'departmentArray', 'department'));
    }
    public function UserScoreExport($department)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $name = 'nurseScore_' . $department;
        $name = str_replace(['/', '\\'], '', $name);

        return Excel::download(new NurseScoreExport($department), $name . date('d-m-Y') . '.xlsx');
    }
}
