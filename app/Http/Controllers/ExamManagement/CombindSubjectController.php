<?php

namespace App\Http\Controllers\ExamManagement;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\CombindSubject;
use App\Models\Session;
use App\Models\SubMarksDist;
use App\Services\Exams\CombindSubjectService;
use Illuminate\Http\Request;

class CombindSubjectController extends Controller
{
    public $service;

    public function __construct(CombindSubjectService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $combinedSubjects = CombindSubject::with('class','exam','subject1.subject')
        ->whereHas('class',function($query){
            $query->where('institute_id',Helper::getInstituteId())->where('institute_branch_id',Helper::getBranchId());;
        })
        ->get();
        return view('template1.exammanagement.combind-subject.index',compact('combinedSubjects'));
    }
    public function create()
    {
        $academic_years = Session::all();
        return view('template1.exammanagement.combind-subject.create',compact('academic_years'));
    }



    public function store(Request $request)
    {
        foreach ($request->subject_id1 as $key => $sub1){
            return $this->service->checkMarkDist($sub1,$request->subject_id2[$key]);
        }
    }


    public function edit($id){
        $combindSubject  = CombindSubject::with('subject1','subject2')->where('id',$id)->first();
        $classSubjects   = ClassSubject::with('subject')->where('ins_class_id',$combindSubject->class_id)->get();
        return view('template1.exammanagement.combind-subject.edit',compact('combindSubject','classSubjects'));
    }

    public function update(Request $request,$id)
    {
        $combindSubject = CombindSubject::find($id);

        $combindSubject->update($request->all());

        //notification
        $notification = array(
            'message' =>'Subject Update Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('exam-management.setting.combind-subject.index')->with($notification);
    }
    public function getSubjectForCombined(Request $request){

        $subjectIds = [];
        $combSubjects = CombindSubject::where('class_id',$request->class_id)->where('exam_id',$request->exam_id)->get();
        foreach ($combSubjects as $subject){
            array_push($subjectIds,$subject->subject_1);
        }
        foreach ($combSubjects as $subject){
            array_push($subjectIds,$subject->subject_2);
        }
        $uniqueSubIds = array_unique($subjectIds);

        //get subject mark Distribution
        $markDistros = SubMarksDist::where('class_id',$request->class_id)->where('exam_id',$request->exam_id)->get();

        $subIds = [];
        foreach ($markDistros as $markDIstro){
            array_push($subIds,$markDIstro->class_subject_id);
        }

        $classSubjects = ClassSubject::with('subject')
                        ->whereIn('id',$subIds)
                        ->whereNotIn('id',$uniqueSubIds)
                        ->where('ins_class_id',$request->class_id)
                        ->get();
        return $classSubjects;
    }
}
