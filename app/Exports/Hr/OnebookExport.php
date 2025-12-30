<?php
namespace App\Exports\Hr;

use App\Models\HrAttend;
use App\Models\HrDate;
use App\Models\HrProject;
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

        $project = HrProject::findOrFail($this->project_id);

        $attends = HrAttend::with(['user', 'date', 'time'])
            ->where('project_id', $this->project_id)
            ->where('attend_delete', false)
            ->orderBy('user_id', 'ASC')
            ->orderBy('approve_datetime', 'ASC')
            ->get();

        $exist = [];
        $data  = [];
        foreach ($attends as $attend) {
            if (! in_array($attend->user->userid, $exist)) {
                $exist[]                     = $attend->user->userid;
                $data[$attend->user->userid] = [
                    'name'    => $attend->user->name,
                    'hours'   => 0,
                    'approve' => null,
                ];
            }
            if ($attend->attend_datetime !== null && $attend->approve_datetime !== null) {
                $hours = $attend->time->time_start->diffInHours($attend->time->time_end);
                $data[$attend->user->userid]['hours'] += $hours;
            }
            if ($attend->approve_datetime !== null) {
                $data[$attend->user->userid]['approve'] = $attend->approve_datetime;
            }
        }

        if ($project->onebook?->skip_hours > 0) {
            foreach ($data as $index => $list) {
                if ($list['hours'] > 0) {
                    $data[$index]['hours'] -= $project->onebook->skip_hours;
                }
            }
        }

        return view('hrd.admin.export.onebook-report')->with(compact('date', 'data'));
    }
}
