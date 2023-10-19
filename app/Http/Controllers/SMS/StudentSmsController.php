<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\SmsTemplates;
use App\Models\Student;
use App\Models\StudentSms;
use Illuminate\Http\Request;

class StudentSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates      = SmsTemplates::all();
        $sessions   = Session::all();
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.student-sms.index',compact('templates','sessions'));
    }

    public function getClass($id)
    {
        return InsClass::where('session_id',$id)->get();
    }

    public function getStudents($type,$id)
    {
        $session_id = '';
        $class_id   = '';

        if('session_id' == $type && 'class_id' != $type){
            $session_id = $id;
        }else{
            $class_id = $id;
        }

        $query = Student::with('ins_class', 'section','shift', 'group', 'category');

        if($session_id){
            $query->where('session_id',$session_id);
        }else{
            $query->where('class_id',$class_id);
        }

        $students = $query->get();

        return response()->json($students);
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
        $sms = StudentSms::find($id);
        $sms->delete();

        //notification
        $notification = array(
            'message' =>'SMS Delete Successfully',
            'alert-type' =>'success'
        );

        return redirect()->back()->with($notification);
    }
}
