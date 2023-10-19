<?php

namespace App\Http\Controllers\Branch;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\InstituteBranch;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Response;


class InstituteBranchController extends Controller
{
    public function index()
    {
        $branches = InstituteBranch::all();
        return view('template1.branch-management.branch.index', compact('branches'));
    }


    public function get()
    {

        $branches = InstituteBranch::with('students', 'teachers')
            ->where('institute_id', Helper::getInstituteId())
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'principal_name' => $item->principal_name,
                    'phone' => $item->phone,
                    'address' => $item->address,
                    'student_count' => $this->studentCount($item->id),
                    'teacher_count' => $this->teacherCount($item->id),
                ];
            });
        return response()->json($branches);
    }


    public function studentCount($branchId)
    {
        $students = Student::withoutGlobalScopes()->where('institute_branch_id', $branchId)->count();
        return $students;
    }


    public function teacherCount($branchId)
    {
        $teachers = Teacher::withoutGlobalScopes()->where('institute_branch_id', $branchId)->count();
        return $teachers;
    }


    public function store(Request $request)
    {
        $rules = array('name' => 'required|unique:institute_branches,name', 'principal_name' => 'required');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
        $data = $request->all();
        $data['institute_id'] = Helper::getInstituteId();
        InstituteBranch::create($data);

        return Response::json(array('success' => true), 200);
    }

    public function edit(Request $request)
    {
        $branch = InstituteBranch::find($request->id);
        return response()->json($branch);
    }

    public function update(Request $request)
    {

        $rules = array('name' => 'required');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $branch = InstituteBranch::find($request->id);
        $branch->update($request->all());
        return Response::json(array('success' => true), 200);
    }

    public function delete(Request $request)
    {
        $branch = InstituteBranch::find($request->id);
        $branch->delete();
        return response()->json($branch);
    }


    public function getStudentInfo(Request $request)
    {
        $sessions = Session::withoutGlobalScopes()
                    ->where('institute_id', Helper::getInstituteId())
                    ->where('institute_branch_id', $request->branch_id)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id'       => $item->id,
                            'name'     => $item->title,
                            'students' => $this->countStudents($item->id),
                            'male'     => $this->countMaleStudents($item->id),
                            'female'   => $this->countFemaleStudents($item->id)
                        ];
                    });

        return response()->json($sessions);
    }


    public function countStudents($sessionId){
        $students = Student::withoutGlobalScopes()
                    ->where('session_id',$sessionId)
                    ->count();
        return $students;            
    }


    public function countMaleStudents($sessionId){
        $students = Student::withoutGlobalScopes()
                    ->where('session_id',$sessionId)
                    ->where('gender','Male')
                    ->count();
        return $students;            
    }


    public function countFemaleStudents($sessionId){
        $students = Student::withoutGlobalScopes()
                    ->where('session_id',$sessionId)
                    ->where('gender','Female')
                    ->count();
        return $students;            
    }
}
