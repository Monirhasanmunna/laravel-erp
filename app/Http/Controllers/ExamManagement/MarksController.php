<?php

namespace App\Http\Controllers\ExamManagement;

use App\Exports\MarksInputExport;
use App\Http\Controllers\Controller;
use App\Imports\MarksImport;
use App\Models\Category;
use App\Models\ClassSubject;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\Exam;
use App\Models\Group;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentMarksInput;
use App\Models\StudentMarksInputDetail;
use App\Models\StudentSubjectAssign;
use App\Models\Subject;
use App\Models\SubMarksDist;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class MarksController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.marks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Session::all();
        $exams = Exam::all();
        return view($this->backendTemplate['template']['path_name'] . '.exammanagement.marks.create', compact('academic_years'), compact('exams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //return $request->all();

        $marks = StudentMarksInput::with('details')
                ->where('exam_id', $request->exam_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('category_id', $request->category_id)
                ->where('group_id', $request->group_id)
                ->where('class_subject_id', $request->subject_id)
                ->get();

        //delete marks
        if ($marks) {
            foreach ($marks as $mark) {
                if ($mark->details) {
                    foreach ($mark->details as $detail) {
                        $detail->delete();
                    }
                }
                $mark->delete();
            }
        }


        $marksInput = StudentMarksInput::create([
            'class_id'   => $request->class_id,
            'section_id'   => $request->section_id,
            'category_id'   => $request->category_id,
            'group_id'   => $request->group_id,
            'exam_id'   => $request->exam_id,
            'class_subject_id' => $request->subject_id
        ]);

        foreach ($request->student_id as $studentId) {

            $req            = "mark_dist_detail-" . $studentId;
            $markDistDetail = "mark_dist_details_id-" . $studentId;

            foreach ($request->$req as $key =>  $marks) {
                StudentMarksInputDetail::create([
                    'student_id'                => $studentId,
                    'student_marks_input_id'    => $marksInput->id,
                    'sub_marks_dist_details_id' => $request->$markDistDetail[$key],
                    'marks' => $marks ?? 0,
                ]);
            }
        }
        //notification
        $notification = array(
            'message' => 'Marks Input Successfully ',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
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
                                    'student_count' => $this->countInputMarksStudents($item->id,$request->all()),
                                    'status'        => $this->getSubjectStatus($request->class_id,$request->section_id,$request->exam_id, $item->id)
                                ];
                            });
        return response()->json($classSubjects);
    }

    public function countInputMarksStudents($classSubjectId,$request)
    {

        $studentCount = StudentSubjectAssign::where('class_subject_id', $classSubjectId)
                        ->whereHas('student',function ($query) use($request){
                            $query->where('class_id',$request['class_id'])
                                    ->where('section_id',$request['section_id'])
                                    ->where('category_id',$request['category_id'])
                                    ->where('group_id',$request['group_id']);
                        })
                        ->get();
        return $studentCount->count();
    }

    public function getSubjectStatus($classId,$sectionId,$examId, $subjectId)
    {
        $inputMarks = StudentMarksInput::where('class_id', $classId)
                    ->where('section_id', $sectionId)
                    ->where('exam_id', $examId)
                    ->where('class_subject_id', $subjectId)
                    ->first();
        if ($inputMarks) {
            return "done";
        } else {
            return "pending";
        }
    }
    public function getStudents(Request $request)
    {

        $students = StudentSubjectAssign::where('class_subject_id', $request->class_subject_id)->get();
        $studentIds = [];
        foreach ($students as $student){
            array_push($studentIds,$student->student_id);
        }

        $data['students'] = Student::whereIn('id',$studentIds)
                            ->where('class_id',$request->class_id)
                            ->where('section_id',$request->section_id)
                            ->where('category_id',$request->category_id)
                            ->where('group_id',$request->group_id)
                            ->get()->map(function ($item) use ($request) {
                                return [
                                    'id'      => $item->id,
                                    'name'    => $item->name,
                                    'id_no'   => $item->id_no,
                                    'roll_no' => $item->roll_no,
                                    'mark_dists' => $this->getMarkDistsDetails($request->class_id,$request->exam_id, $request->class_subject_id)
                                ];
                            });

        $markDist = SubMarksDist::with('details', 'details.subMarkDistType')
                    ->where('class_id', $request->class_id)
                    ->where('exam_id', $request->exam_id)
                    ->where('class_subject_id', $request->class_subject_id)
                    ->first();

        if ($markDist) {
            $data['mark_dists'] = $markDist->details;
        } else {
            $data['mark_dists'] = null;
        }

        return $data;
    }

    public function getExamName($examId)
    {
        $exam = Exam::find($examId);
        return $exam->name;
    }

    public function getSubjectName($subId)
    {
        $subject = ClassSubject::find($subId);
        return $subject->subject->sub_name;
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

    public function getMarks(Request $request)
    {
        //return $request->all();

        $marksInput = StudentMarksInput::with('details')
            ->where('exam_id', $request->exam_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->where('class_subject_id', $request->subject_id)
            ->first();

        if ($marksInput) {
            return response()->json($marksInput->details);
        } else {
            return null;
        }
    }


    public function downloadExcel(Request $request)
    {
        
        $data['students'] = Student::where('class_id', $request->class_id)
            ->where('category_id', $request->category_id)
            ->where('section_id', $request->section_id)
            ->where('group_id', $request->group_id)
            ->get()->map(function ($item) use ($request) {
                return [
                    'id'      => $item->id,
                    'exam'    => $this->getExamName($request->exam_id),
                    'subject' => $this->getSubjectName($request->class_subject_id),
                    'name'    => $item->name,
                    'id_no'   => $item->id_no,
                    'roll_no' => $item->roll_no
                ];
            });

        $markDist = SubMarksDist::with('details', 'details.subMarkDistType')
            ->where('class_id', $request->class_id)
            ->where('class_subject_id', $request->class_subject_id)
            ->first();

        if ($markDist) {
            $data['mark_dists'] = $markDist->details;
        } else {
            $data['mark_dists'] = null;
        }

        $class   = InsClass::find($request->class_id);
        $exam    = Exam::find($request->exam_id);
        $subject = ClassSubject::find($request->class_subject_id);

        return Excel::download(new MarksInputExport($data), $class->name . '-' . $exam->name . '-' . $subject->subject->sub_name . '.xlsx');
    }

    public function studentCount(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->get();
        return $students->count();
    }

    public function uploadExcel()
    {
        return view('template1.exammanagement.marks.upload-excel');
    }


    public function uploadExcelStore(Request $request)
    {
        $request->validate([
            'file' => 'required|max:50000'
        ]);

        $datas = Excel::toArray(new MarksImport, $request->file('file'));
        $array  = $datas[0];

        $data['headings'] =  $array[0];
        $data['body'] = array_slice($array, 1);
        return view('template1.exammanagement.marks.upload-excel', compact('data'));
    }


    public function uploadExcelInsert(Request $request)
    {

        //return $request->all();
        $student      = Student::where('id_no', $request->student_id[0])->first();
        $exam         = Exam::where('session_id', $student->session_id)->where('name', $request->exam_name)->first();
        $subject      = Subject::where('sub_name', $request->subject_name)->first();
        $classSubject = ClassSubject::where('ins_class_id', $student->class_id)->where('subject_id', $subject->id)->first();


        $exist = StudentMarksInput::where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
                ->where('category_id', $student->category_id)
                ->where('group_id', $student->group_id)
                ->where('exam_id', $exam->id)
                ->where('class_subject_id', $classSubject->id)
                ->first();

        if ($exist) {
            //notification
            $notification = array(
                'message' =>'Marks Input Already Exist',
                'alert-type' =>'error'
            );
            return redirect()->route('exam-management.marks.upload-excel')->with($notification);
        }
        else {
            $marksInput = StudentMarksInput::create([
                'class_id'         => $student->class_id,
                'section_id'       => $student->section_id,
                'category_id'      => $student->category_id,
                'group_id'         => $student->group_id,
                'exam_id'          => $exam->id,
                'class_subject_id' => $classSubject->id
            ]);

            $studentIds = [];
            foreach ($request->student_id as $studentId) {
                $student = Student::where('id_no', $studentId)->first();
                array_push($studentIds, $student->id);
            }

            $markDist = SubMarksDist::with('details')->where('class_id', $student->class_id)->where('class_subject_id', $classSubject->id)->first();
            $markDistDetails = $markDist->details;

            foreach ($studentIds as $k => $studentId) {

                foreach ($markDistDetails as $key => $markDistDetail) {

                    $markReq = "short_code-" . $key + 1;

                    StudentMarksInputDetail::create([
                        'student_id'                => $studentId,
                        'student_marks_input_id'    => $marksInput->id,
                        'sub_marks_dist_details_id' => $markDistDetail->id,
                        'marks' => $request->$markReq[$k] ?? 0,
                    ]);
                }
            }
        }
        //notification
        $notification = array(
            'message' =>'Marks Input Insert Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('exam-management.marks.create')->with($notification);
    }


    public function print($req)
    {
        $requests = explode(',',$req);

        $request = [];

        foreach($requests as $requ){
            $exReq = explode('-',$requ);
            $request[$exReq[0]] = $exReq[1];
        }


        $students = StudentSubjectAssign::where('class_subject_id', $request['class_subject_id'])->get();
        $studentIds = [];
        foreach ($students as $student){
            array_push($studentIds,$student->student_id);
        }

        $data['students'] = Student::whereIn('id',$studentIds)
                            ->where('class_id',$request['class_id'])
                            ->where('section_id',$request['section_id'])
                            ->where('category_id',$request['category_id'])
                            ->where('group_id',$request['group_id'])
                            ->get()->map(function ($item) use ($request) {
                                return [
                                    'id'      => $item->id,
                                    'name'    => $item->name,
                                    'id_no'   => $item->id_no,
                                    'roll_no' => $item->roll_no,
                                    'mark_dists' => $this->getMarkDistsDetails($request['class_id'],$request['exam_id'], $request['class_subject_id'])
                                ];
                            });

        $markDist = SubMarksDist::with('details', 'details.subMarkDistType')
                    ->where('class_id', $request['class_id'])
                    ->where('exam_id', $request['exam_id'])
                    ->where('class_subject_id', $request['class_subject_id'])
                    ->first();

        if ($markDist) {
            $data['mark_dists'] = $markDist->details;
        } else {
            $data['mark_dists'] = null;
        }

        //for invoice title

        $class    = InsClass::find($request['class_id']);
        $section  = Section::find($request['section_id']);
        $category = Category::find($request['category_id']);
        $group    = Group::find($request['group_id']);
        $exam     = Exam::find($request['exam_id']);
        $classSubject = ClassSubject::find($request['class_subject_id']);

        $title = [
                    'class'    => $class->name.'-'.$section->shift->name.'-'.$section->name,
                    'exam'     => $exam->name,
                    'category' => $category->name,
                    'group'    => $group->name,
                    'subject'  => $classSubject->subject->sub_name
                ];
      

        return view('template1.exammanagement.marks.print',compact('data','title','request'));
    }
}
