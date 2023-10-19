<?php

namespace App\Http\Controllers\Attendance\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DeviceConfigure;
use App\Models\Teacher;
use App\Models\TeacherTimesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DailyReportController extends Controller
{
    public function index()
    {
        $teachers = Teacher::where('type','teacher')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.teacher_report.daily-report', compact('teachers'));
    }

    public function TeacherDailyReport(Request $request)
    {
        $data = [];
        $datas = $request->all();
        $data['date'] = $datas['from_date'];
        $api_key  = DeviceConfigure::orderBy('id','DESC')->first();

        if($datas['from_date']){
            $teachers = Teacher::where('type','teacher')->get();
            $fingerId = [];
            $deviceId = [];
            foreach ($teachers as $teacher){
                array_push($fingerId,$teacher->finger_id);
                array_push($deviceId,$teacher->device_id);
            }

            $apiResponse = Http::get('https://digitalattendance.xyz/api/v1/get-attendance-by-date', [
                'api_key'   => $api_key->device_token,
                'finger_id' => $fingerId,
                'device_id' => $deviceId,
                'date'      => $datas['from_date'],
            ])->json();
             
            $data['attendances'] = $teachers->map(function($item) use($apiResponse,$data){
                            return [
                                'id_no'        => $item->id_no,
                                'name'         => $item->name,
                                'designation'  => $item->designation->title ?? 'Teacher',
                                'teacher_time' => $item->teacherTime ?? ['in_time' => '10:00','out_time'=>'16:00'],
                                'in_time'      => $this->getTeacherInTime($apiResponse,$item->finger_id,$item->device_id, $data['date'], $item->id),
                                'out_time'     => $this->getTeacherOutTime($apiResponse,$item->finger_id,$item->device_id, $data['date'], $item->id),
                                'total_pesent' => $this->totalPresentTeacher($apiResponse,$item->finger_id,$item->device_id, $data['date'], $item->id),
                                'total_teacher' => $this->totalTeacher(),
                                'status'       => $this->leaveStatus($data['date'], $item->id),
                            ];
                    });
            //dd($data);  
            return $data;
        }
    }

    public function getTeacherInTime($response,$fingerId,$deviceId,$date,$id)
    {
       return collect($response)->where('finger_id',$fingerId)->where('device_id',$deviceId)->first()['in_time'] ?? Attendance::where('source_id',$id)->where('source_type','App\Models\Teacher')->where('date',$date)->first()['in_time'] ?? '';
    }

    public function getTeacherOutTime($response,$fingerId,$deviceId,$date,$id)
    {
       return collect($response)->where('finger_id',$fingerId)->where('device_id',$deviceId)->first()['out_time'] ?? Attendance::where('source_id',$id)->where('source_type','App\Models\Teacher')->where('date',$date)->first()['out_time'] ?? '';
    }

    public function totalPresentTeacher($response,$fingerId,$deviceId,$date,$id)
    {
        return collect($response)->where('finger_id',$fingerId)->where('device_id',$deviceId)->where('in_time', '!=', '')->first()?? Attendance::where('source_id',$id)->where('source_type','App\Models\Teacher')->where('date',$date)->where('in_time', '!=', '')->first();
    }

    public function totalTeacher()
    {
        return count(Teacher::where('type','teacher')->get());
    }

    public function leaveStatus($date,$id)
    {
       return Attendance::where('source_id',$id)->where('source_type','App\Models\Teacher')->where('date',$date)->where('status','leave')->first() ? 'leave' : null;
    }
}
