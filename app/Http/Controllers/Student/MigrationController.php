<?php

namespace App\Http\Controllers\Student;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\InsClass;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentArchive;
use Illuminate\Http\Request;

class MigrationController extends Controller
{
    public function index()
    {
        $academic_years = Session::all();
        $classes = InsClass::all();
        return view($this->backendTemplate['template']['path_name'].'.student.migration.index',compact('academic_years','classes'));
    }


    public function store(Request $request)
    {
        // return $request->all();
        foreach($request->check as $val){
    
           $student = Student::find($request->student_id[$val]);
           Student::create([
            'session_id'            => $request['academic_year_id'],
            'class_id'              => $request['class_id'],
            'section_id'            => $request['section_id'],
            'shift_id'              => $request['shift_id'],
            'category_id'           => $request['category_id'],
            'group_id'              => $request['group_id'],
            'roll_no'               => $request['new_roll'][$val],

            'id_no'                 => $student['id_no'],
            'division_id'           => $student['division_id'],
            'district_id'           => $student['district_id'],
            'upazila_id'            => $student['upazila_id'],
            'finger_id'             => $student['finger_id'],
            'device_id'             => $student['device_id'],
            'dob'                   => $student['dob'],
            'religion'              => $student['religion'],
            'mobile_number'         => $student['mobile_number'],
            'father_name'           => $student['father_name'],
            'father_profession'     => $student['father_profession'],
            'father_nid_no'         => $student['father_nid_no'],
            'father_passport_no'    => $student['father_passport_no'],
            'father_nationality'    => $student['father_nationality'],
            'father_religion'       => $student['father_religion'],
            'mother_name'           => $student['mother_name'],
            'mother_profession'     => $student['mother_profession'],
            'mother_nid_no'         => $student['mother_nid_no'],
            'mother_passport_no'    => $student['mother_passport_no'],
            'mother_nationality'    => $student['mother_nationality'],
            'mother_religion'       => $student['mother_religion'],
            'guardien_name'         => $student['guardien_name'],
            'guardien_profession'   => $student['guardien_profession'],
            'guardien_nid_no'       => $student['guardien_nid_no'],
            'guardien_passport_no'  => $student['guardien_passport_no'],
            'guardien_nationality'  => $student['guardien_nationality'],
            'guardien_religion'     => $student['guardien_religion'],
            'guardien_relation'     => $student['guardien_relation'],
            'email'                 => $student['email'],
            'name'                  => $student['name'],
            'gender'                => $student['gender'],
            'blood_group'           => $student['blood_group']
           ]);
            
        }

        //notification
        $notification = array(
            'message' =>'Student Migrate Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);

    }
}
