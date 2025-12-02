<?php
namespace App\Exports\Hr;

use App\Models\HrAttend;
use App\Models\HrDate;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OnebookExport implements FromView
{
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view(): View
    {
        $TrainingDate = HrDate::where('project_id', $this->project_id)
            ->where('date_delete', false)
            ->orderBy('date_datetime', 'ASC')
            ->get();

        $date = [
            'first' => date("d/m/Y", strtotime($TrainingDate->first()->date_datetime)),
            'last'  => date("d/m/Y", strtotime($TrainingDate->last()->date_datetime)),
        ];

        $attends = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $this->project_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->get();

        return view('hrd.admin.export.onebook-report')->with(compact('attends', 'date'));
    }
}
