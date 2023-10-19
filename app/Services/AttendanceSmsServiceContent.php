<?php
namespace App\Services;

use App\Helper\Helper;
use App\Models\AbsentSmsSetting;
use App\Models\DelaySmsSetting;
use App\Models\Designation;
use App\Models\EarlySmsSetting;
use App\Models\LeavesmsSetting;
use App\Models\PresentSmsSetting;
use App\Models\StudentTimesetting;
use App\Models\TeacherTimesetting;
use Carbon\Carbon;


class AttendanceSmsServiceContent{

    public function TeacherPresentSms($matched,$teacher,$teacher_time)
    {
       $teachersPresentsms = PresentSmsSetting::withoutGlobalScopes()->where($matched)->where('type','teacher')->first();
       
       //get student present time setting
       $teacherTimeSetting = TeacherTimesetting::withoutGlobalScopes()
                            ->where($matched)
                            ->where('teacher_id',$teacher->id)
                            ->first();
       if(@$teacherTimeSetting){
            $teachersSettingTimeIntime     = Carbon::parse($teacherTimeSetting->in_time);
            $teachersAttendanceTimeIntime  = Carbon::parse($teacher_time);
            // calculation Delay time
            $delayTime = $teachersAttendanceTimeIntime->diffInMinutes($teachersSettingTimeIntime);
            // calculation Early time
            $earlyTime = $teachersSettingTimeIntime->diffInMinutes($teachersAttendanceTimeIntime);
    
            // present sms start
            if($teachersAttendanceTimeIntime > $teachersSettingTimeIntime){
                $status = 'Delay : '.$delayTime;
            }else{
                $status = 'Early : '.$earlyTime;
            }      
       }        

       $designation = Designation::withoutGlobalScopes()->find($teacher->designation_id)->first();

       if (isset($teachersPresentsms->content) && @$teacherTimeSetting) {
           $presentsmscontent  = $teachersPresentsms->content;
           $replace            = [":Name", ":Time", ":Designation", ":Date", ":Status"];
           $replaceItem        = [$teacher['name'], date('h:i A', strtotime($teacher['in_time'])), $designation->title ?? "Teacher", date('Y-m-d'), $status];
           $presentsmsContent  = str_replace($replace, $replaceItem, $presentsmscontent);
           return $presentsmsContent;
       }else{
            $teacherAttendTime  = Carbon::parse($teacher_time)->format('Y-m-d g:i A');
            return 'Dear Teacher '.$teacher['name']." Present At:".$teacherAttendTime ;
       }
    }


    public function StudentPresentSms($matched,$student,$presentTime)
    {

        $studentspresentSms = PresentSmsSetting::withoutGlobalScopes()->where($matched)->where('type','student')->first();
        //diff minute beetween two times
  
        //get student present time setting
        $studentTimeSetting = StudentTimesetting::withoutGlobalScopes()
                                ->where($matched)
                                ->where('ins_class_id',$student->class_id)
                                ->first();
                               
        if(@$studentTimeSetting){

            $studentsSettingTime = Carbon::parse($studentTimeSetting->in_time);
    
            $studentsAttendanceTime  = Carbon::parse($presentTime);
            // calculation Delay time
            $delayTime = $studentsAttendanceTime->diffInMinutes($studentsSettingTime);
            // calculation Early time
            $earlyTime = $studentsSettingTime->diffInMinutes($studentsAttendanceTime);
    
            if($studentsAttendanceTime > $studentsSettingTime){
                $status = 'Delay : '.$delayTime;
            }else{
                $status = 'Early : '.$earlyTime;
            }
        }                        


        // present sms start
            if (isset($studentspresentSms->content) && @$studentTimeSetting){
                $presentsmscontent  = $studentspresentSms->content;
                $replace            = [":Name", ":Time", ":Date", ":Status"];
                $replaceItem        = [$student['name'], date('h:i A', strtotime($studentsAttendanceTime)), date('Y-m-d'), $status];
                $smsContent         = str_replace($replace, $replaceItem, $presentsmscontent);
                return $smsContent;
            }else{
                $studentsAttendanceTime  = Carbon::parse($presentTime)->format('Y-m-d g:i A');
                return 'Dear Student '.$student['name']." Present At:".$studentsAttendanceTime ;
            }
        // present sms end
    }


    public function TeacherAbsentSms($teacher)
    {
        $teachersAbsentsms = AbsentSmsSetting::where('type','teacher')->first();

        // absent sms start
        if (isset($teachersAbsentsms->content)) {
            $absentsmscontent   = $teachersAbsentsms->content;
            $replace            = [":Name", ":Designation", ":Date"];
            $replaceItem        = [$teacher['name'], $teacher['designation'], date('Y-m-d')];
            $absentSmsContent   = str_replace($replace, $replaceItem, $absentsmscontent);
            return $absentSmsContent;
        }else{
            return 'Please Set Absent Sms Content';
        }
        // absent sms end
    }


    public function StudentAbsentSms($student)
    {
        $studentAbsentsms = AbsentSmsSetting::where('type','student')->first();

        // absent sms start
            if (isset($studentAbsentsms->content)) {
                $absentsmscontent   = $studentAbsentsms->content;
                $replace            = [":Name", ":Class", ":Date"];
                $replaceItem        = [$student['name'], $student['class'], date('Y-m-d')];
                $absentSmsContent   = str_replace($replace, $replaceItem, $absentsmscontent);
                return $absentSmsContent;
            }else{
                return 'Please Set Absent Sms Content';
            }
        // absent sms end
    }
}
