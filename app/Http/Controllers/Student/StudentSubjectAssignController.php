<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ClassSubject;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\Section;
use App\Models\Session as StdSession;
use App\Models\Student;
use App\Models\StudentSubjectAssign;
use App\Models\SubjectType;
use Illuminate\Http\Request;

class StudentSubjectAssignController extends Controller
{
    public  function  index(){
        $academic_years  = StdSession::all();
        $subjectTypes    = SubjectType::get();
        $backendTemplate = $this->backendTemplate['template']['path_name'] ;

        return view($backendTemplate.'.student.subject-assign.index',compact('academic_years','subjectTypes'));
    }

    public  function  create(){
        $academic_years  = StdSession::all();
        $subjectTypes    = SubjectType::all();
        $backendTemplate = $this->backendTemplate['template']['path_name'] ;
        return view($backendTemplate.'.student.subject-assign.create',compact('academic_years','subjectTypes'));
    }

    public function store(Request $request){

        $data =  $request->all();

        $resultArray = array_filter($data, function($key) {
            return strpos($key, 'class_subject_id-') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $studentIds = [];
        foreach ($resultArray as $key => $array){
            array_push($studentIds,trim($key,'class_subject_id-'));
        }

        foreach ($studentIds as $studentId){
            $req = 'class_subject_id-'.$studentId;

            foreach ($request->$req as $classSubjectId){
                $classSubject = ClassSubject::find($classSubjectId);
                StudentSubjectAssign::updateOrCreate([
                    'student_id' => $studentId,
                    'class_subject_id' => $classSubjectId
                ],[
                    'student_id'        =>   $studentId,
                    'class_subject_id'  =>   $classSubjectId,
                    'subject_type_id'   =>   $classSubject->subject_type_id
                ]);
            }
        }

        //notification
        $notification = array(
            'message' =>'Data Save Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }


    public function print($data){

        $requests = explode('&', $data);
        $request = [];
        foreach ($requests as $requ) {
            $exReq = explode('=', $requ);
            $request[$exReq[0]] = $exReq[1];
        }

        $subjectTypes   = SubjectType::all();


        $class    = InsClass::find($request['class_id']);
        $section  = Section::find($request['section_id']);
        $category = Category::find($request['category_id']);
        $group    = Group::find($request['group_id']);

        $title = [
                    'class'    => $class->name.'-'.$section->shift->name.'-'.$section->name,
                    'category' => $category->name,
                    'group'    => $group->name,
                ];

    
        $array = json_encode($request);

        return view('template1.student.subject-assign.print',compact('subjectTypes','title','array'));
    }


    public function bulk()
    {
        $academic_years = StdSession::all();
        $subjectTypes   = SubjectType::all();
        return view('template1.student.subject-assign.bulk', compact('academic_years','subjectTypes'));
    }


    public function bulkAction(Request $request){
      
        //return $request->all();

        if($request->student_id && $request->class_sub_id ){
            foreach($request->student_id as $studentId){
                foreach($request->class_sub_id as $classSubId){
                    $stdSubject = StudentSubjectAssign::where('student_id',$studentId)->where('class_subject_id',$classSubId)->first();
                    if(!$stdSubject){
                        $classSub = ClassSubject::find($classSubId);
                        StudentSubjectAssign::create([
                            'student_id'       => $studentId,
                            'class_subject_id' => $classSubId,
                            'subject_type_id'  => $classSub->subject_type_id
                        ]);
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
            'message' =>'Subject Assigned Successfully',
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
