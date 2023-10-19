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


class TabulationSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academic_years = Session::all();
        $result          = "";
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.tabulationsheet.index',compact('academic_years', 'result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function tabulationResult(Request $request)
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
    return  redirect()->route('exam-management.report.tabulation-sheet.index')->with($notification);
            }else{

        return view($this->backendTemplate['template']['path_name'].'.exammanagement.tabulationsheet.index',compact('academic_years', 'result', 'students', 'subjects', 'section', 'exam', 'grades'));
    }
    }


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
