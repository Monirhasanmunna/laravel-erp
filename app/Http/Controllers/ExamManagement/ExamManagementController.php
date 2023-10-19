<?php

namespace App\Http\Controllers\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\SubMarksDist;
use Illuminate\Http\Request;

class ExamManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::all();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.examlist.index', compact('exams'));
    }

    public function examList()
    {
        $exams = Exam::orderBy('id', 'desc')->get();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.examlist.examlist', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sessions = Session::all();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.examlist.create',compact('sessions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);


        $data = $request->all();
        Exam::create($data);

        //notification
        $notification = array(
            'message' => 'Exam Create Successfully ',
            'alert-type' => 'success'
        );

        return  redirect()->route('exam-management.exam.index')->with($notification);
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = Exam::find($id);
        $sessions = Session::all();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.examlist.create', compact('exam','sessions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();

        $exam = Exam::find($id);
        $exam->update($data);

        //notification
        $notification = array(
            'message' => 'Exam Update Successfully',
            'alert-type' => 'success'
        );

        return  redirect()->route('exam-management.exam.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = Exam::find($id);
        $exam->delete();

        //notification
        $notification = array(
            'message' => 'Exam Delete Successfully ',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function getSubjects(Request $request)
    {
        $subjectDists = SubMarksDist::with('details', 'details.subMarkDistType', 'classSubject','classSubject.subject')
                        ->where('class_id', $request->ins_class_id)
                        ->where('exam_id', $request->exam_id)
                        ->get();
        // dd($subjectDists);                
        return $subjectDists;
    }

    public function getClassExam(Request $request)
    {
        $data['classes'] = InsClass::where('session_id',$request->session_id)->get();
        $data['exams'] = Exam::where('session_id',$request->session_id)->get();
        return response()->json($data);
    }
}
