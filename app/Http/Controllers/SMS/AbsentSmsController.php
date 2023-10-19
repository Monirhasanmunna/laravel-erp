<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;

class AbsentSmsController extends Controller
{
    public function index(){
        $academic_years = Session::all();
        return view('template1.smsmanagement.absent-sms.student.index',compact('academic_years'));
    }
}
