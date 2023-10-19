<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $sessions = Session::all();
        $currentYear = date('Y');
        return view($this->backendTemplate['template']['path_name'].'.student.report.index',compact('sessions','currentYear'));
    }

    public function getReport($session)
    {
        $data = [];
        $data['classes'] = InsClass::with(['categories','shifts','shifts.sections','shifts.groups.students'])->where('session_id',$session)->get();
        $data['totalMale'] = Student::where('gender','male')->get()->count();
        $data['totalFemale'] = Student::where('gender','female')->get()->count();
        $data['total'] = Student::all()->count();
        return $data;
    }

    public function ExportPdf($session)
    { 
        $data = $this->getReport($session);
        // $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.student.report.report-pdf', compact('data'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        // $pdf->setPaper('a4');
        // return $pdf->download('student-' . $data['total'] . '-report.pdf');
        return view($this->backendTemplate['template']['path_name'].'.student.report.report-print', compact('data'));
    }
}
