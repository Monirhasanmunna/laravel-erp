<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\InsClass;
use App\Models\Section;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\TeacherSectionAssign;
use Illuminate\Http\Request;

class TeacherSecAssignController extends Controller
{
    public function index()
    {
        return view('template1.accountsmanagement.teacher-assign.index');
    }


    public function create()
    {
        $teachers = Teacher::where('type', 'teacher')->get();
        $sessions = Session::all();
        return view('template1.accountsmanagement.teacher-assign.create', compact('teachers', 'sessions'));
    }


    public function store(Request $request)
    {
        if($request->section_id){

            TeacherSectionAssign::where('teacher_id',$request->teacher_id)->where('session_id',$request->session_id)->delete();

            foreach($request->section_id as $sectionId){
                TeacherSectionAssign::updateOrCreate([
                    'section_id' => $sectionId
                ],[
                    'teacher_id' => $request->teacher_id,
                    'session_id' => $request->session_id,
                    'section_id' => $sectionId
                ]);
            }
        }
        else{
               //notification
                $notification = array(
                    'message' =>'Please Select Section',
                    'alert-type' =>'error'
                );
                return redirect()->back()->with($notification);
        }

        //notification
        $notification = array(
            'message' =>'Teacher Assign Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function getSections(Request $request)
    {
        $session  = Session::find($request->session_id);
        $classIds = $session->classes->pluck('id');
        $classes  = InsClass::with('sections', 'sections.shift', 'sections.class')->whereIn('id', $classIds)->get()->chunk(4);

        return response()->json($classes);
    }


    public function getSelectedSections(Request $request){
        $sections = TeacherSectionAssign::where('teacher_id',$request->teacher_id)->where('session_id',$request->session_id)->get()->pluck('section_id');
        return $sections;
    }
}
