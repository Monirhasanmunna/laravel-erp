<?php

namespace App\Http\Controllers\ReportManagement;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StudentListReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions = Session::all();
        $currentYear = date('Y');
        return view($this->backendTemplate['template']['path_name'].'.reportmanagement.studentlist.index',compact('sessions','currentYear'));
    }

    public function getStudentInfoBySessionId($session)
    {
        $classes = InsClass::where('session_id',$session)->get();
        $classes = $classes->map(function($item){
            return [
                'className' => $item->name,
                'totalGroup'=> count($item->groups),
                'totalCategory'=> count($item->categories),
                'male'      => Student::where('gender','male')->where('class_id',$item->id)->get()->count(),
                'female'    => Student::where('gender','female')->where('class_id',$item->id)->get()->count(),
                'total'     => Student::where('class_id',$item->id)->get()->count()
            ];
        });

        return $classes;
    }


    public function ExportPdf($session)
    { 
        $classes = $this->getStudentInfoBySessionId($session);
        $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.reportmanagement.studentlist.report-pdf', compact('classes'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->setPaper('a4');
        return $pdf->download('student-' . $classes->count() . '-report.pdf');
        // return view($this->backendTemplate['template']['path_name'].'.reportmanagement.studentlist.view', compact('classes'));
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
