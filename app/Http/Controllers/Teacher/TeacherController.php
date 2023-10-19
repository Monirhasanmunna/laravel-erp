<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Repositories\Teacher\TeacherInterface;
use App\Http\Requests\StoreTeacherRequest;
use App\Imports\TeacherImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachersExport;
use App\Helper\Helper;

use App\Models\AssignTeacher;
use App\Models\TeacherUser;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\ViewErrorBag;

class TeacherController extends Controller
{
    protected $teacher;


    public function __construct(TeacherInterface $teacherInterface)
    {

        $this->teacher = $teacherInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->teacher->index();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return $this->teacher->create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacherRequest $request)
    {
        $notification = array(
            'message' => ' Update Successfully ',
            'alert-type' => 'success'
        );
        return $this->teacher->store($request->all());
    }
    // get number of table

    public function getNumberOfTable(StoreTeacherRequest $request)
    {
        return $this->teacher->getNumberOfTable($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->teacher->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->teacher->edit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return $this->teacher->update($request->all());
    }

    public function updateSignature(Request $request)
    {
        return $this->teacher->updateSignature($request->all());
    }

    public function updatephoto(Request $request)
    {
        return $this->teacher->updatephoto($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $assignedTeacher = AssignTeacher::where('teacher_id', $id)->first();
        $teacher = Teacher::find($id);

        $assignedTeacher ? $assignedTeacher->delete() : '';
        $teacher->delete();

        $notification = array(
            'message' => ' Delete Successfully ',
            'alert-type' => 'success'
        );

        return redirect()->route('teacher.index')->with($notification);
    }


    // public function uploadcreate()
    // {

    //     $temp = $this->backendTemplate['template']['path_name'];
    //     dd($temp);

    //     return view($this->backendTemplate['template']['path_name'].'.teachers.teacher.upload_teacher');
    // }

    public function uploadcreate()
    {
        return $this->teacher->uploadcreate();
    }

    public function uploadStore(Request $request)
    {

        $request->validate([
            'files_excel' => 'required|max:50000'
        ]);

        $datas = Excel::toArray(new TeacherImport, $request->file('files_excel'));
        $data = $datas[0];

        $teachers  = Teacher::all();

        $id = [];
        foreach ($teachers as $key => $teacher) {
            array_push($id, $teacher->id_no);
        }

        for ($i = 1; $i < count($data); $i++) {
            if (!in_array($data[$i][0], $id)) {

                $uploadTeacher = new Teacher();
                $uploadTeacher->id_no         = Helper::generateTeacherId();
                $uploadTeacher->name          = $data[$i][0];
                $uploadTeacher->gender        = ucfirst($data[$i][1]);
                $uploadTeacher->mobile_number = $data[$i][2];
                $uploadTeacher->blood_group   = $data[$i][3];
                $uploadTeacher->type          = 'teacher';
                $uploadTeacher->save();
            } else {
                $notification = array(
                    'message' => Helper::generateTeacherId() . ' ID alredy used',
                    'alert-type' => 'error'
                );
                return redirect()->route('teacher.upload.create')->with($notification);
            }


            //create Teacher User
            TeacherUser::create([
                'institute_id' => Helper::getInstituteId(),
                'teacher_id'   => $uploadTeacher->id,
                'id_no'        => $uploadTeacher->id_no,
                'name'         => $uploadTeacher->name,
                'password'     => Hash::make($uploadTeacher->mobile_number)
            ]);
        }



        $notification = array(
            'message' => 'Teacher Upload Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('teacher.upload.create')->with($notification);
    }



    public function changePassword(Request $request)
    {
        $teacher = TeacherUser::where('teacher_id', $request->teacher_id)->first();

        $teacher->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'data' => $teacher,
            'status' => 200,
            'message' => 'Update Successfully'
        ]);
    }



    public function exportTeachers()
    {
        return Excel::download(new TeachersExport, 'teacher.xlsx');
    }


    public function exportprintTeachers(Request $request)
    {
        return $this->teacher->exportPrint();
    }

    public function exportpdfTeachers(Request $request)
    {
        return $this->teacher->exportPdf();
    }

    public function createUser($id)
    {
        $teacher = Teacher::find($id);

        TeacherUser::create([
            'institute_id' => Helper::getInstituteId(),
            'teacher_id'   => $teacher->id,
            'id_no'        => $teacher->id_no,
            'name'         => $teacher->name,
            'password'     => Hash::make($teacher->mobile_number)
        ]);

        $notification = array(
            'message' => 'User Create Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



    public function order()
    {
        $teachers = Teacher::all();
        return view('template1.teachers.teacher.order',compact('teachers'));
    }


    public function updateOrder(Request $request){

        foreach($request->teacher_ids as $key => $id){
            $teacher = Teacher::find($id);

            $teacher->update([
                'order' => $request->order[$key]
            ]);
        }


        $notification = array(
            'message' => 'Ordered Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
