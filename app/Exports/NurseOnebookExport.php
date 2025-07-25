<?php
namespace App\Exports;

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
        $transactions = NurseTransaction::where('nurse_project_id', $this->project_id)->whereNotNull('admin_sign')->where('active', true)->get();

        return view('nurse.admin.export.onebook')->with(compact('transactions'));
    }
}
