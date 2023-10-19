<?php

namespace App\Http\Controllers\Teacher;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SmsSendJob;
use App\Models\InstituteBranch;
use App\Models\SmsHistory;
use App\Models\Teacher;
use App\Models\TeacherSms;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BirthDayWishController extends Controller
{
    public function index(Request $request){

        $date    = $request->dob;
        $month  = Carbon::parse($request->dob)->month;
        $day    = Carbon::parse($request->dob)->day;

        $teachers = new Teacher();
        $teachers = $teachers->whereMonth('date_of_birth', '=', $month);
        $teachers = $teachers->whereDay('date_of_birth', '=', $day);
        $teachers = $teachers->get();

        return view($this->backendTemplate['template']['path_name'].'.teachers.birthday-wish.index',compact('teachers','date'));
    }

    public function sendMessage(Request $request)
    {
        $stockSms = Helper::smsBalance()->currentBalance;
        $branch    = InstituteBranch::find(Helper::getBranchId());
       
        if($request->message && @$branch){
            if($stockSms > 0){
                foreach ($request->teacher_id as $key => $id) {

                    $teacher = Teacher::find($id);
                    $name    = $teacher->name;
                    $number  = $teacher->mobile_number;

                    $message = $request->message;
                    $find = ':NAME';
                    $newMessage = str_replace($find, $name, $message);

                    SmsSendJob::dispatch('Teacher Birth Day',Helper::getInstituteId(),$branch->id,$teacher->mobile_number,$newMessage);
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
