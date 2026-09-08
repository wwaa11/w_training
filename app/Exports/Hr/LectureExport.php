<?php
namespace App\Exports\Hr;

use App\Models\HrDate;
use App\Models\HrProject;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class LectureExport implements FromView, ShouldAutoSize, WithDrawings
{
    protected $project_id;

    protected $date_id;

    protected $dates;

    public function __construct($project_id, $date_id = null)
    {
        $this->project_id = $project_id;
        $this->date_id    = $date_id;
    }

    /**
     * Row order must stay identical between the view rows and the signature
     * drawings, so both read from this single cached query.
     */
    protected function dates()
    {
        if ($this->dates === null) {
            $query = HrDate::with(['lectures.user', 'times'])
                ->active()
                ->where('project_id', $this->project_id);

            if ($this->date_id) {
                $query->where('id', $this->date_id);
            }

            $this->dates = $query->orderBy('id', 'ASC')->get();
        }

        return $this->dates;
    }

    public function drawings()
    {
        $drawings = [];

        $drawing = new Drawing();
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/Side Logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawings[] = $drawing;

        $row = 0;
        foreach ($this->dates() as $date) {
            foreach ($date->lectures as $lecture) {
                if ($lecture->user && $lecture->user->sign !== null) {
                    $base64 = explode(',', $lecture->user->sign, 2);
                    if (count($base64) >= 2) {
                        $sign = imagecreatefromstring(base64_decode($base64[1]));
                        if ($sign !== false) {
                            imagesavealpha($sign, true);

                            $drawing = new MemoryDrawing();
                            $drawing->setImageResource($sign);
                            $drawing->setHeight(15);
                            $drawing->setWidth(120);
                            $drawing->setCoordinates('G' . ($row + 5));
                            $drawings[] = $drawing;
                        }
                    }
                }

                $row += 1;
            }
        }

        return $drawings;
    }

    public function view(): View
    {
        $project          = HrProject::find($this->project_id);
        $dates            = $this->dates();
        $project_date     = '';
        $project_time     = [];
        $project_locations = [];

        foreach ($dates as $date) {
            $project_date .= $date->date_title . ' - ';
            foreach ($date->times as $time) {
                $project_time[] = $time->time_title;
            }
            if ($date->date_location) {
                $project_locations[] = $date->date_location;
            }
        }

        $project_date     = rtrim($project_date, ' - ');
        $project_location = implode(' , ', array_unique($project_locations));

        return view('hr.admin.export.Lecturer')->with(compact(
            'project',
            'dates',
            'project_date',
            'project_time',
            'project_location'
        ));
    }
}
