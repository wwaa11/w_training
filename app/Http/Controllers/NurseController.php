<?php
namespace App\Http\Controllers;

use App\Models\NurseDate;
use App\Models\NurseProject;
use App\Models\NurseTime;
use App\Models\NurseTransaction;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseController extends Controller
{
    public function Index()
    {
        $projects = NurseProject::where('active', true)
            ->whereDate('register_start', '<=', date('Y-m-d'))
            ->whereDate('register_end', '>=', date('Y-m-d'))
            ->get();

        $myTransaction = NurseTransaction::where('user_id', Auth::user()->userid)
            ->where('active', true)
            ->orderBy('date_time', 'asc')
            ->get();

        return view('nurse.index', compact('projects', 'myTransaction'));
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
        $time = NurseTime::find($request->time_id);

        $new                   = new NurseTransaction();
        $new->nurse_project_id = $request->project_id;
        $new->nurse_time_id    = $request->time_id;
        $new->date_time        = $time->time_start;
        $new->user_id          = Auth::user()->userid;
        $new->save();

        $response = [
            'status'  => 'success',
            'message' => 'ทำการลงทำเบียนสำเร็จ!',
        ];

        return response()->json($response, 200);
    }
    public function TransactionDelete(Request $request)
    {
        $transaction = Transaction::where('project_id', $request->project_id)
            ->where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->first();

        $transaction->transaction_active = false;
        $transaction->save();

        $item = Item::where('id', $transaction->item_id)->first();
        $item->item_available += 1;
        $item->save();

        if ($transaction->seat !== null) {

            $seatArray                            = Seat::where('item_id', $transaction->item_id)->first();
            $temp                                 = $seatArray->seats;
            $temp[$transaction->seat - 1]['user'] = null;
            $temp[$transaction->seat - 1]['dept'] = null;
            $seatArray->seats                     = $temp;
            $seatArray->save();
        }

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
        $projects = NurseProject::where('active', true)->get();

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
            'training_start' => 'required|date',
            'training_end'   => 'required|date|after_or_equal:register_start',
            'time'           => 'required|array',
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

        $training_start = new DateTime($request->training_start);
        $training_end   = new DateTime($request->training_end . ' +1 Days');
        $interval       = new DateInterval('P1D');
        $dateRange      = new DatePeriod($training_start, $interval, $training_end);
        foreach ($dateRange as $date) {
            $date       = $date->format('Y-m-d');
            $dateCreate = NurseDate::create([
                'nurse_project_id' => $project->id,
                'title'            => $this->FulldateTH($date),
                'date'             => $date,
            ]);
            foreach ($request->time as $time) {
                NurseTime::create([
                    'nurse_date_id' => $dateCreate->id,
                    'title'         => $time['title'],
                    'time_start'    => $date . ' ' . $time['start'],
                    'time_end'      => $date . ' ' . $time['end'],
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

        $old_transaction = NurseTransaction::where('project_id', $request->project_id)
            ->where('user', $userid)
            ->where('transaction_active', true)
            ->first();

        if ($old_transaction !== null) {
            $old_transaction->active = false;
            $old_transaction->save();
        }

        if ($userData !== null) {

            $new                   = new NurseTransaction();
            $new->nurse_project_id = $request->project_id;
            $new->nurse_time_id    = $request->time_id;
            $new->date_time        = $request->time_start;
            $new->user_id          = $request->user;
            $new->save();

            $response = [
                'status'  => 'success',
                'message' => 'ทำการลงทำเบียนสำเร็จ!',
                'time'    => $new->timeData->title,
                'name'    => $userData->userid . ' ' . $userData->name,
            ];
        }

        return response()->json($response, 200);
    }
    public function adminProjectDeleteTransaction(Request $request)
    {
        $transaction         = NurseTransaction::find($request->transaction_id);
        $transaction->active = false;
        $transaction->save();

        $data = [
            'status'  => 'success',
            'message' => 'ลบข้อมูลการลงทะเบียนสำเร็จ',
        ];

        return response()->json($data, 200);
    }
}
