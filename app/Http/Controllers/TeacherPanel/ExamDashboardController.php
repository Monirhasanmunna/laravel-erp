<?php

namespace App\Http\Controllers\TeacherPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamDashboardController extends Controller
{
    public function index(){
        return view('teacherpanel.exam-management.dashboard.index');
    }
}
