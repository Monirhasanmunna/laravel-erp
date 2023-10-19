<?php
namespace App\Services;

use App\Helper\Helper;
use App\Jobs\SmsSendJob;
use App\Models\AbsentSmsSetting;
use App\Models\DelaySmsSetting;
use App\Models\EarlySmsSetting;
use App\Models\LeavesmsSetting;
use App\Models\PresentSmsSetting;
use Carbon\Carbon;

class StudentAttendanceSmsService{
    public function presentSms($studentsSettingTime, $student, $data,$branch)
    {
        $studentsAttendance = $student->attendance()->where('date',$data['date'])->where('source_id', $student->id)->first();
        $studentspresentSms  = PresentSmsSetting::where('type','student')->first();

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;
        //diff minute beetween two times
        $studentsSettingTime     = Carbon::parse($studentsSettingTime->in_time);
        $studentsAttendanceTime  = Carbon::parse($studentsAttendance->in_time);

        // calculation Delay time
        $delayTime = $studentsAttendanceTime->diffInMinutes($studentsSettingTime);
        // calculation Early time
        $earlyTime = $studentsSettingTime->diffInMinutes($studentsAttendanceTime);


        if($studentsAttendanceTime > $studentsSettingTime){
            $status = 'Delay : '.$delayTime;
        }else{
            $status = 'Early : '.$earlyTime;
        }

        // present sms start
        if (isset($studentspresentSms->content)) {
            $presentsmscontent  = $studentspresentSms->content;
            $replace            = [":Name", ":Time", ":Date", ":Status"];
            $replaceItem        = [$student->name, date('h:i A', strtotime($studentsAttendanceTime)), $data['date'], $status];
            $smsContent         = str_replace($replace, $replaceItem, $presentsmscontent);


            if($studentspresentSms->status == true && $student->mobile_number && $studentsAttendance->status == 'present'){
                if($stock_sms > 0 ){
                    SmsSendJob::dispatch('Student Manual Present Attendance',$branch->institute->id,$branch->id,$student->mobile_number,$smsContent);
                }
            }
        }
        // present sms end
    }


    public function delaySms($studentsSettingTime, $student, $data, $branch)
    {
        $studentsAttendance = $student->attendance()->where('date',$data['date'])->where('source_id', $student->id)->first();
        $studentsDelaySms        = DelaySmsSetting::where('type','student')->first();

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        //diff minute beetween two times
        $studentsSettingTime     = Carbon::parse($studentsSettingTime->in_time);
        $studentsAttendanceTime  = Carbon::parse($studentsAttendance->in_time);

        // calculation Delay time
        $delayTime = $studentsAttendanceTime->diffInMinutes($studentsSettingTime);
        

         // delay sms start
         if(isset($studentsDelaySms->content)){
            $delaysmscontent= $studentsDelaySms->content;
            $replace        = [":Name", ":Time",":Date", ":Delay"];
            $replaceItem    = [$student->name, date('h:i A', strtotime($studentsAttendanceTime)),$data['date'], $delayTime];
            $smsContent= str_replace($replace, $replaceItem, $delaysmscontent);

            if($studentsAttendanceTime > $studentsSettingTime){
                if($studentsDelaySms->status == true && $student->mobile_number && $studentsAttendance->status == 'present' && $stock_sms > 0){
                    SmsSendJob::dispatch('Student Manual Delay Attendance',$branch->institute->id,$branch->id,$student->mobile_number,$smsContent);
                }
            }
        }
        // delay sms end
    }


    public function earlySms($studentsSettingTime, $student, $data, $branch)
    {
        $studentsAttendance = $student->attendance()->where('date',$data['date'])->where('source_id', $student->id)->first();
        $studentsEarlySms   = EarlySmsSetting::where('type','student')->first();

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        //diff minute beetween two times
        $studentsSettingTime     = Carbon::parse($studentsSettingTime->in_time);
        $studentsAttendanceTime  = Carbon::parse($studentsAttendance->in_time);

        // calculation Early time
        $earlyTime = $studentsSettingTime->diffInMinutes($studentsAttendanceTime);
        

         // early sms start
         if (isset($studentsEarlySms->content)) {
            $earlysmscontent    = $studentsEarlySms->content;
            $replace            = [":Name", ":Time", ":Date", ":Early"];
            $replaceItem        = [$student->name, date('h:i A', strtotime($studentsAttendanceTime)), $data['date'], $earlyTime];
            $smsContent    = str_replace($replace, $replaceItem, $earlysmscontent);

            if($studentsSettingTime > $studentsAttendanceTime){
                if($studentsEarlySms->status == true && $student->mobile_number && $studentsAttendance->status == 'present'){
                    if($stock_sms > 0 ){
                        SmsSendJob::dispatch('Student Manual Early Attendance',$branch->institute->id,$branch->id,$student->mobile_number,$smsContent);
                    }
                }
            }
        }
        // early sms end
    }


    public function absentSms($studentsSettingTime, $student, $data, $branch)
    {
        $studentsAttendance = $student->attendance()->where('date',$data['date'])->where('source_id', $student->id)->first();
        $studentsabsentSms  = AbsentSmsSetting::where('type','student')->first();

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        //diff minute beetween two times
        $studentsSettingTime     = Carbon::parse($studentsSettingTime->in_time);
        $studentsAttendanceTime  = Carbon::parse($studentsAttendance->in_time);

         // absent sms start
        if (isset($studentsabsentSms->content)) {
            $absentsmscontent   = $studentsabsentSms->content;
            $replace            = [":Name",":Date",];
            $replaceItem        = [$student->name, $data['date']];
            $smsContent         = str_replace($replace, $replaceItem, $absentsmscontent);

            if($studentsabsentSms->status == true && $student->mobile_number && $studentsAttendance->status == 'absent'){
                if($stock_sms > 0 ){
                    SmsSendJob::dispatch('Student Manual Absent Attendance',$branch->institute->id,$branch->id,$student->mobile_number,$smsContent);
                }
            }
        }
        // absent sms start
    }



    public function leaveSms($studentApplication)
    {
        $leavesmscontent = LeavesmsSetting::where('type','student')->first();

        $student       = $studentApplication->source->student;
        $student_name  = $student->name;
        $student_class = $student->ins_class->name.'-'.$student->shift->name.'-'.$student->section->name;
        $leave_days    = $studentApplication->total_day.' Days '.'('. $studentApplication->to_date.' to '.$studentApplication->from_date.'),'. $studentApplication->application.'.';
        
        // sms replace string
        $smscontent     = $leavesmscontent->content;
        $replace        = [":Name", ":Class", ":Days"];
        $replaceItem    = [$student_name, $student_class, $leave_days];
        $smsContent     = str_replace($replace, $replaceItem, $smscontent);

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        if($leavesmscontent->status == true && $leavesmscontent->number){
            if($stock_sms > 0){
                Helper::sd_send_sms_api('88'.$leavesmscontent->number, $smsContent);
                $student->smshistory()->create([
                    'title'         => 'Student Leave Application',
                    'description'   => $smsContent,
                ]);
            }
        }
    }
}