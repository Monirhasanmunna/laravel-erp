<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\StudentLeaveapplication;
use App\Models\TeacherLeaveaplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    public function approvedApplication()
    {
        $studentapplications = LeaveApplication::where('source_type','App\Models\StudentUser')->where('status','approve')->get();
        $teacherapplications = LeaveApplication::where('source_type','App\Models\TeacherUser')->where('status','approve')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.leavemanagement.approvelist.index',compact('teacherapplications','studentapplications'));
    }

    public function rejectedApplication()
    {
        $studentapplications = LeaveApplication::where('source_type','App\Models\StudentUser')->where('status','reject')->get();
        $teacherapplications = LeaveApplication::where('source_type','App\Models\TeacherUser')->where('status','reject')->get();
        return view($this->backendTemplate['template']['path_name'].'.attendance.leavemanagement.rejectlist.index',compact('teacherapplications','studentapplications'));
    }

}
