<?php

namespace App\Http\Controllers\OnlineAdmission;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\ClassSubject;
use App\Models\InsClass;
use App\Models\Institution;
use App\Models\OnlineAdmission;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentSubjectAssign;
use App\Models\StudentUser;
use Illuminate\Http\Request;
use App\Traits\FileSaver;
use Illuminate\Support\Facades\Hash;
use App\Services\Admission\AdmissionSmsServices;

class OnlineAdmissionController extends Controller
{
    use FileSaver;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->backendTemplate['template']['path_name'].'.onlineadmission.subject.index');
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
    public function store(Request $request, AdmissionSmsServices $admissionsms)
    {
       $admission = new OnlineAdmission();

       if(@$request['image']){
        //dd("ok");
            $this->uploadFileLinode($request['image'],$admission,'image','onlineAdmission');
        }

      // return Helper::onlineAdmissionId($request['session_id'], $request['class_id']);
       $admission->admission_no     = Helper::onlineAdmissionId($request['session_id'], $request['class_id']);
       $admission->session_id       = $request->session_id;
       $admission->class_id         = $request->class_id;
       $admission->division_id      = $request->division_id;
       $admission->district_id      = $request->district_id;
       $admission->upazila_id       = $request->upazila_id;
       $admission->name             = $request->name;
       $admission->father_name      = $request->father_name;
       $admission->mother_name      = $request->mother_name;
       $admission->date_of_birth    = $request->date_of_birth;
       $admission->mobile_number    = $request->mobile_number;
       $admission->religion         = $request->religion;
       $admission->gender           = $request->gender;
       $admission->blood_group      = $request->blood_group;
       $admission->save();

       //sms send service
       $admissionsms->admissionSubmitSms($admission);
        //notification
        $notification = array(
            'message' => 'Successfully Completed Your Admission',
            'alert-type' => 'success'
        );
       return redirect()->back()->with($notification);
       
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
