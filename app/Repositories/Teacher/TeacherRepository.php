<?php

namespace App\Repositories\Teacher;

use App\Helper\Helper;
use App\Models\Teacher;
use App\Models\Branch;
use App\Models\Designation;
use App\Models\Experience;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\TeacherUser;
use App\Traits\FileSaver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherRepository implements TeacherInterface
{
    use FileSaver;

    public $backendTemplate;
    function __construct()
    {
        $this->backendTemplate  = collect(app()->make('backend-template')->getTemplate())->toArray();
    }

    public function index()
    {
        // TODO: Implement index() method.
        // return Teacher::all();
        $orderArray = [];
        for ($i = 1; $i < 50; $i++) {
            array_push($orderArray, $i);
        }
        array_push($orderArray,0);
       
        $items = Teacher::with(['designation:id,title', 'branch:id,title'])
            ->where('type', 'teacher')
            ->get();


        $teachers = $items->sortBy(function ($item) use ($orderArray) {
            return array_search($item->order, $orderArray);
        });

        // $template = $this->backendTemplate['template']['path_name'];


        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.index', compact('teachers'));
    }

    public function create()
    {
        $branches = Branch::all();
        $designations = Designation::all();
        $table_number = 1;
        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.create', compact('branches', 'designations', 'table_number'));
    }

    public function store(array $data)
    {

       // return Helper::generateTeacherId();

        if (!empty($data['check'])) {

            $count_array = count($data['check']);

            for ($i = 0; $i < $count_array; $i++) {
                $teacher = Teacher::create([
                    'id_no'          => Helper::generateTeacherId(),
                    'name'           => $data['name'][$i],
                    'gender'         => $data['gender'][$i],
                    'type'           => "teacher",
                    'uuid'           => $data['unique_id'][$i],
                    'mobile_number'  => $data['mobile_number'][$i],
                    'designation_id' => $data['designation_id'][$i],
                    'branch_id'      => $data['branch_id'][$i],
                ]);

                //Create Teacher User
                TeacherUser::create([
                    'institute_id' => Helper::getInstituteId(),
                    'teacher_id'   => $teacher->id,
                    'id_no'        => $teacher->id_no,
                    'name'         => $data['name'][$i],
                    'password'     => Hash::make($data['mobile_number'][$i])
                ]);
            }
        } else {
            return redirect()->route('teacher.index');
        }


        return redirect()->route('teacher.index')
            ->with('success', 'Teacher update successfully.');
    }


    public function getNumberOfTable(array $data)
    {

        $table_number = $data['table_number'];
        $branches = Branch::all();
        $designations = Designation::all();
        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.create', compact('branches', 'designations', 'table_number'));
    }


    public function exportPrint()
    {
        $teachers = Teacher::orderBy('id_no', 'asc')->where('type', 'teacher')->get();
        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.teacher-list-print', compact('teachers'));
    }

    public function exportPdf()
    {
        $teachers = Teacher::orderBy('id_no', 'asc')->where('type', 'teacher')->get();
        $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'] . '.teachers.teacher.teacher-list-pdf', compact('teachers'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->set_paper('A4', 'landscape');
        return $pdf->download('teacher-list' . $teachers->count() . '.pdf');
    }

    public function edit($id)
    {
        $teacher       = Teacher::with('attendance')->find($id);
        $religions     = Religion::all();
        $branches      = Branch::all();
        $designations  = Designation::all();
        $blood_groups  = Helper::blood_groups();
        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.profile_update', compact('branches', 'designations', 'teacher', 'blood_groups', 'religions'));
    }

    public function show($id)
    {
        $teacher = Teacher::find($id);
        return response()->json([
            'data' => $teacher,
            'status' => '200',
            'message' => 'success'
        ]);
    }

    public function updateSignature(array $data)
    {
        $teacher = Teacher::find($data['id']);

        if (@$data['signature_image']) {

            $this->uploadFileLinode($data['signature_image'], $teacher, 'signature_image', 'teacher');

            return response()->json([
                'data' => $teacher,
                'status' => 200,
                'message' => 'Update Successfully'
            ]);
        }
    }

    public function updatephoto(array $data)
    {
        $teacher = Teacher::find($data['id']);

        if (@$data['photo']) {

            $this->uploadFileLinode($data['photo'], $teacher, 'photo', 'teacher');

            return response()->json([
                'data' => $teacher,
                'status' => 200,
                'message' => 'Update Successfully'
            ]);
        }
    }

    public function update(array $data)
    {
        $teacher = Teacher::find($data['id']);
        $teacher->update([
            'name'              => $data['name'],
            'father_name'       => $data['father_name'],
            'uuid'              => $data['unique_id'],
            'mother_name'       => $data['mother_name'],
            'gender'            => $data['gender'],
            'religion'          => $data['religion'],
            'email'             => $data['email'],
            'mobile_number'     => $data['mobile_number'],
            'joining_date'      => $data['joining_date'],
            'designation_id'    => $data['designation'],
            'nid'               => $data['nid'],
            'date_of_birth'     => $data['date_of_birth'],
            'blood_group'       => $data['blood_group'],
            'present_address'   => $data['present_address'],
            'permanent_address' => $data['permanent_address'],
            'nationality'       => $data['nationality'],
            'branch_id'         => $data['branch'],
        ]);


        Experience::where('teacher_id', $teacher->id)->delete();

        foreach ($data['org_name'] as $key =>  $val) {
            $experience                   = new Experience();
            $experience->teacher_id       = $teacher->id;
            $experience->org_name         = $data['org_name'][$key];
            $experience->address          = $data['address'][$key];
            $experience->org_type         = $data['org_type'][$key];
            $experience->position         = $data['position'][$key];
            $experience->responsibilities = $data['responsibilities'][$key];
            $experience->duration         = $data['duration'][$key];
            $experience->save();
        }


        Qualification::where('teacher_id', $teacher->id)->delete();

        foreach ($data['exam_title'] as $key =>  $val) {
            $qualification                   = new Qualification();
            $qualification->teacher_id       = $teacher->id;
            $qualification->exam_title       = $data['exam_title'][$key];
            $qualification->year             = $data['year'][$key];
            $qualification->university       = $data['university'][$key];
            $qualification->gpa              = $data['gpa'][$key];
            $qualification->save();
        }

        $notification = array(
            'message' => 'Teacher Update Successfully ',
            'alert-type' => 'success'
        );

        return redirect()->route('teacher.index')->with($notification);
    }



    public function destroy($id)
    {
        return Teacher::destroy($id);
    }

    public function uploadcreate()
    {
        return view($this->backendTemplate['template']['path_name'] . '.teachers.teacher.upload_teacher');
    }
}
