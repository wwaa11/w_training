<?php
namespace App\Http\Controllers;

use App\Exports\AllDateExport;
use App\Exports\DateExport;
use App\Exports\DBDExport;
use App\Exports\OnebookExport;
use App\Models\Item;
use App\Models\Link;
use App\Models\Project;
use App\Models\Seat;
use App\Models\Slot;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class HumanResourceControler extends Controller
{
    // Dev Add Date to Project
    public function addDatetoProject()
    {

    }
    // User
    public function Index()
    {
        $projects = Project::where('project_delete', false)
            ->where('last_register_datetime', '>=', date('Y-m-d'))
            ->get();

        $myItem = Transaction::where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'asc')
            ->get();

        return view('hr.index')->with(compact('myItem', 'projects'));
    }
    public function History()
    {
        $transactions = Transaction::where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'desc')
            ->get();

        return view('hr.history')->with(compact('transactions'));
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

            return view('hr.project')->with(compact('isRegister', 'transaction', 'project'));
        }

        return redirect(env('APP_URL') . '/');
    }
    public function TransactionCreate(Request $request)
    {
        $response = [
            'status'  => 'failed',
            'message' => 'รอบที่เลือกเต็มแล้ว!',
        ];

        $item = Item::find($request->item_id);
        if ($item->item_available > 0) {
            $item->item_available -= 1;
            $item->save();

            $new             = new Transaction();
            $new->project_id = $request->project_id;
            $new->item_id    = $request->item_id;
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

        Log::channel('hr_delete')->info('User : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete transaction id: ' . $transaction->id);

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

    // Admin
    public function adminIndex()
    {
        $projects = Project::where('project_delete', false)->get();

        return view('hr.admin.index')->with(compact('projects'));
    }
    public function adminProjectTransactions($project_id)
    {
        $project = Project::find($project_id);

        return view('hr.admin.project_transactions')->with(compact('project'));
    }
    public function adminProjectCreateTransaction(Request $req)
    {
        $response = [
            'status'  => 'failed',
            'message' => 'รอบที่เลือกเต็มแล้ว!',
        ];

        $userid   = $req->user;
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

        $old_transaction = Transaction::where('project_id', $req->project_id)
            ->where('user', $userid)
            ->where('transaction_active', true)
            ->first();

        if ($old_transaction !== null) {

            $old_transaction->transaction_active = false;
            $old_transaction->save();

            $item = Item::where('id', $old_transaction->item_id)->first();
            $item->item_available += 1;
            $item->save();

            if ($old_transaction->seat !== null) {
                $seatArray                                = Seat::where('item_id', $old_transaction->item_id)->first();
                $temp                                     = $seatArray->seats;
                $temp[$old_transaction->seat - 1]['user'] = null;
                $temp[$old_transaction->seat - 1]['dept'] = null;
                $seatArray->seats                         = $temp;
                $seatArray->save();
            }

            Log::channel('hr_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete transaction id: ' . $old_transaction->id . ' for user: ' . $userData->userid . ' ' . $userData->name);

        }

        if ($userData !== null) {
            $item = Item::find($req->item_id);
            if ($item->item_available > 0) {
                $item->item_available -= 1;
                $item->save();

                $new             = new Transaction();
                $new->project_id = $req->project_id;
                $new->item_id    = $req->item_id;
                $new->user       = $req->user;
                $new->date       = $item->slot->slot_date;
                $new->save();

                Log::channel('hr_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' add transaction id: ' . $new->id . ' for user: ' . $userData->userid . ' ' . $userData->name);

                $response = [
                    'status'  => 'success',
                    'message' => 'ทำการลงทำเบียนสำเร็จ!',
                    'slot'    => $item->item_name,
                    'name'    => $userData->userid . ' ' . $userData->name,
                ];
            }
        }

        return response()->json($response, 200);
    }
    public function adminProjectDeleteTransaction(Request $req)
    {
        $transaction                     = Transaction::find($req->transaction_id);
        $transaction->transaction_active = false;
        $transaction->save();

        $item                 = Item::find($transaction->item_id);
        $item->item_available = $item->item_available + 1;
        $item->save();

        if ($transaction->seat !== null) {
            $seatArray                            = Seat::where('item_id', $transaction->item_id)->first();
            $temp                                 = $seatArray->seats;
            $temp[$transaction->seat - 1]['user'] = null;
            $seatArray->seats                     = $temp;
            $seatArray->save();
        }

        Log::channel('hr_delete')->info('Admin : ' . Auth::user()->userid . ' ' . Auth::user()->name . ' delete transaction id: ' . $transaction->id . ' for user: ' . $transaction->userData->userid . ' ' . $transaction->userData->name);

        $data = [
            'status'  => 'success',
            'message' => 'ลบข้อมูลการลงทะเบียนสำเร็จ',
        ];

        return response()->json($data, 200);
    }
    public function adminProjectManagement($project_id)
    {
        $project = Project::find($project_id);
        if ($project) {
            return view('hr.admin.project_management')->with(compact('project'));
        }

        return redirect('/hr/admin');
    }
    // Approve
    public function adminProjectApprove(Request $request)
    {
        $checkin = false;
        $req     = $request->query;
        foreach ($req as $in => $value) {
            if ($in == 'project') {
                $project_id = $value;
            }
            if ($in == 'approve') {
                $checkin = $value;
            }
            if ($in == 'time') {
                $selectTime = $value;
                switch ($value) {
                    case 8:
                        $time = '08.30 - 10.00 น.';
                        break;
                    case 10:
                        $time = '10.30 - 12.00 น.';
                        break;
                    case 13:
                        $time = '13.30 - 15.00 น.';
                        break;
                    case 15:
                        $time = '15.30 - 17.00 น.';
                        break;
                    default:
                        $time = 'all';
                        break;
                }
            }
        }

        $project      = Project::find($project_id);
        $transactions = Transaction::where('project_id', $project_id)
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->where('transaction_active', true)
            ->where('checkin', true)
            ->whereDate('checkin_datetime', date('Y-m-d'))
            ->where('item_name', $time)
            ->where('hr_approve', $checkin)
            ->orderBy('seat', 'ASC')
            ->select(
                'transactions.*',
                'items.item_name'
            )
            ->get();

        $select = $checkin;

        return view('hr.admin.project_approve')->with(compact('project', 'transactions', 'select', 'selectTime'));
    }
    public function adminProjectApproveUser(Request $req)
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
    public function adminProjectApproveUserArray(Request $req)
    {
        $transactions = Transaction::whereIn('id', $req->id)->get();
        foreach ($transactions as $transaction) {

            $transaction->hr_approve          = true;
            $transaction->hr_approve_datetime = date('Y-m-d H:i:s');
            $transaction->save();
        }
        $data = [
            'status'  => 'success',
            'message' => 'Approve สำเร็จ',
        ];

        return response()->json($data, 200);
    }
    // Project management
    public function adminProjectLink($project_id)
    {
        $project = Project::find($project_id);

        return view('hr.admin.project_link')->with(compact('project'));
    }
    public function adminProjectLinkUpdate(Request $req)
    {
        $project_id = $req->project_id;
        if ($req->link !== null) {
            $array = [];
            foreach ($req->link as $link) {
                if ($link['title'] !== null) {

                    $array[] = [
                        "title" => $link['title'],
                        "url"   => $link['url'],
                    ];
                }
            }

            $link             = Link::firstOrNew(['project_id' => $project_id]);
            $link->project_id = $project_id;
            $link->links      = $array;
            $link->save();
        } else {

            $link = Link::where('project_id', $project_id);
            $link->delete();
        }

        return redirect('/hr/admin/link/' . $project_id);
    }
    // Exprot
    public function PDFTimeExport($item_id)
    {
        $item = Item::find($item_id);
        $pdf  = Pdf::loadView('hr.admin.export.PDF_TIME', compact('item'));

        return $pdf->stream($item->item_name . '.pdf');
    }
    public function ExcelDateExport($slot_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $slot = Slot::find($slot_id);
        $name = $slot->project->project_name . '_' . $slot->slot_name;

        return Excel::download(new DateExport($slot_id), $name . date('d-m-Y') . '.xlsx');
    }
    public function ExcelAllDateExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = Project::find($project_id);
        $name    = $project->project_name;

        return Excel::download(new AllDateExport($project_id), $name . date('d-m-Y') . '.xlsx');
    }
    public function ExcelOneBookExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = Project::find($project_id);
        $name    = 'Onebook_' . $project->project_name;

        return Excel::download(new OnebookExport($project_id), $name . date('d-m-Y') . '.xlsx');
    }
    public function ExcelDBDExport($project_id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        $project = Project::find($project_id);
        $name    = 'DBD_' . $project->project_name;

        return Excel::download(new DBDExport($project_id), $name . date('d-m-Y') . '.xlsx');
    }
}
