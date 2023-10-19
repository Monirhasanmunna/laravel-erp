<?php

namespace App\Http\Controllers\ExamManagement;

use App\Http\Controllers\Controller;

use App\Helper\Helper;
use App\Models\ClassSubject;
use App\Models\Session;
use App\Models\StudentMarksInput;
use App\Models\StudentMarksInputDetail;
use App\Models\Subject;
use App\Models\SubMarksDist;
use App\Models\SubMarksDistDetail;
use App\Models\SubMarksDistType;
use Illuminate\Http\Request;

class SubMarksDistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academic_years             = Session::all();
        $types                      = SubMarksDistType::all();
        $months                     = Helper::months();
        $subjects                   = Subject::all();
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.marks_distribution.index', compact('academic_years', 'types', 'months', 'subjects'));
    }

    public function getsubjectByClass(Request $request)
    {
        //return $request->all();
        $markDists = SubMarksDist::whereIn('exam_id',$request->exam_id)->where('class_id',$request->class_id)->get();

        $subjectIds = [];
        if($markDists->count() > 0){
            foreach ($markDists as $markDist){
                array_push($subjectIds,$markDist->class_subject_id);
            }
        }

        $array = array_unique($subjectIds);

        $subjects = ClassSubject::with('subject')
                    ->whereNotIn('id',$array)
                    ->where('ins_class_id', $request->class_id)
                    ->get();


        return $subjects;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years             = Session::all();
        $types                      = SubMarksDistType::all();
        $months                     = Helper::months();
        $subjects                   = Subject::all();
        return view('template1.exammanagement.marks_distribution.create', compact('academic_years', 'types', 'months', 'subjects'));
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

        foreach ($request->exam_id as $examId){

            foreach ($request->subject_id as $key => $subjectId) {
                $subMarkDist =  SubMarksDist::create([
                    'class_subject_id' => $subjectId,
                    'class_id'   => $request->class_id,
                    'exam_id'   => $examId,
                    'total_mark' => $request->total_mark[$key],
                    'pass_mark'  => $request->pass_mark[$key],
                    'take'       => $request->take[$key],
                    'grace'      => $request->grace[$key],
                ]);
                //store sub mark details
                $distTypeId = 'dist_type_id' . '_' . $subjectId;
                $mark       = 'mark' . '-' . $subjectId;
                $passMark   = 'pass_mark' . '-' . $subjectId;



                foreach ($request->$distTypeId as $key => $distTypeId) {
                    SubMarksDistDetail::create([
                        'sub_marks_dist_id'      => $subMarkDist->id,
                        'sub_marks_dist_type_id' => $distTypeId,
                        'mark'                   => $request->$mark[$key],
                        'pass_mark'              => $request->$passMark[$key],
                    ]);
                }
            }
        }


        //notification
        $notification = array(
            'message' => 'Mark Distribute Successfully ',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubMarksDist  $subMarksDist
     * @return \Illuminate\Http\Response
     */
    public function show(SubMarksDist $subMarksDist)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubMarksDist  $subMarksDist
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $markDist = SubMarksDist::with('classSubject','details')->find($id);
        $types    = SubMarksDistType::all();
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.marks_distribution.edit',compact('markDist','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubMarksDist  $subMarksDist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $request->all();
        $markDist = SubMarksDist::find($id);

        $markDist->update([
            'total_mark' => $request->total_mark,
            'pass_mark'  => $request->pass_mark,
            'take'       => $request->take,
            'grace'      => $request->grace,
        ]);
        foreach ($markDist->details as $detail){
            $marksInputDetails = StudentMarksInputDetail::where('sub_marks_dist_details_id',$detail->id)->get();

            if($marksInputDetails->count() > 0){

                $marksInput = StudentMarksInput::find($marksInputDetails[0]->student_marks_input_id);
                if($marksInput){
                    $marksInput->delete();
                }
            }

            StudentMarksInputDetail::where('sub_marks_dist_details_id',$detail->id)->delete();

            $detail->delete();
        }

        foreach ($request->mark_dist_type_id as $key => $distTypeId){
            SubMarksDistDetail::create([
               'sub_marks_dist_id'       =>  $markDist->id,
               'sub_marks_dist_type_id'  =>  $distTypeId,
               'mark'                    =>  $request->marks[$key],
               'pass_mark'               =>  $request->pass_marks[$key],
            ]);
        }
        //notification
        $notification = array(
            'message' => 'Mark Dist Update Successfully ',
            'alert-type' => 'success'
        );
        return redirect()->route('exam-management.setting.marks-dist.index')->with($notification);
    }





}
