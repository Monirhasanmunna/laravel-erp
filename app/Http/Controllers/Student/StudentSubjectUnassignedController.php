<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\Session as StdSession;
use App\Models\Student;
use App\Models\StudentSubjectAssign;
use App\Models\SubjectType;
use Illuminate\Http\Request;

class StudentSubjectUnassignedController extends Controller
{
    public function index()
    {
        $academic_years  = StdSession::all();
        $subjectTypes    = SubjectType::get();
        $backendTemplate = $this->backendTemplate['template']['path_name'];
        return view($backendTemplate . '.student.subject-unassigned.index', compact('academic_years', 'subjectTypes'));
    }


    public function bulk()
    {
        $academic_years = StdSession::all();
        $subjectTypes   = SubjectType::all();
        return view('template1.student.subject-unassigned.bulk', compact('academic_years','subjectTypes'));
    }


    public function bulkAction(Request $request){
        //return $request->all();

        if($request->student_id && $request->class_sub_id ){
            foreach($request->student_id as $studentId){
                foreach($request->class_sub_id as $classSubId){
                    $stdSubject = StudentSubjectAssign::where('student_id',$studentId)->where('class_subject_id',$classSubId)->first();
                    if($stdSubject){
                        $stdSubject->delete();
                    }
                }
            }
        }
        else{
               //notification
                $notification = array(
                    'message' =>'Please Select Subject',
                    'alert-type' =>'error'
                );
                return redirect()->back()->with($notification);
        }

        //notification
        $notification = array(
            'message' =>'Subject Unassigned Successfully',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function getStudents(Request $request)
    {
        $matched = [
                        'session_id'  => $request->academic_year_id,
                        'class_id'    => $request->class_id,
                        'shift_id'    => $request->shift_id,
                        'category_id' => $request->category_id,
                        'group_id'    => $request->group_id,
                        'section_id'  => $request->section_id,
                    ];
        
        $students = Student::where($matched)->get(['id','name','id_no','roll_no'])->chunk(2);
        
        return $students;
    }


    public function getSubjects(Request $request){

        $matched = [
                        'ins_class_id'    => $request->class_id,
                        'subject_type_id' => $request->type_id
                    ];
        $classSubjects = ClassSubject::with('subject')->where($matched)->get()->chunk(2);

        return $classSubjects;
    }
}
