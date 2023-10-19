<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\SmsHistory;
use Illuminate\Http\Request;

class SmsReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.smsreport.index');
    }


    public function dateToDateReport(){
        return view('template1.smsmanagement.report.date-to-date');
    }

    public function getReports(Request $request){
        $histories =  SmsHistory::whereDate('time','>=',$request->from_date)->whereDate('time','<=',$request->to_date)->get();
        return response()->json($histories);
    }
}
