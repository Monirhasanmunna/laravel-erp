<?php

namespace App\Http\Controllers\TeacherPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountDashboardController extends Controller
{
    public function index(){
        return view('teacherpanel.account-management.dashboard.index');
    }
}
