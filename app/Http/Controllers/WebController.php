<?php
namespace App\Http\Controllers;

use App\Exports\DBDExport;
use App\Exports\OnebookExport;
use App\Exports\ProjectExport;
use App\Exports\SlotExport;
use App\Http\Controllers\WebController;
use App\Models\Item;
use App\Models\Project;
use App\Models\Slot;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class WebController extends Controller
{
    public function test()
    {
        // return abort(419);
    }

    // Auth Management
    public function loginPage()
    {
        return view('login');
    }
    public function loginRequest(Request $req)
    {
        $userid   = $req->userid;
        $password = $req->password;
        $data     = [
            'status'  => 'failed',
            'message' => null,
        ];

        $response = Http::withHeaders(['token' => env('API_KEY')])
            ->post('http://172.20.1.12/dbstaff/api/getuser', [
                'userid' => $req->userid,
            ])
            ->json();

        $data['message'] = 'ไม่พบรหัสพนักงานนี้';

        if ($response['status'] == 1) {
            $userData = User::where('userid', $req->userid)->first();

            if (! $userData) {
                $userData           = new User();
                $userData->userid   = $userid;
                $userData->password = Hash::make($userid);
            }
            $userData->name        = $response['user']['name'];
            $userData->position    = $response['user']['position'];
            $userData->department  = $response['user']['department'];
            $userData->division    = $response['user']['division'];
            $userData->hn          = $response['user']['HN'];
            $userData->gender      = $response['user']['gender'];
            $userData->refNo       = $response['user']['refID'];
            $userData->passport    = $response['user']['passport'];
            $userData->last_update = date('Y-m-d H:i:s');
            $userData->save();

            $data['message'] = 'รหัสพนักงาน หรือ รหัสผ่านผิด';

            if (Auth::attempt(['userid' => $userid, 'password' => $password])) {
                session([
                    'name'       => $response['user']['name'],
                    'position'   => $response['user']['position'],
                    'department' => $response['user']['department'],
                    'division'   => $response['user']['division'],
                    'email'      => $response['user']['email'],
                ]);

                $data['status']  = 'success';
                $data['message'] = 'เข้าสู่ระบบสำเร็จ';
            }
        }

        return response()->json($data, 200);
    }
    public function logoutRequest(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function IndexchangePassword()
    {
        $user = Auth::user();

        return view('changepassword')->with(compact('user'));
    }
    public function changePassword(Request $request)
    {
        $user         = Auth::user();
        $old_password = $request->old_password;
        $password     = $request->password;
        if (Hash::check($old_password, $user->password)) {
            $user->password         = Hash::make($password);
            $user->password_changed = true;
            $user->refNo            = $request->refno;
            $user->sign             = $request->sign;
            $user->save();

            $sign = DB::connection('STAFF')
                ->table('signs')
                ->where('userid', $user->userid)
                ->first();
            if ($sign == null) {
                $sign = DB::connection('STAFF')
                    ->table('signs')
                    ->insert(['userid' => $user->userid, 'sign' => $request->sign, 'sign_time' => date('Y-m-d H:i:s'), 'consent_witness' => 0]);
            } else {
                $sign = DB::connection('STAFF')
                    ->table('signs')
                    ->where('userid', $user->userid)
                    ->update(['sign' => $request->sign, 'sign_time' => date('Y-m-d H:i:s')]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } else {

            return view('changepassword')->with(compact('user'))->withErrors('Password mismatch!');
        }

    }

    // User Management
    public function index()
    {
        $user = Auth::user();
        if (! $user->password_changed) {

            return view('changepassword')->with(compact('user'));
        }

        $projects = Project::where('project_delete', false)
            ->where('last_register_datetime', '>=', date('Y-m-d'))
            ->get();

        $myItem = Transaction::where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'asc')
            ->get();

        return view('Project.index')->with(compact('user', 'myItem', 'projects'));

    }
    public function history()
    {
        $transactions = Transaction::where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'desc')
            ->get();

        return view('Project.history')->with(compact('transactions'));
    }
    public function ProjectIndex($project_id)
    {
        $project = Project::find($project_id);
        if (date("Y-m-d") >= date("Y-m-d", strtotime($project->start_register_datetime)) &&
            date("Y-m-d") <= date("Y-m-d", strtotime($project->last_register_datetime))) {

            $transaction = Transaction::where('project_id', $project_id)
                ->where('user', Auth::user()->userid)
                ->where('transaction_active', true)
                ->first();

            $isRegister = $transaction == null ? false : true;

            return view('Project.project')->with(compact('isRegister', 'transaction', 'project'));
        }

        return redirect(env('APP_URL') . '/');
    }
    public function TransactionSave(Request $req)
    {
        $response = [
            'status'  => 'failed',
            'message' => 'รอบที่เลือกเต็มแล้ว!',
        ];
        $item = Item::find($req->item_id);
        if ($item->item_available > 0) {
            $item->item_available -= 1;
            $item->save();

            $new             = new Transaction();
            $new->project_id = $req->project_id;
            $new->item_id    = $req->item_id;
            $new->user       = Auth::user()->userid;
            $new->date       = $item->slot->slot_date;
            $new->save();

            $response = [
                'status'  => 'success',
                'message' => 'ทำการลงทำเบียนสำเร็จ!',
            ];
        }

        return response()->json($response, 200);
    }
    public function TransactionDelete(Request $req)
    {
        $transaction = Transaction::where('project_id', $req->project_id)
            ->where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->first();

        $transaction->transaction_active = false;
        $transaction->save();

        $item = Item::where('id', $transaction->item_id)->first();
        $item->item_available += 1;
        $item->save();

        $response = [
            'status'  => 'success',
            'message' => 'ทำการเปลี่ยนรอบการลงทะเบียนสำเร็จ!',
        ];

        return response()->json($response, 200);
    }
    public function TransactionSign(Request $req)
    {
        $transaction                   = Transaction::find($req->transaction_id);
        $transaction->checkin          = true;
        $transaction->checkin_datetime = date('Y-m-d H:i');
        $transaction->save();

        $response = [
            'status'  => 'success',
            'message' => 'ลงชื่อสำเร็จ!',
        ];

        return response()->json($response, 200);
    }

    // Project management
    public function adminIndex()
    {
        $projects = Project::where('project_delete', false)->get();

        return view('admin.index')->with(compact('projects'));
    }
    public function adminCreateProject()
    {
        return view('admin.Project_create');
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
    public function adminCreateProject_AddDate(Request $req)
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
    public function adminStoreProject(Request $req)
    {
        $validate = false;
        if ($req->project_name !== null && $req->slot !== null && $req->item['list']) {
            $validate = true;
        } else {
            return back()->with('message', 'ข้อมูลไม่ถูกต้อง!');
        }
        if ($validate) {
            $project                          = new Project();
            $project->project_name            = $req->project_name;
            $project->project_detail          = $req->project_detail;
            $project->start_register_datetime = date('Y-m-d', strtotime(array_key_first($req->slot) . "+1 days"));
            $project->last_register_datetime  = date('Y-m-d', strtotime(array_key_last($req->slot) . "-1 days"));
            $project->save();
            $slotindex = 0;
            foreach ($req->slot as $sl) {
                $slotindex += 1;
                $slot             = new Slot();
                $slot->project_id = $project->id;
                $slot->slot_index = $slotindex;
                $slot->slot_date  = $sl['date'];
                $slot->slot_name  = $sl['title'];
                $slot->save();

                $listIndex = 0;
                foreach ($req->item['list'] as $list) {
                    $listIndex += 1;
                    $li              = new Item();
                    $li->slot_id     = $slot->id;
                    $li->item_index  = $listIndex;
                    $li->item_name   = $list['name'];
                    $li->item_detail = $list['detail'];
                    if ($req->item['item_note_1_title'] !== null) {
                        $li->item_note_1_active = true;
                        $li->item_note_1_title  = $req->item['item_note_1_title'];
                        $li->item_note_1_value  = $list['note_1_value'];
                    }
                    if ($req->item['item_note_2_title'] !== null) {
                        $li->item_note_2_active = true;
                        $li->item_note_2_title  = $req->item['item_note_2_title'];
                        $li->item_note_2_value  = $list['note_2_value'];
                    }
                    if ($req->item['item_note_3_title'] !== null) {
                        $li->item_note_3_active = true;
                        $li->item_note_3_title  = $req->item['item_note_3_title'];
                        $li->item_note_3_value  = $list['note_3_value'];
                    }
                    $li->item_available = $list['avabile'];
                    $li->save();
                }
            }
        }

        return redirect(env('APP_URL') . '/admin/project/' . $project->id);
    }

    public function adminViewProject($id)
    {
        $project = Project::find($id);

        return view('admin.Project_view')->with(compact('project'));
    }
    public function adminProjectUser($id)
    {
        $project = Project::find($id);

        return view('admin.Project_users')->with(compact('project'));
    }
    public function adminProjectUserDelete(Request $req)
    {
        $transaction                     = Transaction::find($req->transaction_id);
        $transaction->transaction_active = false;
        $transaction->save();

        $item                 = Item::find($transaction->item_id);
        $item->item_available = $item->item_available + 1;
        $item->save();

        $data = [
            'status'  => 'success',
            'message' => 'ลบข้อมูลการลงทะเบียนสำเร็จ',
        ];

        return response()->json($data, 200);
    }
    // Proejct Export
    public function adminPDFSlot($item_id)
    {
        $item = Item::find($item_id);
        $pdf  = Pdf::loadView('admin.export.slot', compact('item'));

        return $pdf->stream('test.pdf');
    }
    public function adminExcelDate($project_id)
    {
        $project = Project::find($project_id);
        $name    = $project->project_name;

        return Excel::download(new ProjectExport($project_id), $name . '.xlsx');
    }
    public function adminExcelSlot($slot_id)
    {
        $slot = Slot::find($slot_id);
        $name = $slot->project->project_name . '_' . $slot->slot_name;

        return Excel::download(new SlotExport($slot_id), $name . '.xlsx');
    }
    public function adminExcelOnebook($project_id)
    {
        $project = Project::find($project_id);
        $name    = 'Onebook_' . $project->project_name;

        return Excel::download(new OnebookExport($project_id), $name . '.xlsx');
    }
    public function adminExcelDBD($project_id)
    {
        $project = Project::find($project_id);
        $name    = 'DBD_' . $project->project_name;

        return Excel::download(new DBDExport($project_id), $name . '.xlsx');
    }

    // User Management
    public function adminUser()
    {
        $users = User::orderBy('admin', 'desc')->orderBy('userid', 'asc')->get();

        return view('admin.Users_Management')->with(compact('users'));
    }
    public function adminUserResetPassword(Request $req)
    {
        $user                   = User::where('userid', $req->userid)->first();
        $user->password         = Hash::make($req->userid);
        $user->password_changed = false;
        $user->save();

        $sessions = DB::table('sessions')->where('user_id', $user->id)->delete();

        $data = [
            'status'  => 'success',
            'message' => 'รีเซ็ตรหัสผ่านสำเร็จ',
        ];

        return response()->json($data, 200);
    }
    public function admincheckinProject($project_id)
    {
        $project      = Project::find($project_id);
        $transactions = Transaction::where('project_id', $project_id)->where('transaction_active', true)->where('checkin', true)->where('hr_approve', false)->get();
        $select       = 'not approve';

        return view('admin.Project_checkin')->with(compact('project', 'transactions', 'select'));
    }
    public function adminapprovedProject($project_id)
    {
        $project      = Project::find($project_id);
        $transactions = Transaction::where('project_id', $project_id)->where('transaction_active', true)->where('checkin', true)->where('hr_approve', true)->get();
        $select       = 'approved';

        return view('admin.Project_checkin')->with(compact('project', 'transactions', 'select'));
    }
    public function admincheckinProjectApprove(Request $req)
    {
        $transaction                      = Transaction::find($req->id);
        $transaction->hr_approve          = true;
        $transaction->hr_approve_datetime = date('Y-m-d H:i:s');
        $transaction->save();

        $data = [
            'status'  => 'success',
            'message' => 'Approve สำเร็จ',
        ];

        return response()->json($data, 200);
    }
}
