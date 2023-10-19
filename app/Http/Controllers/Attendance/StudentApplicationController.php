<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;

class StudentApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = LeaveApplication::where('source_type','App\Models\StudentUser')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.leavemanagement.student_application.index',compact('applications'));
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


    public function getApplication($id)
    {
        $data = [];
        $data['application'] = LeaveApplication::find($id);
        $data['student'] = LeaveApplication::find($id)->source->student->with('section','ins_class','group')->first();

        return $data;
    }


    public function approvedApplication()
    {
        $applications = LeaveApplication::where('source_type','App\Models\StudentUser')->where('status','approve')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.leavemanagement.approvelist.index',compact('studentapplications'));
    }

    public function rejectedApplication()
    {
        $applications = LeaveApplication::where('source_type','App\Models\StudentUser')->where('status','reject')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.leavemanagement.rejectlist.index',compact('studentapplications'));
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
    public function update($type, $id)
    {
        if($type == 'approve'){
            LeaveApplication::find($id)->update([
                'status'        => 'approve',
                'approved_date' =>  date('Y,m,d'),
            ]);

            $notification = array(
                'message' =>' Application Approved ',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }else{
            LeaveApplication::find($id)->update([
                'status'        => 'reject',
                'approved_date' =>  date('Y,m,d'),
            ]);

            $notification = array(
                'message' =>' Application Rejected ',
                'alert-type' =>'success'
            );
            return redirect()->back()->with($notification);
        }
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
