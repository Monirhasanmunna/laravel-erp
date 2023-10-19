<?php

namespace App\Http\Controllers\TeacherPanel;

use App\Http\Controllers\Controller;
use App\Imports\MarksImport;
use App\Models\AssignTeacherSubject;
use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentMarksInput;
use App\Models\StudentMarksInputDetail;
use App\Models\StudentSubjectAssign;
use App\Models\Subject;
use App\Models\SubMarksDist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MarksInputController extends Controller
{
    public function index()
    {
        $academic_years = Session::all();
        return view('teacherpanel.exam-management.marks-input.index', compact('academic_years'));
    }


    public function getSubjects(Request $request)
    {

        $subMarkDists = SubMarksDist::where('class_id', $request->class_id)
                        ->where('exam_id', $request->exam_id)
                        ->get();

        $classSubjectIds = [];
        foreach ($subMarkDists as $dist) {
            array_push($classSubjectIds, $dist->class_subject_id);
        }

        //get teacher assign subjects
        $teacherId = Auth::user()->teacher_id;
        $assignSubject = AssignTeacherSubject::where('teacher_id',$teacherId)->pluck('class_subject_id')->toArray();

       

        $filteredArray = array_filter($classSubjectIds,function($item) use($assignSubject){
            if (in_array($item, $assignSubject))
            {
              return $item;
            }
        });

    

        $classSubjects = ClassSubject::with('subject')
            ->whereIn('id', $filteredArray)
            ->get()
            ->map(function ($item) use ($request) {
                return [
                    'id'            => $item->id,
                    'sub_name'      => $item->subject->sub_name,
                    'student_count' => $this->countInputMarksStudents($item->id, $request->all()),
                    'status'        => $this->getSubjectStatus($request->class_id, $request->section_id, $request->exam_id, $item->id)
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


    public function uploadExcel()
    {
        return view('teacherpanel.exam-management.marks-input.upload-excel');
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
}
