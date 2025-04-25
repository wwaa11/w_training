<?php
namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OnebookExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view(): view
    {
        $transactions = Transaction::where('project_id', $this->project_id)->where('transaction_active', true)->get();

        return view('hr.admin.export.onebook')->with(compact('transactions'));
    }
}
