<?php

namespace App\Http\Controllers\RoutineManagement;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamRoutine as ModelsExamRoutine;
use App\Models\ExamRoutineClassSubject;
use App\Models\ExamRoutineInstruction;
use App\Models\ExamRoutineSubject;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\Subject;
use App\Rules\ExamRoutineRule;
use Illuminate\Http\Request;

class ExamRoutine extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $routines = ModelsExamRoutine::with('classSubjects')->get();
        return view($this->backendTemplate['template']['path_name'].'.routinemanagement.examroutine.index',compact('routines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Session::all();
        $classes        = InsClass::all();
        $exams          = Exam::all();
        return view($this->backendTemplate['template']['path_name'].'.routinemanagement.examroutine.create',compact('academic_years','classes','exams'));
    }


    public function getGroup($id)
    {
        $groups = Group::where('ins_class_id',$id)->get();
        return response()->json($groups);
    }

    public function getSubjects(Request $request)
    {
        $data = $request->all();
        $subjects = ClassSubject::with('subject')->where('ins_class_id',$data['class_id'])->get();

        $items = [];
        $items['subjects'] = $subjects->toArray();
        $items['class_id'] = $data;
        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
       // dd($data);
        
        $exam = ModelsExamRoutine::create([
            "ins_class_id" =>  $data['class_id'],
            "session_id"   =>  $data['session_id'],
            "exam_id"      =>  $data['exam_id'],
            "start_date"   =>  $data['st_date'],
            "end_date"     =>  $data['ed_date'],
            "start_time"   =>  $data['exam_start_time'],
            "end_time"     =>  $data['exam_end_time'],
            "instruction"  =>  $data['instruction']    
        ]);

        foreach ($data['subject_id'] as $key => $value) {
            $sub = $exam->classSubjects()->attach($value,[
                'exam_routine_id' => $exam->id,
                'class_subject_id' => $value,
                'date' => $data['date'][$key],
                'room' => $data['room'][$key],
                'start_time' => $data['start_time'][$key],
                'end_time' => $data['end_time'][$key]
            ]);
        }

        return redirect()->route('examroutine.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $routine =  ModelsExamRoutine::find($id);
        return view($this->backendTemplate['template']['path_name'].'.routinemanagement.examroutine.view',compact('routine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $routine        =  ModelsExamRoutine::with('classSubjects')->find($id);
        $academic_years =  Session::all();
        $classes        =  InsClass::all();
        $exams          =  Exam::all();
        $classSubjects  =  ClassSubject::where('ins_class_id',$routine->ins_class_id)->get();
        return view($this->backendTemplate['template']['path_name'].'.routinemanagement.examroutine.edit',compact('routine','academic_years','classes','exams','classSubjects'));
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
        $data = $request->all();

        $exam = ModelsExamRoutine::find($id);
        $exam->ins_class_id = $data['class_id'];
        $exam->session_id   = $data['academic_year_id'];
        $exam->exam_id      = $data['exam_id'];
        $exam->start_date   = $data['st_date'];
        $exam->end_date     = $data['ed_date'];
        $exam->instruction  = $data['instruction'];
        $exam->save();

        $exam->classSubjects()->detach();

       // return $data['subject_id'];

        foreach ($data['subject_id'] as $key => $value) {
                $exam->classSubjects()->attach($value,[
                'exam_routine_id' => $exam->id,
                'class_subject_id' => $value,
                'date' => $data['date'][$key],
                'room' => $data['room'][$key],
                'start_time' => $data['start_time'][$key],
                'end_time' => $data['end_time'][$key]
            ]);
        }

        //notification
        $notification = array(
            'message' =>'Routine Update Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('examroutine.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $routine = ModelsExamRoutine::find($id);
        $routine->classSubjects()->detach();
        $routine->delete();

        return redirect()->route('examroutine.index');
    }
}
