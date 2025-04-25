<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Project;
use App\Models\Seat;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HumanResourceControler extends Controller
{
    public function Index()
    {
        $projects = Project::where('project_delete', false)
            ->where('last_register_datetime', '>=', date('Y-m-d'))
            ->get();

        $myItem = Transaction::where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'asc')
            ->get();

        return view('hr.user.index')->with(compact('myItem', 'projects'));
    }
    public function History()
    {
        $transactions = Transaction::where('user', Auth::user()->userid)
            ->where('transaction_active', true)
            ->orderBy('date', 'desc')
            ->get();

        return view('hr.user.history')->with(compact('transactions'));
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

            return view('hr.user.project')->with(compact('isRegister', 'transaction', 'project'));
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
    public function adminProjectManagement($project_id)
    {
        $project = Project::find($project_id);
        if ($project) {
            return view('hr.admin.project_management')->with(compact('project'));
        }

        return redirect(env('APP_URL') . '/hr/admin');
    }
}
