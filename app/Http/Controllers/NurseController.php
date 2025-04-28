<?php
namespace App\Http\Controllers;

use App\Models\NurseDate;
use App\Models\NurseProject;
use App\Models\NurseTime;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    public function Index()
    {

        return view('nurse.index');
    }
    public function adminProjectIndex()
    {

        return view('nurse.admin.project_index');
    }
    public function adminProjectCreate()
    {

        return view('nurse.admin.project_create');
    }
    public function FulldateTH($date)
    {
        $dateTime = strtotime($date);
        $day      = date('d', $dateTime);
        $month    = date('m', $dateTime);
        $year     = date('Y', $dateTime);

        switch ($month) {
            case '01':
                $fullmonth = 'มกราคม';
                break;
            case '02':
                $fullmonth = 'กุมภาพันธ์';
                break;
            case '03':
                $fullmonth = 'มีนาคม';
                break;
            case '04':
                $fullmonth = 'เมษายน';
                break;
            case '05':
                $fullmonth = 'พฤษภาคม';
                break;
            case '06':
                $fullmonth = 'มิถุนายน';
                break;
            case '07':
                $fullmonth = 'กรกฎาคม';
                break;
            case '08':
                $fullmonth = 'สิงหาคม';
                break;
            case '09':
                $fullmonth = 'กันยายน';
                break;
            case '10':
                $fullmonth = 'ตุลาคม';
                break;
            case '11':
                $fullmonth = 'พฤศจิกายน';
                break;
            case '12':
                $fullmonth = 'ธันวาคม';
                break;
        }
        $year = $year + 543;

        $birthDate = date_create($date);
        $nowDate   = date_create(date('Y-m-d'));
        $diff      = $birthDate->diff($nowDate);

        $data = $day . ' ' . $fullmonth . ' ' . $year;

        return $data;
    }
    public function adminProjectStore(Request $request)
    {
        $request->validate([
            'title'          => 'required',
            'location'       => 'required',
            'register_start' => 'required|date',
            'register_end'   => 'required|date|after_or_equal:register_start',
            'training_start' => 'required|date',
            'training_end'   => 'required|date|after_or_equal:register_start',
            'time'           => 'required|array',
        ], [
            'time.required' => 'โปรดระบุรอบการลงทะเบียน',
        ]);

        $project = NurseProject::create([
            'title'          => $request->title,
            'detail'         => $request->detail,
            'location'       => $request->location,
            'register_start' => $request->register_start,
            'register_end'   => $request->register_end,
        ]);

        $training_start = new DateTime($request->training_start);
        $training_end   = new DateTime($request->training_end . ' +1 Days');
        $interval       = new DateInterval('P1D');
        $dateRange      = new DatePeriod($training_start, $interval, $training_end);
        foreach ($dateRange as $date) {
            $date = NurseDate::create([
                'nurse_project_id' => $project->id,
                'title'            => $this->FulldateTH($date->format('Y-m-d')),
                'date'             => $date->format('Y-m-d'),
            ]);
            foreach ($request->time as $time) {
                NurseTime::create([
                    'nurse_date_id' => $date->id,
                    'title'         => $time['title'],
                    'time_start'    => $time['start'],
                    'time_end'      => $time['end'],
                ]);
            }
        }

        return redirect()->route('NurseAdminIndex')->with('success', 'Project created successfully.');
    }
}
