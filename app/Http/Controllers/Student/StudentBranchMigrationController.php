<?php

namespace App\Http\Controllers\Student;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ClassSubject;
use App\Models\InstituteBranch;
use App\Models\MigratedStudent;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentSubjectAssign;
use App\Models\StudentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentBranchMigrationController extends Controller
{
    public function index()
    {
        $sessions = Session::all();
        $branchId = Auth::user()->institute_branch_id;
        $branches = InstituteBranch::whereNotIn('id',[$branchId])->get();

        return view('template1.student.branch-migration.index', compact('sessions','branches'));
    }


    public function store(Request $request)
    {
        //return $request->all();

        if($request->branch_id && $request->student_ids){
            foreach ($request->student_ids as $stdId) {
                $student = Student::find($stdId);
                $std = [
                    'institute_branch_id' => $request->branch_id,
                    'name' => $student->name,
                    'id_no' => $student->id_no,
                    'roll_no' => $student->roll_no,
                    'photo'  => $student->photo,
                    'session' => $student->session->title,
                    'class' => $student->ins_class->name . "-" . $student->shift->name . "-" . $student->section->name,
                    'category' => $student->category->name,
                    'group' => $student->group->name,
                    'mobile_number' => $student->mobile_number,
                    'gender' => $student->gender,
                    'religion' => $student->religion,
                    'father_name' => $student->father_name,
                    'mother_name' => $student->mother_name,
                    'blood_group' => $student->blood_group
                ];

                $migrate = MigratedStudent::create($std);

                if ($migrate) {
                    $student->update([
                        'is_migrate' => 1
                    ]);
                }
            }
        }
        else{
            $notification = array(
                'message' => 'Branch Or Student Not Selected',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }



        $notification = array(
            'message' => 'Migrate Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



    public function getStudents(Request $request)
    {
        $students = Student::where(['session_id' => $request->session_id, 'class_id' => $request->class_id])
            ->where('is_migrate', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'id_no' => $item->id_no,
                    'name' => $item->name,
                    'roll_no' => $item->roll_no,
                    'class' => $item->class->name . "-" . $item->shift->name . "-" . $item->section->name,
                    'phone' => $item->mobile_number,
                ];
            });


        return $students;
    }


    public function studentList()
    {
        $academic_years = Session::all();
        $students = MigratedStudent::get();
        return view('template1.student.branch-migration.student-list', compact('students','academic_years'));
    }


    public function studentStore(Request $request){

        $data = $request->all();

        if($request->student_ids){
            $ids = [];
            foreach ($request->student_ids as $key => $stdId){
                $studentId              = Helper::studentIdGenerate($data['academic_year_id'], $data['class_id']);
                array_push($ids,$studentId);

                //new student create
                $student = MigratedStudent::find($stdId);
                $std = Student::create([
                        "id_no"         => $studentId,
                        "session_id"    => $data['academic_year_id'],
                        "class_id"      => $data['class_id'],
                        "shift_id"      => $data['shift_id'],
                        "section_id"    => $data['section_id'],
                        "category_id"   => $data['category_id'],
                        "group_id"      => $data['group_id'],
                        "roll_no"       => $request->roll_no[$key],
                        "name"          => $student['name'],
                        "photo"         => $student['photo'],
                        "gender"        => $student['gender'],
                        "religion"      => $student['religion'],
                        "father_name"   => $student['father_name'],
                        "mother_name"   => $student['mother_name'],
                        "mobile_number" => $student['mobile_number'],
                    ]);

                //assign Subject for student
                $classSubjects = ClassSubject::where('ins_class_id',$student->class_id)->get();

                foreach ($classSubjects as $classSubject){
                    StudentSubjectAssign::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'subject_type_id' => $classSubject->subject_type_id
                    ]);
                }

                //create student User
                StudentUser::create([
                    'institute_id' => Helper::getInstituteId(),
                    'student_id'   => $std->id,
                    'id_no'        => $std->id_no,
                    'name'         => $std->name,
                    'password'     => Hash::make($std['mobile_number'])
                ]);

                //remove migrated student
                MigratedStudent::find($stdId)->delete();
            }


        }
        else{
            $notification = array(
                'message' => 'Select Student',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $notification = array(
            'message' => 'Migrate Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
