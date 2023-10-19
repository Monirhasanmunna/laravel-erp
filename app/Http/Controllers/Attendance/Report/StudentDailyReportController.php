<?php

namespace App\Http\Controllers\Attendance\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DeviceConfigure;
use App\Models\InsClass;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentDailyReportController extends Controller
{
    public function index()
    {
        $data = [];
        $data['academic_years'] = Session::all();
        $data['classes']        = InsClass::all();
        $data['sections']       = Section::all();
        return view($this->backendTemplate['template']['path_name'].'.attendance.student_report.daily-report')->with($data);
    }

    public function StudentDailyReport(Request $request)
    {
        $data = [];
        $datas = $request->all();
        $data['date'] = $datas['date'];
        $api_key  = DeviceConfigure::orderBy('id','DESC')->first();

        if($datas['date']){
            $students = Student::where('session_id',$datas['academic_year_id'])
                                ->where('class_id',$datas['class_id'])
                                ->where('section_id',$datas['section_id'])
                                ->where('category_id',$datas['category_id'])
                                ->where('group_id',$datas['group_id'])->get();
            $fingerId = [];
            $deviceId = [];
            foreach ($students as $student){
                array_push($fingerId,$student->finger_id);
                array_push($deviceId,$student->device_id);
            }

            $apiResponse = Http::get('https://digitalattendance.xyz/api/v1/get-attendance-by-date', [
                'api_key'   => $api_key->device_token,
                'finger_id' => $fingerId,
                'device_id' => $deviceId,
                'date'      => $datas['date'],
            ])->json();
             
            $data['attendances'] = $students->map(function($item) use($apiResponse,$datas){
                            return [
                                'id_no'         => $item->id_no,
                                'name'          => $item->name,
                                'class'         => $item->ins_class->name,
                                'shift'         => $item->shift->name,
                                'section'       => $item->section->name,
                                'student_time'  => $item->ins_class->studentTime,
                                'in_time'       => $this->getStudentInTime($apiResponse,$item->finger_id,$item->device_id, $datas['date'], $item->id),
                                'out_time'      => $this->getStudentOutTime($apiResponse,$item->finger_id,$item->device_id, $datas['date'], $item->id),
                                'total_pesent'  => $this->totalPresentStudent($apiResponse,$item->finger_id,$item->device_id, $datas['date'], $item->id),
                                'total_student' => $this->totalStudent($datas['academic_year_id'],$datas['class_id'],$datas['section_id'],$datas['category_id'],$datas['group_id']),
                            ];
                    });
            //dd($data);  
            return $data;
        }
    }

    public function getStudentInTime($response,$fingerId,$deviceId,$date,$id)
    {
       return collect($response)->where('finger_id',$fingerId)->where('device_id',$deviceId)->first()['in_time'] ?? Attendance::where('source_id',$id)->where('source_type','App\Models\Student')->where('date',$date)->first()['in_time'] ?? '';
    }

    public function getStudentOutTime($response,$fingerId,$deviceId,$date,$id)
    {
       return collect($response)->where('finger_id',$fingerId)->where('device_id',$deviceId)->first()['out_time'] ?? Attendance::where('source_id',$id)->where('source_type','App\Models\Student')->where('date',$date)->first()['out_time'] ?? '';
    }

    public function totalPresentStudent($response,$fingerId,$deviceId,$date,$id)
    {
        return collect($response)->where('finger_id',$fingerId)->where('device_id',$deviceId)->where('in_time', '!=', '')->first()?? Attendance::where('source_id',$id)->where('source_type','App\Models\Student')->where('date',$date)->where('in_time', '!=', '')->first();
    }

    public function totalStudent($academic_year_id, $class_id, $section_id, $category_id, $group_id)
    {
        $students = Student::where('session_id',$academic_year_id)
                            ->where('class_id',$class_id)
                            ->where('section_id',$section_id)
                            ->where('category_id',$category_id)
                            ->where('group_id',$group_id)->get();

        return count($students);
    }

}
