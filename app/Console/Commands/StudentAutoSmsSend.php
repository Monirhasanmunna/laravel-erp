<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Models\DeviceConfigure;
use App\Models\Student;
use App\Services\ApiAttendanceSmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class StudentAutoSmsSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autosms:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Student Auto sms send here';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $students = Student::withoutGlobalScopes()
                    ->where('institute_id',Helper::getInstituteId())
                    ->where('institute_branch_id',Helper::getBranchId())
                    ->get();

        $api_key =  DeviceConfigure::withoutGlobalScopes()
                    ->where('institute_id',Helper::getInstituteId())
                    ->where('institute_branch_id',Helper::getBranchId())
                    ->first();

        $fingerId = [];
        $deviceId = [];
        foreach ($students as $student){
            array_push($fingerId,$student->finger_id);
            array_push($deviceId,$student->device_id);
        }

        $apiResponse = Http::get('https://digitalattendance.xyz/api/v1/get-attendance-by-date', [
                            'api_key'   => $api_key['device_token'],
                            'finger_id' => $fingerId,
                            'device_id' => $deviceId,
                            'date'      => date('Y-m-d'),
                        ])->json();


        $totalStudents = $students->map(function($item) use($apiResponse){
            return [
                 'id_no'         => $item->roll_no,
                 'name'          => $item->name,
                 'mobile_number' => $item->mobile_number,
                 'student_time'  => $item->ins_class->studentTime,
                 'in_time'       => collect($apiResponse)->where('finger_id',$item['finger_id'])->where('device_id',$item['device_id'])->first()['in_time'] ?? null,
                 'out_time'      => collect($apiResponse)->where('finger_id',$item['finger_id'])->where('device_id',$item['device_id'])->first()['out_time'] ?? null,
            ];
        });


        if(isset($totalStudents)){
            foreach($totalStudents as $student){
                if($student['in_time'] != null){
                    (new ApiAttendanceSmsService)->StudentPresentSms($student, date('Y-m-d'));
                }
            }
        }
    }
}
