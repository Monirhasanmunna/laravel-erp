<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\CommitteeExport;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Designation;
use App\Models\Experience;
use App\Models\Qualification;
use App\Models\Teacher;
use App\Traits\FileSaver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CommitteeController extends Controller
{
    use FileSaver;

    public function index()
    {
        $staffs = Teacher::where('type', 'committee')->get();
        return view($this->backendTemplate['template']['path_name'].'.teachers.committee.index', compact('staffs'));
    }

    public function create()
    {
        $branches     = Branch::all();
        $designations = Designation::all();
        $table_number = 1;
        return view($this->backendTemplate['template']['path_name'].'.teachers.committee.create', compact('branches', 'designations', 'table_number'));
    }

    public function getNumberOfTable(Request $request)
    {
        $table_number = $request->table_number;
        $branches = Branch::all();
        $designations = Designation::all();
        return view($this->backendTemplate['template']['path_name'].'.teachers.committee.create', compact('branches', 'designations', 'table_number'));
    }


    public function store(Request $request){

        $data = $request->all();

        if (!empty($data['check'])) {
            $count_array = count($data['check']);
            for ($i = 0; $i < $count_array; $i++) {
                $teacher                 = new Teacher();
                $teacher->id_no          = Helper::generateTeacherId();
                $teacher->name           = $data['name'][$i];
                $teacher->type           = 'committee';
                $teacher->photo          = 'default.png';
                $teacher->mobile_number  = $data['mobile_number'][$i];
                $teacher->designation_id = $data['designation_id'][$i];
                $teacher->branch_id      = $data['branch_id'][$i];
                $teacher->save();
            }
        } else {
            return redirect()->route('committee.index');
        }


        $notification = array(
            'message' =>'Successfully ',
            'alert-type' =>'success'
        );
        
        return redirect()->route('committee.index')->with($notification);
    }

    public function exportCommittee()
    {
        return Excel::download(new CommitteeExport, 'committee.xlsx');
    }

    public function edit($id)
    {
        $teacher       = Teacher::with('attendance')->find($id);
        $branches      = Branch::all();
        $designations  = Designation::all();
        $blood_groups  = Helper::blood_groups();
        return view($this->backendTemplate['template']['path_name'].'.teachers.committee.profile_update', compact('branches', 'designations', 'teacher', 'blood_groups'));
    }


    public function updateSignature(Request $request)
    {
        $teacher = Teacher::find($request['id']);

        if (@$request['signature_image']){
            
            $this->uploadFileLinode($request['signature_image'],$teacher,'signature_image','teacher');
            
            return response()->json([
                        'data' => $teacher,
                        'status' => 200,
                        'message' => 'Update Successfully'
                    ]);
        }

    }

    public function updatephoto(Request $request)
    {
        $teacher = Teacher::find($request['id']);

        if (@$request['photo']){
            
            $this->uploadFileLinode($request['photo'],$teacher,'photo','teacher');
            
            return response()->json([
                    'data' => $teacher,
                    'status' => 200,
                    'message' => 'Update Successfully'
                ]);
        }
    }

    public function update(Request $request)
    {
        $teacher = Teacher::find($request['id']);
        $teacher->update([
            'name'              => $request['name'],
            'father_name'       => $request['father_name'],
            'uuid'              => $request['unique_id'],
            'mother_name'       => $request['mother_name'],
            'gender'            => $request['gender'],
            'email'             => $request['email'],
            'mobile_number'     => $request['mobile_number'],
            'joining_date'      => $request['joining_date'],
            'leaving_date'      => $request['leaving_date'],
            'designation_id'    => $request['designation'],
            'nid'               => $request['nid'],
            'date_of_birth'     => $request['date_of_birth'],
            'blood_group'       => $request['blood_group'],
            'present_address'   => $request['present_address'],
            'permanent_address' => $request['permanent_address'],
            'nationality'       => $request['nationality'],
            'branch_id'         => $request['branch'],
        ]);


        Experience::where('teacher_id', $teacher->id)->delete();

        foreach ($request['org_name'] as $key =>  $val) {
            $experience                   = new Experience();
            $experience->teacher_id       = $teacher->id;
            $experience->org_name         = $request['org_name'][$key];
            $experience->address          = $request['address'][$key];
            $experience->org_type         = $request['org_type'][$key];
            $experience->position         = $request['position'][$key];
            $experience->responsibilities = $request['responsibilities'][$key];
            $experience->duration         = $request['duration'][$key];
            $experience->save();
        }


        Qualification::where('teacher_id', $teacher->id)->delete();

        foreach ($request['exam_title'] as $key =>  $val) {
            $qualification                   = new Qualification();
            $qualification->teacher_id       = $teacher->id;
            $qualification->exam_title       = $request['exam_title'][$key];
            $qualification->year             = $request['year'][$key];
            $qualification->university       = $request['university'][$key];
            $qualification->gpa              = $request['gpa'][$key];
            $qualification->save();
        }

        $notification = array(
            'message' =>'Committee Update Successfully ',
            'alert-type' =>'success'
        );

        return redirect()->route('committee.index')->with($notification);

    }


    public function exportCommitteePdf()
    {
        $committies = Teacher::orderBy('id_no', 'asc')->where('type','committee')->get();
        $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.teachers.committee.pdf.committee-pdf-list',compact('committies'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->set_paper('A4', 'portrait');
        return $pdf->download('committee-list'.$committies->count().'.pdf');
       // return view($this->backendTemplate['template']['path_name'].'.teachers.committee.pdf.committee-pdf-list', compact('committies'));
    }

    public function destroy($id)
    {
         Teacher::destroy($id);
         $notification = array(
            'message' =>'Committee deleted successfully ',
            'alert-type' =>'success'
        );

        return redirect()->route('committee.index')->with($notification);
    }
}
