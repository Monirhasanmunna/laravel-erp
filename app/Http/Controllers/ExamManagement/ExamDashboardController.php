<?php

namespace App\Http\Controllers\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\Student;
use App\Models\Section;
use App\Models\ClassSubject;
use App\Models\InsClass;
use App\Models\StudentMarksInput;
use App\Models\StudentMarksInputDetail;
use App\Models\StudentSubjectAssign;
use App\Models\Subject;
use App\Models\SubMarksDist;
use App\Models\Exam;
use App\Models\GeneralGrade;


class ExamDashboardController extends Controller
{
    public function index(){
        $academic_years = Session::all();
        $result = "";
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.dashboard.index',compact('academic_years', 'result'));
    }

    public function dashboardResult(Request $request)
    {
        $academic_years = Session::all();
        $exam = Exam::find($request->exam_id);

        $section = Section::where('id', $request->section_id)->firstOrFail();
       $students = Student::where('session_id', $request->academic_year_id)
                            ->where('section_id', $request->section_id)
                            ->where('category_id', $request->category_id)
                            ->where('group_id', $request->group_id)
                            ->get();
        $subjects = ClassSubject::where('ins_class_id', $section->class->id)->get();

        $result = StudentMarksInput::with('details')
            ->where('exam_id', $request->exam_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->get();

        $grades = GeneralGrade::where('ins_class_id', $section->class->id)
                      ->get();
       
            if ($result->isEmpty()) {
                //notification
    $notification = array(
        'message' =>'No Data Found.',
        'alert-type' =>'success'
    );
    return  redirect()->route('exam-management.dashboard.index')->with($notification);
            }else{

        return view($this->backendTemplate['template']['path_name'].'.exammanagement.dashboard.index',compact('academic_years', 'result', 'students', 'subjects', 'section', 'exam', 'grades'));
    }
    }
}
