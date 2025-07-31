<?php
namespace App\Exports\Hr;

use App\Models\HrAttend;
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
        $attends = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $this->project_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->get();

        return view('hrd.admin.export.onebook')->with(compact('attends'));
    }
}
