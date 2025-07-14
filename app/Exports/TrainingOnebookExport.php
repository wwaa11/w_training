<?php
namespace App\Exports;

use App\Models\TrainingAttend;
use DateTime;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingOnebookExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function array(): array
    {
        $date       = $this->date;
        $users      = TrainingAttend::where('user', true)->where('admin', true)->where('name', 'like', $date . '%')->get();
        $exportRows = [];
        foreach ($users as $user) {
            if (! array_key_exists($user->user_id, $exportRows)) {
                $dates                      = $user->date->time->dates;
                $firstDate                  = date('d/m/Y', strtotime($dates->first()->name));
                $lastDate                   = date('d/m/Y', strtotime($dates->last()->name));
                $exportRows[$user->user_id] = [
                    'EmployeeID'     => $user->user_id,
                    'CourseID'       => '',
                    'StartDate'      => $firstDate,
                    'EndDate'        => $lastDate,
                    'Teacher'        => $user->date->time->session->teacher->name,
                    'ClassNo '       => '',
                    'CourseNameTH '  => '',
                    'CourseNameEN'   => '',
                    'TrainingType'   => '',
                    'TrainingMethod' => '',
                    'TrainingHours'  => '0.0',
                ];
            }

            $hours = $this->calculateTrainingHours($user->date->time->name);

            // Parse current total
            list($totalHours, $totalMinutes) = explode('.', $exportRows[$user->user_id]['TrainingHours']);
            // Parse new value
            list($addHours, $addMinutes) = explode('.', $hours);

            // Convert to integers
            $totalHours   = (int) $totalHours;
            $totalMinutes = (int) $totalMinutes;
            $addHours     = (int) $addHours;
            $addMinutes   = (int) $addMinutes;

            // Add
            $totalHours += $addHours;
            $totalMinutes += $addMinutes;

            // If minutes >= 60, convert to hours
            if ($totalMinutes >= 60) {
                $extraHours = intdiv($totalMinutes, 60);
                $totalHours += $extraHours;
                $totalMinutes = $totalMinutes % 60;
            }

            // Store back in "hours.minutes" format, pad minutes to 2 digits
            $exportRows[$user->user_id]['TrainingHours'] = $totalHours . '.' . str_pad($totalMinutes, 2, '0', STR_PAD_LEFT);
        }

        return $exportRows;
    }

    private function calculateTrainingHours($timeRange)
    {
        $parts = explode('-', $timeRange);
        if (count($parts) === 2) {
            list($start, $end) = array_map('trim', $parts);
            $startTime         = DateTime::createFromFormat('H:i', $start);
            $endTime           = DateTime::createFromFormat('H:i', $end);
            if ($startTime && $endTime) {
                $interval = $startTime->diff($endTime);
                $hours    = $interval->h;
                $minutes  = $interval->i;
                return $hours . "." . str_pad($minutes, 2, '0', STR_PAD_LEFT);
            }
        }
        return '-';
    }

    public function headings(): array
    {
        return [
            'EmployeeID',
            'CourseID',
            'StartDate',
            'EndDate',
            'Teacher',
            'ClassNo ',
            'CourseNameTH ',
            'CourseNameEN',
            'TrainingType',
            'TrainingMethod',
            'TrainingHours',
            'TrainingVenue',
            'TrainingCost',
            'TrainingObjective',
            'TrainingProvider',
            'InstructorExternal',
            'TrainingDSDType',
            'DSDCertificateNo',
            'DSDCertificateDate',
            'TrainingResults',
            'Remark',
        ];
    }
}
