<?php

namespace App\Exports;

use App\Models\NurseDate;
use App\Models\NurseTime;
use App\Models\NurseTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NurseOnebookExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view(): view
    {
        $dates = NurseDate::where('nurse_project_id', $this->project_id)
            ->where('active', true)
            ->orderBy('date', 'ASC')
            ->get();
        $first = $dates->first()->date;
        $last = $dates->last()->date;
        $times = NurseTime::where('nurse_date_id', $dates->first()->id)
            ->where('active', true)
            ->first();
        $strTime = explode('-', $times->title);
        $hoursPerslot = (strtotime($strTime[1]) - strtotime($strTime[0])) / 3600;

        $transactions = NurseTransaction::where('nurse_project_id', $this->project_id)
            ->whereNotNull('admin_sign')
            ->where('active', true)
            ->orderBy('user_id', 'asc')
            ->get();
        $exist = [];
        $data = [];
        foreach ($transactions as $transaction) {
            if (! in_array($transaction->user_id, $exist)) {
                $exist[] = $transaction->user_id;
                $data[$transaction->user_id] = [
                    'name' => $transaction->userData->name,
                    'hours' => 0,
                    'approve' => null,
                ];

            }
            if ($transaction->user_sign !== null && $transaction->admin_sign !== null) {
                $data[$transaction->user_id]['hours'] += $hoursPerslot;
            }
            if ($transaction->admin_sign !== null) {
                $data[$transaction->user_id]['approve'] = $transaction->admin_sign;
            }
        }

        return view('nurse.admin.export.onebook')->with(compact('data', 'first', 'last'));
    }
}
