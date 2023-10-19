<?php

namespace App\Http\Controllers\Student;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\ClassSubject;
use App\Models\InsClass;
use App\Models\Institution;
use App\Models\OnlineAdmission;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentSubjectAssign;
use App\Models\StudentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\Admission\AdmissionSmsServices;

class OnlineAdmissionController extends Controller
{
    public function index()
    {
        $admissions = OnlineAdmission::all();
        $academic_years = Session::all();
        $classes = InsClass::all();
        return view($this->backendTemplate['template']['path_name'].'.student.online-admission.index',compact('admissions','academic_years','classes'));
    }


    public function store(Request $request, AdmissionSmsServices $admissionsms)
    {
       // return $request->all();
        $institute      = Institution::with('package')->find(Helper::getInstituteId());
        $studentLimit   = $institute->package->student;

        $student        = Student::get();
        $ourStudent     = $student->count();

        if ($ourStudent > $studentLimit) {
            //notification
            $notification = array(
                'message' => 'Student Out Of Limit',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

       // dd($request->all());

        if ($request->check) {
            foreach ($request->check as $key => $val) {
                if($request['roll_no'][$val] != ''){
                    $data = OnlineAdmission::find($val);

                    $student                = new Student();
                    $studentId              = Helper::studentIdGenerate($request['session_id'], $request['class_id']);
                    
                    $student->id_no         = $studentId;
                    $student->session_id    = $request['session_id'];
                    $student->class_id      = $request['class_id'];
                    $student->shift_id      = $request['shift_id'];
                    $student->section_id    = $request['section_id'];
                    $student->category_id   = $request['category_id'];
                    $student->group_id      = $request['group_id'];
                    $student->roll_no       = $request['roll_no'][$val];
                    $student->division_id   = $data['division_id'];
                    $student->district_id   = $data['district_id'];
                    $student->upazila_id    = $data['upazila_id'];
                    $student->name          = $data['name'];
                    $student->gender        = $data['gender'];
                    $student->religion      = $data['religion'];
                    $student->father_name   = $data['father_name'];
                    $student->mother_name   = $data['mother_name'];
                    $student->mobile_number = $data['mobile_number'];
                    $student->admission_no  = $data['admission_no'];
                    $student->save();

                    // sms service
                    $admissionsms->confirmSms($student);

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
                        'student_id'   => $student->id,
                        'id_no'        => $student->id_no,
                        'name'         => $data['name'],
                        'password'     => Hash::make($data['mobile_number'])
                    ]);

                    if(@$request->type == 'online_admission'){

                        OnlineAdmission::where('id',$data['id'])->delete();
                    }
                }else{
                    //notification
                    $notification = array(
                        'message' => 'Please Input Roll No',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
            }
        } else {
            //notification
            $notification = array(
                'message' => 'Student Not Selected',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }


        //notification
        $notification = array(
            'message' => 'Student Admitted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('student.online-admission.list')->with($notification);
    }

}



