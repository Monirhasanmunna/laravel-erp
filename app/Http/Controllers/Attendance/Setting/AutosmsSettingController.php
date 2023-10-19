<?php

namespace App\Http\Controllers\Attendance\Setting;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AbsentSmsSetting;
use App\Models\ApiAutoSmsSend;
use App\Models\DelaySmsSetting;
use App\Models\EarlySmsSetting;
use App\Models\LeavesmsSetting;
use App\Models\PresentSmsSetting;
use Illuminate\Http\Request;

class AutosmsSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
    
        $data['presentSMS']         = PresentSmsSetting::where('type','teacher')->first();
        $data['studentpresentSMS']  = PresentSmsSetting::where('type','student')->first();
        $data['absentSMS']          = AbsentSmsSetting::where('type','teacher')->first();
        $data['studentabsentSMS']   = AbsentSmsSetting::where('type','student')->first();
        $data['delaySMS']           = DelaySmsSetting::where('type','teacher')->first();
        $data['studentdelaySMS']    = DelaySmsSetting::where('type','student')->first();
        $data['earlySMS']           = EarlySmsSetting::where('type','teacher')->first();
        $data['studentearlySMS']    = EarlySmsSetting::where('type','student')->first();
        $data['stuLeavesms']        = LeavesmsSetting::where('type','student')->first();
        $data['teacherLeavesms']    = LeavesmsSetting::where('type','teacher')->first();
        $data['teacherapisms']      = ApiAutoSmsSend::where('type','teacher')->first();
        $data['studntapisms']       = ApiAutoSmsSend::where('type','student')->first();
        
        return view($this->backendTemplate['template']['path_name'].'.attendance.setting.autosmssetting.index')->with($data);
    }



    public function teacherPresentSMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);


      $sms = PresentSmsSetting::where('type','teacher')->first();

      if($sms){ $sms->delete();}

       $presentSMS = PresentSmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'teacher',
        ]);

        return response()->json([
            'data' => $presentSMS,
            'status' => 200,
            'message' => 'Teacher Present SMS Updated Successfully'
        ]);

    }



    public function studentPresentSMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

      $sms = PresentSmsSetting::where('type','student')->first();

      if($sms){ $sms->delete();}

       $presentSMS = PresentSmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'student'
        ]);

        return response()->json([
            'data' => $presentSMS,
            'status' => 200,
            'message' => 'Student Present SMS Updated Successfully'
        ]);

    }


    public function absentSMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

      $sms = AbsentSmsSetting::where('type','teacher')->first();

      if($sms){ $sms->delete();}

       $absentsms = AbsentSmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'teacher'
        ]);

        return response()->json([
            'data' => $absentsms,
            'status' => 200,
            'message' => 'Teacher Absent SMS Updated Successfully'
        ]);

    }


    public function studentabsentSMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

        $sms = AbsentSmsSetting::where('type','student')->first();

      if($sms){ $sms->delete();}

       $absentsms = AbsentSmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'student'
        ]);

        return response()->json([
            'data' => $absentsms,
            'status' => 200,
            'message' => 'Student Absent SMS Updated Successfully'
        ]);

    }


    public function delaySMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

      $sms = DelaySmsSetting::where('type','teacher')->first();

      if($sms){ $sms->delete();}

       $delaySMS = DelaySmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'teacher'
        ]);

        return response()->json([
            'data' => $delaySMS,
            'status' => 200,
            'message' => 'Teacher Delay SMS Updated Successfully'
        ]);

    }


    public function studentdelaySMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

      $sms = DelaySmsSetting::where('type','student')->first();

      if($sms){ $sms->delete();}

       $studentdelaySMS = DelaySmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'student'
        ]);

        return response()->json([
            'data' => $studentdelaySMS,
            'status' => 200,
            'message' => 'Student Delay SMS Updated Successfully'
        ]);

    }

    public function earlySMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

      $sms = EarlySmsSetting::where('type','teacher')->first();

      if($sms){ $sms->delete();}

       $earlysms = EarlySmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'teacher'
        ]);

        return response()->json([
            'data' => $earlysms,
            'status' => 200,
            'message' => 'Teacher Early SMS Updated Successfully'
        ]);
    }


    public function studentearlySMS(Request $request)
    {
        $request->validate([
            'content'   => 'required',
        ]);

      $sms = EarlySmsSetting::where('type','student')->first();

      if($sms){ $sms->delete();}

       $earlysms = EarlySmsSetting::updateOrcreate([
            'content'   => $request->content,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'student'
        ]);

        return response()->json([
            'data' => $earlysms,
            'status' => 200,
            'message' => 'Student Early SMS Updated Successfully'
        ]);
    }


    public function studentLeaveSms(Request $request)
    {
        $request->validate([
            'number'   => 'required|min:11|max:11',
        ]);

        $sms = LeavesmsSetting::where('type','student')->first();

       if($sms){ $sms->delete();}

       $studentLeavesms = LeavesmsSetting::updateOrcreate([
            'title'     => 'Student Leave SMS',
            'content'   => $request->content,
            'number'    => $request->number,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'student',
        ]);

        return response()->json([
            'data' => $studentLeavesms,
            'status' => 200,
            'message' => 'Student Leave SMS Updated Successfully'
        ]);
    }


    public function teacherLeaveSms(Request $request)
    {
        $request->validate([
            'number'   => 'required|min:11|max:11',
        ]);

        $sms = LeavesmsSetting::where('type','teacher')->first();

       if($sms){ $sms->delete();}

       $teacherLeavesms = LeavesmsSetting::updateOrcreate([
            'title'     => 'Teacher Leave SMS',
            'content'   => $request->content,
            'number'    => $request->number,
            'status'    => $request->status == 1 ?? 0,
            'type'      => 'teacher',
        ]);

        return response()->json([
            'data' => $teacherLeavesms,
            'status' => 200,
            'message' => 'Teacher Leave SMS Updated Successfully'
        ]);
    }


    public function TeacherApiSms(Request $request)
    {
        $sms = ApiAutoSmsSend::where('type','teacher')->first();

       if($sms){ $sms->delete();}

       $teacherapisms = ApiAutoSmsSend::updateOrcreate([
            'sms_send_time'    => $request->sms_send_time,
            'type'             => 'teacher',
        ]);

        return response()->json([
            'data' => $teacherapisms,
            'status' => 200,
            'message' => 'Teacher Api SMS Time Setting Successfully'
        ]);
    }


    public function StudentApiSms(Request $request)
    {
        $sms = ApiAutoSmsSend::where('type','student')->first();

       if($sms){ $sms->delete();}

       $teacherapisms = ApiAutoSmsSend::updateOrcreate([
            'sms_send_time'    => $request->sms_send_time,
            'type'             => 'student',
        ]);

        return response()->json([
            'data' => $teacherapisms,
            'status' => 200,
            'message' => 'Student Api SMS Time Setting Successfully'
        ]);
    }



    public function resetTemplate($type)
    {
        if($type == 'teacher-present'){
           $presentsms = PresentSmsSetting::where('type','teacher')->first();

           if(isset($presentsms)){
            $presentsms->delete();
           }

            PresentSmsSetting::create([
                'content'   => 'Dear Teacher :Name (:Designation)  Present at  :Time (:Status minutes) , Date : :Date Institute.  Edteco School & College.',
                'type'      => 'teacher'
            ]);

            //notification
            $notification = array(
                'message' =>'Teacher Present Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }


        if($type == 'student-present'){
           $stupresent =  PresentSmsSetting::where('type','student')->first();
           if(isset($stupresent)){
            $stupresent->delete();
           }
           PresentSmsSetting::create([
                'content'   => 'Dear Student :Name Present at  :Time (:Status minutes) , Date : :Date Institute.  Edteco School & College.',
                'type'      => 'student'
            ]);

            //notification
            $notification = array(
                'message' =>'Student Present Sms Template Reset Successfully',
                'alert-type' =>'success'
            );

            return redirect()->back()->with($notification);
        }


        if($type == 'student-absent'){
           $stuabsent =  AbsentSmsSetting::where('type','student')->first();
            if(isset($stuabsent)){
                $stuabsent->delete();
            }

            AbsentSmsSetting::create([
                'content'   => 'Dear Parent, Your Child :Name is Absent Date : :Date Institute.  Edteco School & College.',
                'type'      => 'student'
            ]);

            //notification
            $notification = array(
                'message' =>'Student Absent Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }


        if($type == 'teacher-absent'){
           $teacherabsent = AbsentSmsSetting::where('type','teacher')->first();
            if(isset($teacherabsent)){
                $teacherabsent->delete();
            }
            AbsentSmsSetting::create([
                'content'   => 'Dear Teacher :Name (:Designation), You are Absent Date : :Date Institute.  Edteco School & College.',
                'type'      => 'teacher'
            ]);

            //notification
            $notification = array(
                'message' =>'Teacher Absent Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }

        if($type == 'teacher-delay'){
           $delaysms = DelaySmsSetting::where('type','teacher')->first();

           if(isset($delaysms)){
            $delaysms->delete();
            }

            DelaySmsSetting::create([
                'content'   => 'Dear Teacher :Name (:Designation)  Present at  :Time (Delay : :Delay minutes) , Date : :Date Institute.  Edteco School & College.',
                'type'      => 'teacher'
            ]);

            //notification
            $notification = array(
                'message' =>'Teacher Delay Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }

        if($type == 'student-delay'){
           $studelay = DelaySmsSetting::where('type','student')->first();
            if(isset($studelay)){
                $studelay->delete();
            }

            DelaySmsSetting::create([
                'content'   => 'Dear Parent, Your Child (:Name) Reached Our School at :Time  (Delay : :Delay minutes), Date : :Date EDTECO School',
                'type'      => 'student'
            ]);

            //notification
            $notification = array(
                'message' =>'Student Delay Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }

        if($type == 'teacher-early'){
           $earlysms = EarlySmsSetting::where('type','teacher')->first();
           if(isset($earlysms)){
                $earlysms->delete();
           }

            EarlySmsSetting::create([
                'content'   => 'Dear Teacher :Name (:Designation)  Present at  :Time (Early: :Early minutes) , Date : :Date Institute.  Edteco School & College.',
                'type'      => 'teacher'
            ]);

            //notification
            $notification = array(
                'message' =>'Teacher Early Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }

        if($type == 'student-early'){
           $stuearly = EarlySmsSetting::where('type','student')->first();

           if(isset($stuearly)){
            $stuearly->delete();
            }

            EarlySmsSetting::create([
                'content'   => 'Dear Parent, Your Child (:Name) Reached Our School at :Time  (Early: :Early minutes), Date : :Date EDTECO School',
                'type'      => 'student'
            ]);

            //notification
            $notification = array(
                'message' =>'Student Early Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }

        if($type == 'student-leave'){
           $stuleave = LeavesmsSetting::where('type','student')->first();
           if(isset($stuleave)){
            $stuleave->delete();
           }

           LeavesmsSetting::create([
                'content'   => 'Dear Parent, Your Child :Name. student of Class :Class, Applied for leave for :Days days. EDTECO School',
                'type'      => 'student'
            ]);

            //notification
            $notification = array(
                'message' =>'Student Leave Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }

        if($type == 'teacher-leave'){
           $teacherleave =  LeavesmsSetting::where('type','teacher')->first();

           if(isset($teacherleave)){
                $teacherleave->delete();
           }

           LeavesmsSetting::create([
                'content'   => 'Dear Teacher,:Name you are applied for leave for :Days days. EDTECO School',
                'type'      => 'teacher'
            ]);

            //notification
            $notification = array(
                'message' =>'Teacher Leave Sms Template Reset Successfully',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }
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
