<?php

namespace App\Http\Controllers\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\GeneralGrade;
use App\Models\InsClass;
use App\Models\Session;
use Illuminate\Http\Request;

class GpaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sessions = Session::all();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.gpa.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classIds = [];
        $gpaGradings = GeneralGrade::all();
        foreach ($gpaGradings as $grade) {
            array_push($classIds, $grade->ins_class_id);
        }
        $uniqueIds = array_unique($classIds);

        $classes = InsClass::with('session')->whereNotIn('id', $uniqueIds)->get();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.gpa.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        foreach ($request->class_id as  $classId) {

            foreach ($request->range_form as $key => $rangeFrom) {
                GeneralGrade::create([
                    'ins_class_id' => $classId,
                    'range_from'   => $rangeFrom,
                    'range_to'     => $request->range_to[$key],
                    'point_from'   => $request->point_from[$key],
                    'point_to'     => $request->point_to[$key],
                    'gpa_name'     => $request->gpa_name[$key],
                    'gpa_point'    => $request->gpa_point[$key],
                    'comment'      => $request->comments[$key],
                ]);
            }
        }

        //notification
        $notification = array(
            'message' => 'Grade Create Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('exam-management.setting.gpa-grading.index')->with($notification);
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
        $grades = GeneralGrade::where('ins_class_id',$id)->get();

        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.gpa.edit', compact('grades','id'));
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


        GeneralGrade::where('ins_class_id',$id)->delete();

        foreach ($request->range_form as $key =>  $rangeFrom){

            GeneralGrade::create([
                'ins_class_id'   => $id,
                'range_from'   => $rangeFrom,
                'range_to'     => $request->range_to[$key],
                'point_from'   => $request->point_from[$key],
                'point_to'     => $request->point_to[$key],
                'gpa_name'     => $request->gpa_name[$key],
                'gpa_point'    => $request->gpa_point[$key],
                'comment'      => $request->comments[$key],
            ]);
        }

        //notification
        $notification = array(
            'message' => 'Grade Update Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('exam-management.setting.gpa-grading.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grade = GeneralGrade::find($id);

        $grade->delete();

        //notification
        $notification = array(
            'message' => 'Grade Delete Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('exam-management.setting.gpa-grading.index')->with($notification);
    }


    public function getGrades(Request $request){
        $grades = GeneralGrade::with('class', 'class.session')
                    ->whereHas('class',function ($query) use($request){
                        $query->where('session_id',$request->session_id);
                    })
                    ->get()->groupBy('ins_class_id');
        return $grades;
    }
}
