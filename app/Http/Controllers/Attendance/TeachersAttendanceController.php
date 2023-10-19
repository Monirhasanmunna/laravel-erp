<?php

namespace App\Http\Controllers\Attendance;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AbsentSmsSetting;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\DelaySmsSetting;
use App\Models\Designation;
use App\Models\EarlySmsSetting;
use App\Models\PresentSmsSetting;
use App\Models\StockSms;
use App\Models\Teacher;
use App\Models\TeacherTimesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\TeacherAttendanceSmsService;
use Illuminate\Support\Collection;

class TeachersAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = [];

        $data['teachers'] = Teacher::where('type','teacher')->get();
        $data['attendances'] = Attendance::where('source_type','App\Models\Teacher')->orderBy('date','DESC')->where('date',date('Y-m-d'))->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.teacher.index')->with($data);
    }

    public function getTeacherAttendanceByDate(Request $request)
    {


        if($request->teacher_id == 'all'){
           return Attendance::with('source.designation')->where('source_type','App\Models\Teacher')
                   ->orderBy('date','DESC')
                   ->where('date',$request->date)
                   ->get();
       }else{
           return Attendance::with('source.designation')->where('source_type','App\Models\Teacher')
                   ->where('source_id',$request->teacher_id)
                   ->orderBy('date','DESC')->where('date',$request->date)
                   ->get();
       }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $branches = Branch::all();
        $teachers = Teacher::where('type','teacher')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.teacher.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TeacherAttendanceSmsService $teacherAttendanceSms)
    {
        $data = $request->all();

        if (!empty($data['check'])){
            foreach ($data['check'] as $key => $val){

                $teacher  = Teacher::find($data['teacher_id'][$val]);
                $teachersSettingTime = TeacherTimesetting::where('teacher_id',$teacher->id)->first();

                $attend_status = 'attend_status' . $data['teacher_id'][$val];

                $teacher->attendance()->where('date', $data['date'])->where('source_id', $teacher->id)->delete();

                if(isset($teachersSettingTime)){
                    $teacher->attendance()->updateOrCreate([
                        'date'       => $data['date'],
                        'finger_id'  => $teacher->finger_id ?? 00,
                        'in_time'    => $data['in_time'][$val],
                        'out_time'   => $data['out_time'][$val],
                        'status'     => $data[$attend_status]
                    ]);
                }else{
                    //notification
                    $notification = array(
                        'message' =>"Please set teacher's time first",
                        'alert-type' =>'error'
                    );
                    return redirect()->back()->with($notification);
                }

                $branch = $teacher->branch;
                //teacherAttendanceSms Services
                $teacherAttendanceSms->presentSms($teachersSettingTime, $teacher, $data, $branch);
                $teacherAttendanceSms->delaySms($teachersSettingTime, $teacher, $data, $branch);
                $teacherAttendanceSms->earlySms($teachersSettingTime, $teacher, $data, $branch);
                $teacherAttendanceSms->absentSms($teachersSettingTime, $teacher, $data, $branch);

            }

            //notification
            $notification = array(
                'message' =>'Data Submit Successfully',
                'alert-type' =>'success'
            );
            return redirect()->route('attendance.teacher.index')->with($notification);
        } else {
            return redirect()->back()->with('message', 'No Student Select!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTeachers(Request $request)
    {
        $data = $request->all();
        $query = new Teacher();
        $item = [];
        $item['data'] = $data;
        if ($data['teacher_id'] != 'teachers' && $data['teacher_id'] != 'staffs'){
            $item['teachers'] = array($query::with('designation')->find($data['teacher_id']));
        }elseif($data['teacher_id'] == 'teachers'){
            $item['teachers'] =  $query::with('designation')->where('type','teacher')->get();
        }else{
            $item['teachers'] =  $query::with('designation')->where('type','staff')->get();
        }

        return response()->json($item);
    }


    public function getTeacherByType($type)
    {
        $data = [];
        $data['type'] = $type;
        $data['teachers'] = Teacher::where('type',$type)->get();
        return $data;
    }
}
