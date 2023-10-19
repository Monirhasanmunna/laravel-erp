<?php

namespace App\Http\Controllers\ExamManagement;

use App\Models\Session;
use App\Models\Student;
use App\Models\ClassSubject;
use App\Models\SubMarksDist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StudentSubjectAssign;

class NumberSheetController extends Controller
{
    public function index()
    {
        $academic_years = Session::all();
        return view('template1.exammanagement.number-sheet.index',compact('academic_years'));
    }
    
    public function getSubjects(Request $request)
    {

        //get students
        $matched = [
            'class_id'    => $request->class_id,
            'section_id'  => $request->section_id,
            'category_id' => $request->category_id,
            'group_id'    => $request->group_id
        ];

        $students = Student::with('assignSubjects')->where($matched)->get();

        $stdSub = [];
        foreach($students as $student){
           foreach($student->assignSubjects as $assingSub){
                array_push($stdSub,$assingSub->class_subject_id);
           }
        }

        $classSubjects = ClassSubject::with('subject','markDists')
                            ->whereHas('markDists',function($q) use($request){
                                $q->where('exam_id',$request->exam_id);
                            })
                            ->whereIn('id',$stdSub)
                            ->has('markDists')
                            ->get()
                            ->map(function ($item) use ($request) {
                                return [
                                    'id'            => $item->id,
                                    'sub_name'      => $item->subject->sub_name,
                                ];
                            })->chunk(4);
        return response()->json($classSubjects);
    }



    public function getStudents(Request $request)
    {
        $req = array();
        parse_str($request->student_info,$req);
        $req['class_subject_id'] = $request->subject_id;

        $students = StudentSubjectAssign::where('class_subject_id', $req['class_subject_id'])->get();
        $studentIds = [];
        foreach ($students as $student){
            array_push($studentIds,$student->student_id);
        }

        $data['students'] = Student::whereIn('id',$studentIds)
                            ->where('class_id',$req['class_id'])
                            ->where('section_id',$req['section_id'])
                            ->where('category_id',$req['category_id'])
                            ->where('group_id',$req['group_id'])
                            ->get()->map(function ($item) use ($req) {
                                return [
                                    'id'      => $item->id,
                                    'name'    => $item->name,
                                    'id_no'   => $item->id_no,
                                    'roll_no' => $item->roll_no,
                                    'mark_dists' => $this->getMarkDistsDetails($req['class_id'],$req['exam_id'], $req['class_subject_id'])
                                ];
                            });

        $markDist = SubMarksDist::with('details', 'details.subMarkDistType')
                    ->where('class_id', $req['class_id'])
                    ->where('exam_id', $req['exam_id'])
                    ->where('class_subject_id', $req['class_subject_id'])
                    ->first();

        if ($markDist) {
            $data['mark_dists'] = $markDist->details;
        } else {
            $data['mark_dists'] = null;
        }

        return $data;
    }

    public function getMarkDistsDetails($classId,$examId, $subjectId)
    {

        $markDist = SubMarksDist::with('details', 'details.subMarkDistType')
                    ->where('class_id', $classId)
                    ->where('exam_id', $examId)
                    ->where('class_subject_id', $subjectId)
                    ->first();

        if ($markDist) {
            return $markDist->details;
        } else {
            return null;
        }
    }
}
