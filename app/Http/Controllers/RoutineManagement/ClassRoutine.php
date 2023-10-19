<?php

namespace App\Http\Controllers\RoutineManagement;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\PeriodTime;
use App\Models\RoutinePeriodTimeSetting;
use App\Models\Section;
use App\Models\Session;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoutine as Routine;
use App\Models\ClassRoutine as ModelsClassRoutine;
use App\Models\ClassRoutineDetail;
use App\Models\Period;
use App\Models\RoutinePeriodTimeSettingDetail;
use Illuminate\Http\Request;

class ClassRoutine extends Controller
{



    public function index(Request $request)
    {
        //dd($request->all());
        $academic_years = Session::all();

        $data = $request->all();
        if($data){
            $matched = [
                'class_id'    => $request->class_id,
                'section_id'  => $request->section_id,
                'category_id' => $request->category_id,
                'group_id'    => $request->group_id,
            ];
    
            $data['routines'] = ModelsClassRoutine::with('details', 'details.periodTime', 'details.teacher', 'details.classSubject.subject')
                                ->where($matched)
                                ->get()
                                ->map(function ($item) {
                                    return [
                                        'day' => $item->day,
                                        'periods' => $this->getPeriods($item)
                                    ];
                                });
        }

        $data['periods'] = Period::all();
    

        return view('template1.routinemanagement.classroutine.index', compact('academic_years', 'data'));
    }


    public function getPeriods($routines)
    {

        $periodIds   =  Period::get()->pluck('id')->toArray();
        $hasPeriod = [];

        foreach ($routines->details->toArray() as $key => $detail) {
            array_push($hasPeriod,$detail['period_time']['period_id']);
        }

        $notMatched = array_diff($periodIds, $hasPeriod);
        $notMatchedKeys = array_keys($notMatched);

        $finalArr = [];
        foreach ($routines->details->toArray() as $key1 => $detail) {
                array_push($finalArr,$detail);
        }


        foreach ($notMatchedKeys as $index) {
            array_splice($finalArr, $index, 0, [null]);
        }

        return $finalArr;
    }



    public function create()
    {
        $timeSettings   = RoutinePeriodTimeSetting::with('details')->where(['section_id' => 4, 'day' => "Saturday"])->first();
        $days           = Helper::getDays();
        $academic_years = Session::all();
        return view('template1.routinemanagement.classroutine.create', compact("days", 'timeSettings', 'academic_years'));
    }

    public function getSubjects(Request $request)
    {
        //  return $request->all();
        $class = InsClass::with('subjects')->find($request->class_id)->toArray();
        return $class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();

        $search = $request->search;
        $searchQuery = [];
        parse_str($search, $searchQuery);

        $classRoutine = ModelsClassRoutine::create($searchQuery);

        //insert details
        foreach ($request->details_id as $key => $detailId) {

            ClassRoutineDetail::create([
                'class_routine_id'       => $classRoutine->id,
                'period_time_details_id' => $detailId,
                'class_subject_id'       => $request->subject_id[$key],
                'teacher_id'             => $request->teacher_id[$key],
            ]);
        }

        // //notification
        $notification = array(
            'message' => 'Routine Create Successfully ',
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }


    public function print(Request $request){

        $data = json_decode(stripslashes($request['data']),true);
       
        if($data){

            $matched = [
                'class_id'    => $data['class_id'],
                'section_id'  => $data['section_id'],
                'category_id' => $data['category_id'],
                'group_id'    => $data['group_id'],
            ];
    
            $data['routines'] = ModelsClassRoutine::with('details', 'details.periodTime', 'details.teacher', 'details.classSubject.subject')
                                ->where($matched)
                                ->get()
                                ->map(function ($item) {
                                    return [
                                        'day' => $item->day,
                                        'periods' => $this->getPeriods($item)
                                    ];
                                });
        }

        $data['periods'] = Period::all();

        $class = InsClass::find($data['class_id']);
        $data['session']  = Session::find($class->session_id)->title;
        $section = Section::find($data['section_id']);
        $data['class']    = $section->class->name.'-'.$section->shift->name.'-'.$section->name;

        $data['category'] = Category::find($data['category_id'])->name;
        $data['group']    = Group::find($data['group_id'])->name;

        return view('template1.routinemanagement.classroutine.print', compact('data'));
    }

    public function getTimeSettings(Request $request)
    {

        $data = $request->all();

        $marched = [
            'session_id'  => $data['academic_year_id'],
            'section_id'  => $data['section_id'],
            'category_id' => $data['category_id'],
            'group_id'    => $data['group_id'],
            'day'         => $data['day']
        ];

        $data['timeSettings'] = RoutinePeriodTimeSetting::with('details', 'details.period')->where($marched)->first();

        $data['subjects'] = ClassSubject::with('subject')->where('ins_class_id', $data['class_id'])->get();
        $data['teachers'] = Teacher::where('type', 'teacher')->get();

        return $data;
    }

    public function getRoutine(Request $request)
    {
        $data = $request->all();

        $matched = [
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'category_id' => $data['category_id'],
            'group_id' => $data['group_id'],
        ];

        $routines = ModelsClassRoutine::where($matched)->get();
        return $routines;
    }



    public function getTeacherInfo(Request $request){

        $searchInfo = array();
        parse_str($request->search_info, $searchInfo);


        $matched = [
            'day'         => $searchInfo['day']
        ];

        $teacher = Teacher::find($request->teacher_id);

        $classRoutines = ClassRoutineDetail::where('teacher_id',$request->teacher_id)
                        ->with('classRoutine','periodTime','periodTime.period')
                        ->whereHas('classRoutine',function($q) use ($matched){
                            $q->where($matched);
                        })
                        ->get()
                        ->map(function($item) use($teacher){
                            return [
                                'class'    => $item->classRoutine->class->name.'-'.$item->classRoutine->section->shift->name.'-'.$item->classRoutine->section->name,
                                'category' => $item->classRoutine->category->name,
                                'group'    => $item->classRoutine->group->name,
                                'day'      => $item->classRoutine->day,
                                'period'   => $item->periodTime->period->name,
                                'teacher'  => $teacher->name,
                            ];
                        });
                                        
       return $classRoutines; 

     

    }
}
