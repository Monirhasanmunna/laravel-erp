<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shift;
use App\Models\SmsTemplates;
use App\Models\Student;
use App\Models\Teacher;
use App\Helper\Helper;
use App\Jobs\SmsSendJob;
use App\Models\Branch;
use App\Models\InstituteBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates      = SmsTemplates::all();
        $academic_years = Session::all();
        $classes        = InsClass::all();
        $shifts         = Shift::all();
        $sections       = Section::all();
        $groups         = Group::all();

        $teachers       = Teacher::where('type', 'teacher')->get();
        $staffs         = Teacher::where('type', 'staff')->get();
        $committies     = Teacher::where('type', 'committee')->get();

        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.sms.index',compact('templates','academic_years','classes','shifts','sections','groups','teachers','staffs','committies'));
    }


    public function studentsendsms(Request $request)
    {
        $student_ids = $request->check;
        $students  = Student::whereIn('id',$student_ids)->get();

        $template  = SmsTemplates::where('id',$request->template_id)->first();
        
        @$stockSms = Helper::smsBalance()->currentBalance;

        $branch    = InstituteBranch::find(Helper::getBranchId());
       

        if($request->sms_content && @$branch){
            if($stockSms > 0){
                foreach ($students as $student) {
                    SmsSendJob::dispatch('Student Sms Send',Helper::getInstituteId(),$branch->id,$student->mobile_number,$request->sms_content);
                }

                //notification
                $notification = array(
                    'message' =>'Sms Send Successfully',
                    'alert-type' =>'success'
                );
            }else {
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


    public function teachersendsms(Request $request)
    {
        $teacher_ids = $request->check;
        $teachers = Teacher::where('type','teacher')->whereIn('id',$teacher_ids)->get();

        $template = SmsTemplates::where('id',$request->template_id)->first();
        @$stockSms = Helper::smsBalance()->currentBalance;

        $branch = InstituteBranch::where('id',Helper::getBranchId())->first();

        if($request->sms_content && $branch){
            if($stockSms > 0){
                foreach ($teachers as $teacher) {
                    SmsSendJob::dispatch('Teacher Sms Send',$branch->institute->id,$branch->id,$teacher->mobile_number,$request->sms_content);
                }

                //notification
                $notification = array(
                    'message' =>'Sms Send Successfully',
                    'alert-type' =>'success'
                );
            }else {
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



    public function staffsendsms(Request $request)
    {
        $staff_ids = $request->check;
        $staffs = Teacher::where('type','staff')->whereIn('id',$staff_ids)->get();

        $template = SmsTemplates::where('id',$request->template_id)->first();
        @$stockSms = Helper::smsBalance()->currentBalance;

        $branch = InstituteBranch::where('id',Helper::getBranchId())->first();

        if($request->sms_content && $branch){
            if($stockSms > 0){
                foreach ($staffs as $staff) {
                    SmsSendJob::dispatch('Staff Sms Send',$branch->institute->id,$branch->id,$staff->mobile_number,$request->sms_content);
                }

                //notification
                $notification = array(
                    'message' =>'Sms Send Successfully',
                    'alert-type' =>'success'
                );
            }else {
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



    public function comitteesendsms(Request $request)
    {

        $committee_ids = $request->check;
        $committies = Teacher::where('type','committee')->whereIn('id',$committee_ids)->get();

        $template = SmsTemplates::where('id',$request->template_id)->first();
        @$stockSms = Helper::smsBalance()->currentBalance;

        $branch = InstituteBranch::where('id',Helper::getBranchId())->first();

        if($request->sms_content && $branch){
            if(@$stockSms > 0){
                foreach ($committies as $committee) {
                    SmsSendJob::dispatch('Committee Sms Send',$branch->institute->id,$branch->id,$committee->mobile_number,$request->sms_content);
                }

                //notification
                $notification = array(
                    'message' =>'Sms Send Successfully',
                    'alert-type' =>'success'
                );
            }else {
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
