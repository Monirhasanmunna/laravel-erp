<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\InstituteBranch;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       

        $branch = InstituteBranch::find(Session::get('branch_id'));
        $seller = Institution::with('seller','seller.district','seller.upazila')->find(Helper::getInstituteId());
      
        return view('home',compact('branch','seller'));
    }
}
