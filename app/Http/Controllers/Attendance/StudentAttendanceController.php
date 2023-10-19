<?php

namespace App\Http\Controllers\Attendance;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AbsentSmsSetting;
use App\Models\Attendance;
use App\Models\DelaySmsSetting;
use App\Models\EarlySmsSetting;
use App\Models\InsClass;
use App\Models\PresentSmsSetting;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentAbsentsmsSetting;
use App\Models\StudentDelaysmsSetting;
use App\Models\StudentEarlysmsSetting;
use App\Models\StudentTimesetting;
use Carbon\Carbon;
use App\Services\StudentAttendanceSmsService;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $data = [];
        $data['academic_years'] = Session::all();
        $data['classes']        = InsClass::all();
        $data['sections']       = Section::all();
        $data['attendance'] = Attendance::where('source_type','App\Models\Student')->where('date',date('Y-m-d'))->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.student.index')->with($data);
    }


    public function getStudents(Request $request)
    {
        $students = Student::where('class_id',$request->class_id)
                    ->where('session_id',$request->academic_year_id)
                    ->where('category_id',$request->category_id)
                    ->where('group_id',$request->group_id)->where('section_id',$request->section_id)
                    ->where('shift_id',$request->shift_id)->get();


        $data = [];

        if(isset($students)){
            foreach ($students as $key => $student) {
                $data['attendances'] = $student->attendance->with('source')->where('source_type','App\Models\Student')->where('date',$request->date)->get();
            }
        }
        
        return $data;
    }

    public function create()
    {
        $academic_years = Session::all();
        $classes        = InsClass::all();
        $sections       = Section::all();
        return view($this->backendTemplate['template']['path_name'].'.attendance.student.create', compact('academic_years','sections', 'classes'));
    }


    public function store(Request $request, StudentAttendanceSmsService $studentAttendanceSms)
    {
       $data = $request->all();
        // return $data;
        if (!empty($data['check'])){
            foreach($data['check'] as $key => $val){

                $student = Student::find($data['student_id'][$val]);

                $studentsSettingTime  = StudentTimesetting::where('ins_class_id',$student->ins_class->id)->first();

                $attend_status = 'attend_status' . $data['student_id'][$val];

                $student->attendance()->where('date',$data['date'])->where('source_id',$student->id)->delete();

                if(isset($studentsSettingTime)){
                    $student->attendance()->updateOrCreate([
                        'date'       => $data['date'],
                        'finger_id'  => $student->finger_id ?? 00,
                        'in_time'    => $data['in_time_input'][$val],
                        'out_time'   => $data['out_time_input'][$val],
                        'status'     => $data[$attend_status]
                    ]);
                }else{
                    //notification
                    $notification = array(
                        'message' =>"Please set student's time first",
                        'alert-type' =>'error'
                    );
                    return redirect()->back()->with($notification);
                }
                
                $branch = $student->branch;
                //studentAttendanceSms Services
                $studentAttendanceSms->presentSms($studentsSettingTime, $student, $data, $branch);
                $studentAttendanceSms->delaySms($studentsSettingTime, $student, $data, $branch);
                $studentAttendanceSms->earlySms($studentsSettingTime, $student, $data, $branch);
                $studentAttendanceSms->absentSms($studentsSettingTime, $student, $data, $branch);

            }

            return redirect()->route('attendance.student.index')->with('message','Data Create Successfully');

        }else{
            return redirect()->back()->with('message','No Student Select!');
        }
    }


    public function report(){
        $students = Student::all();
        return view($this->backendTemplate['template']['path_name'].'.attendance.student.report',compact('students'));
    }


    public function getStudentStatus(Request $request){

        $data = $request->all();

        if($data['student_id']){
            $attendances = Student::find($data['student_id'])->attendance()->whereBetween('date', [$data['from_date'], $data['to_date']])->with('source')->get();
            return $attendances;
        }
        else{
            $attendances = Attendance::where('source_type','App\Models\Student')->whereBetween('date', [$data['from_date'], $data['to_date']])->with('source')->get();
            return $attendances;
        }
    }
}
