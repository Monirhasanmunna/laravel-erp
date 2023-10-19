<?php

namespace App\Http\Controllers\Attendance\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\TeacherTimesetting;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;

class DateToDateReportController extends Controller
{
    public function index()
    {
        return view($this->backendTemplate['template']['path_name'].'.attendance.teacher_report.dateTodate-report');
    }

    public function DateToDateReport(Request $request){
        $data = $request->all();
        $items = [];
        $items['form_date'] = $data['from_date'];
        $items['to_date'] = $data['to_date'];

        $items['teachers'] = Teacher::where('type','teacher')->get()->map(function($item) use ($data){
            return [
                'id_no' => $item->id_no,
                'name'  => $item->name,
                'designation' => $item->designation,
                'teacher_time'=> $item->teacherTime,
                'attendance'  => $this->getAttendance($item->id, $data),
            ];
        });
    
         return $items;
    }


    public function getAttendance($teacher_id, $data)
    {
        //return Attendance::where('source_id',$teacher_id)->whereBetween('date', [$data['from_date'], $data['to_date']])->get();

        $start_date = '2018-06-14';
        $end_date = '2018-06-20';

        $date_range = array();

        $start = new DateTime($start_date);
        $end = new DateTime($end_date);

        // Since we want all dates between start and end dates inclusive
        $end = $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start, $interval, $end);

        foreach ($daterange as $date) {
            $date_range[] = [
                'date' => $date,
                'attend'=> Attendance::where('source_id',$teacher_id)->where('date', $date->format("Y-m-d"))->get() ?? null
            ];
        }


        return $date_range;
    }
    
}
