<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $confirmAdmission = Notification::where('type','confirm-admission')->first();
        $admission = Notification::where('type','admission')->first();
        $payment = Notification::where('type','payment-collection')->first();
        $dues = Notification::where('type','dues')->first();
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.sms-notification.index',compact('confirmAdmission','admission','payment','dues'));
    }


    public function admissionTemplate(Request $request){

        Notification::where('type','admission')->first()->update([
            'status' => $request->status == 1 ? true : false 
        ]);

        return response()->json([
            'data' => 'admission',
            'status' => 200,
            'message' => 'Admission Notification Updated Successfully'
        ]);
    }


    public function PaymentCollection(Request $request){

        Notification::where('type','payment-collection')->first()->update([
            'status' => $request->status == 1 ? true : false 
        ]);

        return response()->json([
            'data' => 'payment',
            'status' => 200,
            'message' => 'Payment Notification Updated Successfully'
        ]);
    }

    public function DuesCollection(Request $request){

        Notification::where('type','dues')->first()->update([
            'status' => $request->status == 1 ? true : false 
        ]);

        return response()->json([
            'data' => 'dues',
            'status' => 200,
            'message' => 'Dues Notification Updated Successfully'
        ]);
    }



    public function resetTemplates($type)
    {
        $content =  Notification::where('type',$type)->first();
        if(@$content){$content->delete();}

        // notification content
        $admission = 'Name : :Name, Admission Id : :admissionId Your Admission Submited.';
        $confirmAdmission = 'Dear Student :Name, Roll: :Roll Your Admission Confirmed.';
        $confirmPayment = 'Dear Student :Name, Roll: :Roll Your Payment Confirmed.';
        $dues = 'Dear Student :Name, Roll: :Roll Your Dues.';

        $template = '';
        if('confirm-admission' == $type){
            $template = $confirmAdmission;
        }else if('admission' == $type){
            $template = $admission;
        }else if('payment-collection' == $type){
            $template = $confirmPayment;
        }else if('dues' == $type){
            $template = $dues;
        }


        Notification::create([
            'content' => $template,
            'type'    => $type
        ]);

        //notification
        $notification = array(
            'message' =>$type.' Notification Template Reset Successfully',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }
}
