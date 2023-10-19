<?php

namespace App\Services;

use App\Helper\Helper;
use App\Jobs\SmsSendJob;
use App\Models\Attendance;
use App\Models\DeviceConfigure;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\Sms\SmsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\Sms\SmsCountService;

class SmsSendService
{
    public function sendPresentSms($title)
    {
        
        // $fiveMinuteAgo = Carbon::now()->subMinute(5)->format('H:i');
        // $now           = Carbon::now()->format('H:i');
        $timezone  = 'Asia/Dhaka';
        $startTime = Carbon::parse('today 07am', $timezone)->format('H:i');
        $endTime   = Carbon::parse('today 11am', $timezone)->format('H:i');

        $response = Http::get('https://digitalattendance.xyz/api/v1/get-attendance-by-time-range', [
                        "date"      => date('Y-m-d'),
                        "from_time" => $startTime,
                        "to_time"   => $endTime
                    ])->json();
           
        
        foreach ($response['data'] as $attendance){
            
            $institute = DeviceConfigure::withoutGlobalScopes()->where('device_token',$attendance['organization_api_key'])->first();
            
            if(@$institute){
                $matched    = ['institute_id'=>$institute->institute_id,'institute_branch_id' => $institute->institute_branch_id];
                $student = Student::withoutGlobalScopes()
                                ->where($matched)
                                ->where('device_id',$attendance['device_id'])
                                ->where('finger_id',$attendance['user_id'])
                                ->first();

             
                $teacher = Teacher::withoutGlobalScopes()->where($matched)
                                ->where('device_id',$attendance['device_id'])
                                ->where('finger_id',$attendance['user_id'])
                                ->first();

                $smsContentService  = new AttendanceSmsServiceContent(); //sms content service
                
                if(@$student){
                    $content = $smsContentService->StudentPresentSms($matched,$student,$attendance['time']);
                    //dispatch job
                    SmsSendJob::dispatch($title,$institute->institute_id,$institute->institute_branch_id,$student->mobile_number,$content);
                }

                if(@$teacher){
                    $content = $smsContentService->TeacherPresentSms($matched,$teacher,$attendance['time']);
                    //dispatch job
                    SmsSendJob::dispatch($title,$institute->institute_id,$institute->institute_branch_id,$teacher->mobile_number,$content);
                }
            }
        }
        
    }



    // public function sendAbsentSms()
    // {
    //     $fiveMinuteAgo = Carbon::now()->subMinute(5)->format('H:i');
    //     $now           = Carbon::now()->format('H:i');

    //     $api_key = DeviceConfigure::withoutGlobalScopes()
    //                ->where('institute_id',Helper::getInstituteId())
    //                ->where('institute_branch_id',Helper::getBranchId())
    //                ->first();

    //     $smsServce = new ApiAttendanceSmsService();

    //     //Teacher Absent Sms
    //     $teachers = Teacher::withoutGlobalScopes()
    //                 ->where('institute_id',Helper::getInstituteId())
    //                 ->where('institute_branch_id',Helper::getBranchId())
    //                 ->where('type','teacher')
    //                 ->get();

    //     $fingerId = [];
    //     $deviceId = [];

    //     foreach ($teachers as $teacher){
    //         array_push($fingerId,$teacher->finger_id);
    //         array_push($deviceId,$teacher->device_id);
    //     }

    //     $response = Http::get('http://teamx-digital-attendance.test/api/v1/get-attendance-by-device-id', [
    //         "date"      => date('Y-m-d'),
    //         "from_time" => $fiveMinuteAgo,
    //         "to_time"   => $now,
    //         'finger_id'  => $fingerId,
    //         'device_id'  => $deviceId,
    //         'api_key'   => $api_key->device_token
    //     ])->json();


    //    $attendance = $teachers->map(function($item) use($response){
    //         return [
    //             'institute_id'  => $item->institute_id,
    //             'name'          => $item->name,
    //             'mobile_number' => $item->mobile_number,
    //             'designation'   => $item->designation->title,
    //             'in_time'       => collect($response)->where('finger_id',$item['finger_id'])->where('device_id',$item['device_id'])->where('time',date('Y-m-d'))->first() ?? Attendance::where('source_id',$item['id'])->where('source_type','App\Models\Teacher')->where('date',date('Y-m-d'))->first()['in_time'] ?? null,
    //         ];
    //    });


    //    if(@$attendance){
    //         foreach ($attendance as $key => $teacher) {
    //             if ($teacher['in_time'] == null) {
    //                 $content = $smsServce->TeacherAbsentSms($teacher);
    //                 SmsSendJob::dispatch($teacher['institute_id'],$teacher['mobile_number'],$content);
    //             }
    //         }
    //     }

    //     //Teacher Absent Sms

    //     //Student Absent Sms
    //     $students = Student::withoutGlobalScopes()
    //                 ->where('institute_id',Helper::getInstituteId())
    //                 ->where('institute_branch_id',Helper::getBranchId())
    //                 ->get();


    //      $studentfingerId = [];
    //      $studentdeviceId = [];

    //      foreach ($students as $student){
    //          array_push($studentfingerId,$student->finger_id);
    //          array_push($studentdeviceId,$student->device_id);
    //      }


    //     $studentResponse = Http::get('http://teamx-digital-attendance.test/api/v1/get-attendance-by-device-id', [
    //         "date"      => date('Y-m-d'),
    //         "from_time" => $fiveMinuteAgo,
    //         "to_time"   => $now,
    //         'finger_id'  => $studentfingerId,
    //         'device_id'  => $studentdeviceId,
    //         'api_key'   => $api_key->device_token
    //     ])->json();


    //    $studentAttendance = $students->map(function($item) use($studentResponse){
    //         return [
    //             'institute_id'  => $item->institute_id,
    //             'name'          => $item->name,
    //             'mobile_number' => $item->mobile_number,
    //             'class'         => $item->class->name,
    //             'in_time'       => collect($studentResponse)->where('finger_id',$item['finger_id'])->where('device_id',$item['device_id'])->where('time',date('Y-m-d'))->first() ?? Attendance::where('source_id',$item['id'])->where('source_type','App\Models\Student')->where('date',date('Y-m-d'))->first()['in_time'] ?? null,
    //         ];
    //    });


    //    if(@$studentAttendance){
    //         foreach ($studentAttendance as $key => $student) {
    //             if ($student['in_time'] == null) {
    //                 $content = $smsServce->StudentAbsentSms($student);
    //                 SmsSendJob::dispatch($student['institute_id'],$student['mobile_number'],$content);
    //             }
    //         }
    //     }
    // //Student Absent Sms

    // }
}












