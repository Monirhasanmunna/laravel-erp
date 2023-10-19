<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Models\DeviceConfigure;
use App\Models\Teacher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Attendance\Report\DailyReportController;
use App\Models\ApiSmsCheck;
use App\Models\TeacherTimesetting;
use App\Services\ApiAttendanceSmsService;
use Illuminate\Support\Facades\Log;

class AutoSmsSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autosms:teacher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teacher Auto Sms Send Successfully';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $teachers = Teacher::withoutGlobalScopes()
                    ->where('institute_id',Helper::getInstituteId())
                    ->where('institute_branch_id',Helper::getBranchId())
                    ->where('type','teacher')
                    ->get();

        $api_key = DeviceConfigure::withoutGlobalScopes()
                   ->where('institute_id',Helper::getInstituteId())
                   ->where('institute_branch_id',Helper::getBranchId())
                   ->first();
                   

        $fingerId = [];
        $deviceId = [];
        foreach ($teachers as $teacher){
            array_push($fingerId,$teacher->finger_id);
            array_push($deviceId,$teacher->device_id);
        }

        $apiResponse = Http::get('https://digitalattendance.xyz/api/v1/get-attendance-by-date', [
            'api_key'   => $api_key['device_token'],
            'finger_id' => $fingerId,
            'device_id' => $deviceId,
            'date'      => date('Y-m-d'),
        ])->json();

        $totalTeachers = $teachers->map(function($item) use($apiResponse){
            return [
                'id'           => $item->id,
                'id_no'        => $item->id_no,
                'name'         => $item->name,
                'designation'  => $item->designation->title,
                'mobile_number'=> $item->mobile_number,
                'teacher_time' => $item->teacherTime,
                'in_time'      => collect($apiResponse)->where('finger_id',$item['finger_id'])->where('device_id',$item['device_id'])->first()['in_time'],
                'out_time'     => collect($apiResponse)->where('finger_id',$item['finger_id'])->where('device_id',$item['device_id'])->first()['out_time'],
            ];
        });

        foreach(@$totalTeachers as $teacher){
            if($teacher['in_time'] != null){
                //search old record
               $oldSms = ApiSmsCheck::withoutGlobalScopes()
                        ->where('institute_id',Helper::getInstituteId())
                        ->where('institute_branch_id',Helper::getBranchId())
                        ->where('sms_id',$teacher['id'])
                        ->first();
                //check with old record
                if(@$oldSms->sms_id != $teacher['id']){
                    //new sms send
                    (new ApiAttendanceSmsService)->TeacherPresentSms($teacher, date('Y-m-d')); 
                    //new record create after send sms
                    ApiSmsCheck::create([
                        'sms_id'        => $teacher['id'],
                        'id_no'         => $teacher['id_no'],
                        'name'          => $teacher['name'],
                        'in_time'       => $teacher['in_time'],
                        'out_time'      => $teacher['out_time'],
                    ]);
                }
            }
        }
    }
}
