<?php

namespace App\Http\Controllers\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ClassSubject;
use App\Models\InsClass;
use App\Models\StudentMarksInput;
use App\Models\StudentMarksInputDetail;
use App\Models\StudentSubjectAssign;
use App\Models\Subject;
use App\Models\SubMarksDist;
use App\Models\SubMarksDistType;
use App\Models\GeneralGrade;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academic_years = Session::all();
        $sections       = Section::all();
        $exams          = Exam::all();
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.transcript.index', compact('academic_years', 'sections','exams'));
    }

    public function view($id,$exam_id)
    {
        $student = Student::findOrfail($id);
        $exam    = Exam::findOrfail($exam_id);
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.transcript.view',compact('student','exam'));
    }

    public function transcriptResult(Request $request)
    {
        $academic_years = Session::all();
        $marks_dis_type = SubMarksDistType::all();
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
    return  redirect()->route('exam-management.report.transcript.index')->with($notification);
            }else{
        
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.transcript.view',compact('academic_years', 'result', 'students', 'subjects', 'section', 'exam', 'grades', 'marks_dis_type'));
    }

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
