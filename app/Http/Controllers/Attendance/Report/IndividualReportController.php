<?php

namespace App\Http\Controllers\Attendance\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\TeacherTimesetting;
use Illuminate\Http\Request;

class IndividualReportController extends Controller
{
    public function index()
    {
        $teachers = Teacher::where('type','teacher')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.teacher_report.individual-report', compact('teachers'));
    }

    public function IndividualReport(Request $request){
        $data = $request->all();

        if($data['teacher_id']){
            $items = [];
            $items['form_date'] = $data['from_date'];
            $items['to_date'] = $data['to_date'];
            $items['attendances'] = Teacher::find($data['teacher_id'])->attendance()->whereBetween('date', [$data['from_date'], $data['to_date']])->with('source')->get();
            $items['teacher_time'] = TeacherTimesetting::where('teacher_id',$data['teacher_id'])->first();
            return $items;
        }
    }
}
