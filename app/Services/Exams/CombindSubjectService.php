<?php

namespace App\Services\Exams;

use App\Models\CombindSubject;
use App\Models\SubMarksDist;

class CombindSubjectService
{

    private $request;

    public function __construct()
    {
        $this->request = request()->all();
    }



    /*
     |--------------------------------------------------------------------------
     | CHECK MARK & PASS MARK IS MATCHED
     |--------------------------------------------------------------------------
    */



    public function checkMarkDist($classSubId1,$classSubId2)
    {
        $check1 = $this->checkMarkDistSub($classSubId1);
        $check2 = $this->checkMarkDistSub($classSubId2);


        if($check1 == $check2){
            //return $this->storeCombindSubject();
            return $this->checkShortCode($classSubId1,$classSubId2);
        }
        else{
            //notification
            $notification = array(
                'message' =>'Total Mark & Pass Mark Are Not Equal',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function checkMarkDistSub($classSubId)
    {
        $markDist = SubMarksDist::where('class_id',$this->request['ins_class_id'])
                    ->where('exam_id',$this->request['exam_id'])
                    ->where('class_subject_id',$classSubId)
                    ->first(['total_mark','pass_mark'])->toArray();

        return serialize($markDist);
    }



    /*
     |--------------------------------------------------------------------------
     | CHECK SHORT CODE MATCH
     |--------------------------------------------------------------------------
    */



    public function checkShortCode($classSubId1,$classSubId2){

       $check1 = $this->checkShortCodeBySub($classSubId1);
       $check2 = $this->checkShortCodeBySub($classSubId2);

        if($check1 == $check2){
            return $this->storeCombindSubject();
        }
        else{
            //notification
            $notification = array(
                'message' =>'Subject Mark Distribution Not Matched',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }

    }

    public function checkShortCodeBySub($classSubId)
    {
        $markDist = SubMarksDist::where('class_id',$this->request['ins_class_id'])
                    ->where('exam_id',$this->request['exam_id'])
                    ->where('class_subject_id',$classSubId)
                    ->first();

        $shortCodeIds = [];
        foreach ($markDist->details as $detail){
            array_push($shortCodeIds,$detail->sub_marks_dist_type_id);
        }

        return serialize($shortCodeIds);
    }


    /*
      |--------------------------------------------------------------------------
      | STORE COMBINED SUBJECT
      |--------------------------------------------------------------------------
    */


    public function storeCombindSubject()
    {

        foreach ($this->request['subject_id1'] as $key => $sub1){

            CombindSubject::create([
                'class_id'  =>  $this->request['ins_class_id'],
                'exam_id'  =>   $this->request['exam_id'],
                'subject_1' =>  $sub1,
                'subject_2' =>  $this->request['subject_id2'][$key],
            ]);
        }

        //notification
        $notification = array(
            'message' =>'Subject Combined Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('exam-management.setting.combind-subject.index')->with($notification);
    }
}
