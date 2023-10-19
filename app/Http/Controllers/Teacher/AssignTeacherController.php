<?php


namespace App\Http\Controllers\Teacher;


use App\Http\Controllers\Controller;

use App\Helper\Helper;
use App\Models\{AssignTeacher, AssignTeacherSbuject, AssignTeacherSubject, Category, ClassSubject, Group, Session, InsClass, Section, Subject, Teacher};
use Illuminate\Http\Request;
use DB;

class AssignTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assign_teacher = AssignTeacher::with(['teacher', 'ins_class', 'shift', 'section'])->latest()->get();

        $teachers = Teacher::where('type', 'teacher')->get();

        $sections  = Section::with('class', 'shift')->where('institute_id', Helper::getInstituteId())->where('institute_branch_id', Helper::getBranchId())->get();
        // $secName = [];

        // foreach($sections as $section){
        //     if($section->students){
        //         array_push($secName,$section->id);
        //     }
        // }
        // dd($secName);
        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.assign_teacher', [
            'sections' => $sections,
            'teachers' => $teachers,
            'assign_teacher' => $assign_teacher,
        ]);
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
        $data['teacher_id'] = $request->teacher_id;
        $data['section_id'] = $request->section_id;
        $section = Section::find($data['section_id']);

        $data['class_id']   = $section->ins_class_id;
        $data['shift_id']   = $section->shift_id;

        $exists = AssignTeacher::where('teacher_id', $data['teacher_id'])->orWhere('section_id', $data['section_id'])->get();

        if ($exists->count() > 0) {

            $notification = array(
                'message' => 'Teacher Already Assigned',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {
            AssignTeacher::create($data);
        }

        //notification
        $notification = array(
            'message' => 'Teacher Assigned',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssignTeacher  $assignTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(AssignTeacher $assignTeacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssignTeacher  $assignTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(AssignTeacher $assignTeacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssignTeacher  $assignTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssignTeacher $assignTeacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssignTeacher  $assignTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssignTeacher $assignTeacher)
    {
        //
    }


    public function subject()
    {

        $teachersId = AssignTeacherSubject::whereHas('teacher', function ($q) {
            $q->where('institute_id', Helper::getInstituteId())->where('institute_branch_id', Helper::getBranchId());
        })->get()->pluck('teacher_id');
        $teachersId = array_unique($teachersId->toArray());

        $teachers = Teacher::whereIn('id', $teachersId)
            ->get()
            ->map(function ($item) {
                return [
                    'id'   => $item->id,
                    'name' => $item->name,
                    'subjects' => $this->getAssignedSubjects($item)
                ];
            });

        return view($this->backendTemplate['template']['path_name'] . '.teachers.assign-subject.index', compact('teachers'));
    }

    public function getAssignedSubjects($teacher)
    {

        $classSubIds = AssignTeacherSubject::where('teacher_id', $teacher->id)->get()->pluck('class_subject_id');

        $classSubjects = ClassSubject::with('subject')->whereIn('id', $classSubIds)->get()->groupBy('ins_class_id');

        $array = [];
        foreach ($classSubjects as $key => $classSub) {
            $className = InsClass::find($key)->name;
            $array[$className] = "";
            foreach ($classSub as $key =>  $sub) {
                $arrCount = count($classSub) - 1;
                if($arrCount == $key){
                    $array[$className] .= $sub->subject->sub_name;
                }
                else{
                    $array[$className] .= $sub->subject->sub_name.", ";
                }
            }
        }

     
        return $array;
    }


    public function subjectAssign()
    {

        $teachers          = Teacher::where('type', 'teacher')->get();
        $sessions          = Session::all();
        return view($this->backendTemplate['template']['path_name'] . '.teachers.assign-subject.create', compact('teachers', 'sessions'));
    }

    public function subjectStore(Request $request)
    {
        if (!$request->subjects) {
            //notification
            $notification = array(
                'message' => 'Subject Not Select',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } else {
            foreach ($request->subjects as $subjectId) {
                AssignTeacherSubject::create([
                    'teacher_id' => $request->teacher_id,
                    'class_subject_id' => $subjectId
                ]);
            }

            //notification
            $notification = array(
                'message' => 'Assign Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }
    }



    public function getSubjects(Request $request)
    {
        $assignSubjects = AssignTeacherSubject::where('teacher_id', $request->teacher_id)->pluck('class_subject_id');
        $classSubjects  = ClassSubject::whereNotIn('id', $assignSubjects)->with('subject')->where('ins_class_id', $request->class_id)->get()->chunk(3);
        return $classSubjects;
    }
}
