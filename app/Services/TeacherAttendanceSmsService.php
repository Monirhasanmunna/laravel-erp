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

class TeacherAttendanceSmsService
{
    public function presentSms($teachersSettingTime, $teacher, $data, $branch)
    {
        $teachersAttendanceTime  = $teacher->attendance()->where('date', $data['date'])->where('source_id', $teacher->id)->first();
        $teachersPresentsms = PresentSmsSetting::where('type','teacher')->first();
        //diff minute beetween two times
        $teachersSettingTimeIntime     = Carbon::parse($teachersSettingTime->in_time);
        $teachersAttendanceTimeIntime  = Carbon::parse($teachersAttendanceTime->in_time);
        // calculation Delay time
        $delayTime = $teachersAttendanceTimeIntime->diffInMinutes($teachersSettingTimeIntime);
        // calculation Early time
        $earlyTime = $teachersSettingTimeIntime->diffInMinutes($teachersAttendanceTimeIntime);

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        // present sms start
        if($teachersAttendanceTimeIntime > $teachersSettingTimeIntime){
            $status = 'Delay : '.$delayTime;
        }else{
            $status = 'Early : '.$earlyTime;
        }

        if (isset($teachersPresentsms->content)) {
            $presentsmscontent= $teachersPresentsms->content;
            $replace            = [":Name", ":Time", ":Designation", ":Date", ":Status"];
            $replaceItem        = [$teacher->name, date('h:i A', strtotime($teachersAttendanceTime->in_time)), $teacher->designation->title, $data['date'], $status];
            $presentsmsContent  = str_replace($replace, $replaceItem, $presentsmscontent);

            if($teachersPresentsms->status == true && $teacher->mobile_number && $teachersAttendanceTime->status == 'present'){
                if($stock_sms > 0 ){
                    SmsSendJob::dispatch('Teacher Manual Attendance',$branch->institute->id,$branch->id,$teacher->mobile_number,$presentsmsContent);
                }
            }
        }
        // present sms end
    }


    public function delaySms($teachersSettingTime, $teacher, $data, $branch)
    {
        $teachersAttendanceTime  = $teacher->attendance()->where('date', $data['date'])->where('source_id', $teacher->id)->first();
        $teachersDelaySms = DelaySmsSetting::where('type','teacher')->first();
        //diff minute beetween two times
        $teachersSettingTimeIntime     = Carbon::parse($teachersSettingTime->in_time);
        $teachersAttendanceTimeIntime  = Carbon::parse($teachersAttendanceTime->in_time);
        // calculation Delay time
        $delayTime = $teachersAttendanceTimeIntime->diffInMinutes($teachersSettingTimeIntime);

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        // delay sms start
        if (isset($teachersDelaySms->content)) {
            $delaysmscontent= $teachersDelaySms->content;
            $replace        = [":Name", ":Time", ":Designation", ":Date", ":Delay"];
            $replaceItem    = [$teacher->name, date('h:i A', strtotime($teachersAttendanceTime->in_time)), $teacher->designation->title, $data['date'], $delayTime];
            $delaysmsContent= str_replace($replace, $replaceItem, $delaysmscontent);

            if($teachersAttendanceTimeIntime > $teachersSettingTimeIntime){
                if($teachersDelaySms->status == true && $teacher->mobile_number && $teachersAttendanceTime->status == 'present'){
                    if($stock_sms > 0){
                        SmsSendJob::dispatch('Teacher Manual Attendance',$branch->institute->id,$branch->id,$teacher->mobile_number,$delaysmsContent);
                    }
                }
            }
        }
        // delay sms end
    }


    public function earlySms($teachersSettingTime, $teacher, $data, $branch)
    {
        $teachersAttendanceTime  = $teacher->attendance()->where('date', $data['date'])->where('source_id', $teacher->id)->first();
        $teachersEarlySms = EarlySmsSetting::where('type','teacher')->first();
        //diff minute beetween two times
        $teachersSettingTimeIntime     = Carbon::parse($teachersSettingTime->in_time);
        $teachersAttendanceTimeIntime  = Carbon::parse($teachersAttendanceTime->in_time);
        // calculation Early time
        $earlyTime = $teachersSettingTimeIntime->diffInMinutes($teachersAttendanceTimeIntime);

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        // early sms start
        if (isset($teachersEarlySms->content)) {
            $earlysmscontent    = $teachersEarlySms->content;
            $replace            = [":Name", ":Time", ":Designation", ":Date", ":Early"];
            $replaceItem        = [$teacher->name, date('h:i A', strtotime($teachersAttendanceTime->in_time)), $teacher->designation->title, $data['date'], $earlyTime];
            $earlysmsContent    = str_replace($replace, $replaceItem, $earlysmscontent);

            if($teachersSettingTimeIntime > $teachersAttendanceTimeIntime){
                if($teachersEarlySms->status == true && $teacher->mobile_number && $teachersAttendanceTime->status == 'present'){
                    if($stock_sms > 0 ){
                        SmsSendJob::dispatch('Teacher Manual Attendance',$branch->institute->id,$branch->id,$teacher->mobile_number,$earlysmsContent);
                    }
                }
            }
        }
        // early sms end
    }



    public function absentSms($teachersSettingTime, $teacher, $data, $branch)
    {
        $teachersAttendanceTime  = $teacher->attendance()->where('date', $data['date'])->where('source_id', $teacher->id)->first();
        $teachersAbsentsms = AbsentSmsSetting::where('type','teacher')->first();

        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

       // absent sms start
       if (isset($teachersAbsentsms->content)) {
        $absentsmscontent   = $teachersAbsentsms->content;
        $replace            = [":Name", ":Designation", ":Date"];
        $replaceItem        = [$teacher->name, $teacher->designation->title, $data['date']];
        $absentSmsContent   = str_replace($replace, $replaceItem, $absentsmscontent);

        if($teachersAbsentsms->status == true && $teacher->mobile_number && $teachersAttendanceTime->status == 'absent'){
            if($stock_sms > 0 ){
                SmsSendJob::dispatch('Teacher Manual Attendance',$branch->institute->id,$branch->id,$teacher->mobile_number,$absentSmsContent);
            }
        }
        }
        // absent sms end
    }


    public function leaveSms($teacherApplication)
    {
        $stock_sms = Helper::smsBalance();
        $stock_sms = $stock_sms->total_balance;

        $leavesmscontent = LeavesmsSetting::where('type','teacher')->first();

        $teacher = $teacherApplication->source->teacher;
        
        $teacher_name        = $teacher->name;
        $teacher_designation = $teacher->designation->title;
        $leave_days          = $teacherApplication->total_day.' Days '.'('. $teacherApplication->to_date.' to '.$teacherApplication->from_date.'),'. $teacherApplication->application.'.';
        
        // sms replace string
        $smscontent     = $leavesmscontent->content;
        $replace        = [":Name", ":Designation", ":Days"];
        $replaceItem    = [$teacher_name, $teacher_designation, $leave_days];
        $smsContent     = str_replace($replace, $replaceItem, $smscontent);

        
        if($leavesmscontent->status == true && $leavesmscontent->number && $stock_sms > 0){
            Helper::sd_send_sms_api('88'.$leavesmscontent->number, $smsContent);
            $teacher->smshistory()->create([
                'title'         => 'Teacher Leave Application',
                'description'   => $smsContent,
            ]);
        }
    }
}