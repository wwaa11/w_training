<?php
namespace App\Http\Controllers;

use App\Http\Controllers\WebController;
use App\Models\Item;
use App\Models\Project;
use App\Models\Slot;
use Illuminate\Http\Request;

class WebController extends Controller
{
    // Project management
    public function adminCreateProject()
    {
        return view('admin.Project_create');
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
    public function adminCreateProject_AddDate(Request $req)
    {
        $dates = [];
        $start = $req->start;
        $end   = $req->end;

        $startDate = new \DateTime($start);
        $endDate   = new \DateTime($end . ' +1 Days');

        $interval  = new \DateInterval('P1D'); // 1 day interval
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($dateRange as $date) {
            $dates[] = [
                'date'  => $date->format('Y-m-d'),
                'title' => $this->FulldateTH($date->format('Y-m-d')),
            ];
        }

        return response()->json(['status' => 'success', 'dates' => $dates]);
    }
    public function adminStoreProject(Request $req)
    {
        $validate = false;
        if ($req->project_name !== null && $req->slot !== null && $req->item['list']) {
            $validate = true;
        } else {
            return back()->with('message', 'ข้อมูลไม่ถูกต้อง!');
        }
        if ($validate) {
            $project                          = new Project();
            $project->project_name            = $req->project_name;
            $project->project_detail          = $req->project_detail;
            $project->start_register_datetime = date('Y-m-d', strtotime(array_key_first($req->slot) . "+1 days"));
            $project->last_register_datetime  = date('Y-m-d', strtotime(array_key_last($req->slot) . "-1 days"));
            $project->save();
            $slotindex = 0;
            foreach ($req->slot as $sl) {
                $slotindex += 1;
                $slot             = new Slot();
                $slot->project_id = $project->id;
                $slot->slot_index = $slotindex;
                $slot->slot_date  = $sl['date'];
                $slot->slot_name  = $sl['title'];
                $slot->save();

                $listIndex = 0;
                foreach ($req->item['list'] as $list) {
                    $listIndex += 1;
                    $li              = new Item();
                    $li->slot_id     = $slot->id;
                    $li->item_index  = $listIndex;
                    $li->item_name   = $list['name'];
                    $li->item_detail = $list['detail'];
                    if ($req->item['item_note_1_title'] !== null) {
                        $li->item_note_1_active = true;
                        $li->item_note_1_title  = $req->item['item_note_1_title'];
                        $li->item_note_1_value  = $list['note_1_value'];
                    }
                    if ($req->item['item_note_2_title'] !== null) {
                        $li->item_note_2_active = true;
                        $li->item_note_2_title  = $req->item['item_note_2_title'];
                        $li->item_note_2_value  = $list['note_2_value'];
                    }
                    if ($req->item['item_note_3_title'] !== null) {
                        $li->item_note_3_active = true;
                        $li->item_note_3_title  = $req->item['item_note_3_title'];
                        $li->item_note_3_value  = $list['note_3_value'];
                    }
                    $li->item_available     = $list['avabile'];
                    $li->item_max_available = $list['avabile'];
                    $li->save();
                }
            }
        }

        return redirect(env('APP_URL') . '/admin/project/' . $project->id);
    }

}
