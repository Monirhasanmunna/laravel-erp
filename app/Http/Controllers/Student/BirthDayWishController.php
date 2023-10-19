<?php

namespace App\Http\Controllers\Student;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SmsSendJob;
use App\Models\InstituteBranch;
use App\Models\SmsHistory;
use App\Models\Student;
use App\Models\StudentSms;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BirthDayWishController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->dob;
        $month = Carbon::parse($request->dob)->month;
        $day = Carbon::parse($request->dob)->day;

        $students = new Student();

        $students = $students->whereMonth('dob', '=', $month);
        $students = $students->whereDay('dob', '=', $day);
        $students = $students->get();

        return view($this->backendTemplate['template']['path_name'].'.student.birthday-wish.index',compact('students','date'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'student_id' => 'required'
        ]);


        $stockSms = Helper::smsBalance()->currentBalance;
        $branch   = InstituteBranch::find(Helper::getBranchId());
       
        if($request->message && @$branch){
            if($stockSms > 0){
                foreach ($request->student_id as $key => $id) {

                $student = Student::find($id);
                $name    = $student->name;
                $message = $request->message;
                $find = ':NAME';
                $newMessage = str_replace($find, $name, $message);

                SmsSendJob::dispatch('Student Birth Day',Helper::getInstituteId(),$branch->id,$student->mobile_number,$newMessage);

                }

                //notification
                $notification = array(
                    'message' =>'Sms Send Successfully',
                    'alert-type' =>'success'
                );
            }else{
                //notification
                $notification = array(
                    'message' =>'Not Enough SMS',
                    'alert-type' =>'error'
                );
            }
        }else{
            //notification
            $notification = array(
                'message' =>'SMS Content is Empty',
                'alert-type' =>'error'
            );
        }

        return redirect()->back()->with($notification);
    }
}
