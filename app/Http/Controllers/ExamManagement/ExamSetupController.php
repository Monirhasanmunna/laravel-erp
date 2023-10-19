<?php

namespace App\Http\Controllers\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\Exam;
use App\Models\General_exam_setting;
use App\Models\Average_exam_setting;
use App\Models\Average_exam_setting_detail;

class ExamSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
         $general_exam_setting = General_exam_setting::all();
         $average_exam_setting = Average_exam_setting::with('average_exam_setting_detail')->get();
         return view($this->backendTemplate['template']['path_name'].'.exammanagement.exam_setup.index',compact('general_exam_setting'),compact('average_exam_setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generalsetup()
    {
        $academic_years = Session::all();
        $exams=Exam::all();
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.exam_setup.general_setup',compact('academic_years'),compact('exams'));
    }

    

    public function save_generalsetup(Request $request)
{
    $class = $request->input('class_id', []);
    foreach($class as $key => $val){
        $gset = General_exam_setting::where([
            ['session_id', '=', $request->input("session_id")],
            ['class_id', '=', $request->class_id[$key]],
            ['exam_id', '=', $request->input("exam_id")],
        ])->first();
        if($gset){
            //update existing data
            $gset->class_test   = $request->input("class_test");
            $gset->calss_test_per      = $request->input("calss_test_per");
            $gset->attn_show       = $request->input("attn_show");
            $gset->calss_pos_gpa          = $request->input("calss_pos_gpa");
            $gset->calss_pos_total        = $request->input("calss_pos_total");
            $gset->calss_pos_atten      = $request->input("calss_pos_atten");
            $gset->sequentially   = $request->input("sequentially");
            $gset->non_sequentially   = $request->input("non_sequentially");
            $gset->save();
        } else {
            //create new data
            $gset = new General_exam_setting();
            $gset->session_id    = $request->input("session_id");
            $gset->class_id      = $request->class_id[$key];
            $gset->exam_id      = $request->input("exam_id");
            $gset->class_test   = $request->input("class_test");
            $gset->calss_test_per      = $request->input("calss_test_per");
            $gset->attn_show       = $request->input("attn_show");
            $gset->calss_pos_gpa          = $request->input("calss_pos_gpa");
            $gset->calss_pos_total        = $request->input("calss_pos_total");
            $gset->calss_pos_atten      = $request->input("calss_pos_atten");
            $gset->sequentially   = $request->input("sequentially");
            $gset->non_sequentially   = $request->input("non_sequentially");
            $gset->save();
        }
    }
    //notification
    $notification = array(
        'message' =>'General Exam Setup Successfull.',
        'alert-type' =>'success'
    );
    return  redirect()->route('exam-management.exam-setup.index')->with($notification);
}

public function edit_generalsetup($id)
    {
        
        $gsetting = General_exam_setting::find($id);
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.exam_setup.edit_general_setup',compact('gsetting'));
    }

    public function update_generalsetup(Request $request, $id)
    {

        $data = $request->all();
        $exam = General_exam_setting::find($id);
        $exam->class_test = $request->input('class_test') ? 1 : NULL;
        $exam->attn_show = $request->input('attn_show') ? 1 : NULL;
        $exam->sequentially = $request->input('sequentially') ? 1 : NULL;
        $exam->non_sequentially = $request->input('non_sequentially') ? 1 : NULL;
        $exam->update($data);

        //notification
        $notification = array(
            'message' =>'General Exam Setup Update Successfully',
            'alert-type' =>'success'
        );

        return  redirect()->route('exam-management.exam-setup.index')->with($notification);
    }

public function destroy_generalsetup($id)
    {
        $gsetting = General_exam_setting::find($id);
        $gsetting->delete();

        //notification
        $notification = array(
            'message' =>'Setting Delete Successfully ',
            'alert-type' =>'success'
        );

        return redirect()->back()->with($notification);
    }

    public function averagesetup()
    {
        $academic_years = Session::all();
        $exams=Exam::all();
        return view($this->backendTemplate['template']['path_name'].'.exammanagement.exam_setup.average_setup',compact('academic_years'),compact('exams'));
    }

    public function save_averagesetup(Request $request)
{
    $class = $request->input('class_id', []);
    $exam = $request->input('exam_id', []);
    $status = $request->input('exam_status', []);
    $exam_per = $request->input('exam_per', []);
    foreach($class as $key => $val){
        $aset = Average_exam_setting::where([
            ['session_id', '=', $request->input("session_id")],
            ['class_id', '=', $request->class_id[$key]]
        ])->first();
        if($aset){
            //update existing data
            $aset->gpa_average   = $request->input("gpa_average");
            $aset->attn_show       = $request->input("attn_show");
            $aset->calss_pos_gpa          = $request->input("calss_pos_gpa");
            $aset->calss_pos_total        = $request->input("calss_pos_total");
            $aset->calss_pos_atten      = $request->input("calss_pos_atten");
            $aset->sequentially   = $request->input("sequentially");
            $aset->non_sequentially   = $request->input("non_sequentially");
            $aset->save();

            $asetting = Average_exam_setting_detail::where('average_exam_setting_id', '=', $aset->id)->delete();

            foreach($exam as $ex => $val){

                $asetd = new Average_exam_setting_detail();
                $asetd->session_id    = $request->input("session_id");
                $asetd->class_id      = $aset->class_id;
                $asetd->exam_id      = $request->exam_id[$ex];
                $asetd->average_exam_setting_id = $aset->id;
                $asetd->exam_status   = $request->exam_status[$ex] ?? '';;
                $asetd->exam_per   = $request->exam_per[$ex];
                $asetd->save();
            }
            
        } else {
            //create new data
            $aset = new Average_exam_setting();
            $aset->session_id    = $request->input("session_id");
            $aset->class_id      = $request->class_id[$key];
            $aset->gpa_average   = $request->input("gpa_average");
            $aset->attn_show       = $request->input("attn_show");
            $aset->calss_pos_gpa          = $request->input("calss_pos_gpa");
            $aset->calss_pos_total        = $request->input("calss_pos_total");
            $aset->calss_pos_atten      = $request->input("calss_pos_atten");
            $aset->sequentially   = $request->input("sequentially");
            $aset->non_sequentially   = $request->input("non_sequentially");
            $aset->save();

            //$average_setting_id = Average_exam_setting::all()->last();
            $average_setting_id = Average_exam_setting::orderBy('id', 'desc')->first();
            
            foreach($exam as $ex => $val){
                
                $asetd = new Average_exam_setting_detail();
                $asetd->session_id    = $request->input("session_id");
                $asetd->class_id      = $request->class_id[$key];
                $asetd->exam_id      = $request->exam_id[$ex];
                $asetd->average_exam_setting_id = $average_setting_id->id;
                $asetd->exam_status   =  $request->exam_status[$ex] ?? '';;
                $asetd->exam_per   = $request->exam_per[$ex];
                $asetd->save();
            }
        }

    }
    //notification
    $notification = array(
        'message' =>'General Exam Setup Successfull.',
        'alert-type' =>'success'
    );
    return  redirect()->route('exam-management.exam-setup.index')->with($notification);
}

public function edit_averagesetup($id)
{
    
    $asetting = Average_exam_setting::with('average_exam_setting_detail')->find($id);
    return view($this->backendTemplate['template']['path_name'].'.exammanagement.exam_setup.edit_average_setup',compact('asetting'));
}

public function destroy_averagesetup($id)
    {
        $asetting = Average_exam_setting::find($id);
        $asettingd = Average_exam_setting_detail::where('average_exam_setting_id', '=', $id)->delete();
        $asetting->delete();

        //notification
        $notification = array(
            'message' =>'Setting Delete Successfully ',
            'alert-type' =>'success'
        );

        return redirect()->back()->with($notification);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
