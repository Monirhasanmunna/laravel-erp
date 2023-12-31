<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\AdmissionImport;
use App\Models\Admission;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Designation;
use App\Models\Gender;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\Religion;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\UploadAdmission;
use Illuminate\Http\Request;

use DB;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $backendTemplate = $this->backendTemplate['template']['path_name'] ;
        $admission = Admission::latest()->get();
        return view($backendTemplate.'.student.student', compact('admission'));
    }

    public function category()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Session::all();
        $sections = Section::all();

        $table_number = 0;
        $gender=Gender::all();
        $religion=Religion::all();

        $backendTemplate = $this->backendTemplate['template']['path_name'] ;
        return view($backendTemplate.'.student.admission.insert_admission',compact('academic_years','sections','table_number','gender', 'religion'));
    }
    public function uploadcreate()
    {
        $academic_years  = Session::all();
        $sections = Section::all();
        $table_number    = 0;
        $gender          = Gender::all();
        $religion        = Religion::all();
        $uploadAdmission = UploadAdmission::all();

        return view($this->backendTemplate['template']['path_name'].'.student.admission.insert_admission',compact('academic_years','sections','table_number','gender', 'religion','uploadAdmission'));
    }
    public function upload()
    {
        $academic_years = Session::all();
        $sections = Section::all();
        $table_number = 0;
        $gender=Gender::all();
        $religion=Religion::all();
        return view($this->backendTemplate['template']['path_name'].'.student.admission.upload_admission',compact('academic_years','sections','table_number','gender', 'religion'));
    }

    public function uploadstore(Request $request){
        $request->validate([
            'files_excel'=>'required|max:50000'
        ]);


        $ads = UploadAdmission::get();

        if($ads){
            foreach ($ads as $std){
                $std->delete();
            }
        }

        $datas= Excel::toArray(new AdmissionImport, $request->file('files_excel'));
        $data=$datas[0];

        for ($i=1; $i < count($data); $i++) {
            $gender = trim(strtolower($data[$i][2]));
            $relegion = trim(strtolower($data[$i][3]));

            $uploadAdmission = new UploadAdmission();
            $uploadAdmission->roll_no = $data[$i][0];
            $uploadAdmission->name = $data[$i][1];
            $uploadAdmission->gender =  ucfirst($gender);
            $uploadAdmission->religion = ucfirst($relegion);
            $uploadAdmission->father_name = $data[$i][4];
            $uploadAdmission->mother_name = $data[$i][5];
            $uploadAdmission->mobile_number = $data[$i][6];
            $uploadAdmission->save();

            // // Check if any column 0 to 6 is empty
            // $isEmptyRow = false;
            // for ($j = 0; $j <= 6; $j++) {
            //     if ($data[$i][$j] === null || trim($data[$i][$j]) === '') {
            //         $isEmptyRow = true;
            //         break;
            //     }
            // }

            // if (!$isEmptyRow) {
            //     $uploadAdmission = new UploadAdmission();
            //     $uploadAdmission->roll_no = $data[$i][0];
            //     $uploadAdmission->name = $data[$i][1];
            //     $uploadAdmission->gender =  trim(ucfirst($data[$i][2]));
            //     $uploadAdmission->religion = trim(ucfirst($data[$i][3]));
            //     $uploadAdmission->father_name = $data[$i][4];
            //     $uploadAdmission->mother_name = $data[$i][5];
            //     $uploadAdmission->mobile_number = $data[$i][6];
            //     $uploadAdmission->save();
            // }
            
        }
        

        $notification = array(
            'message' =>'Successfull',
            'alert-type' =>'success'
        );

        return redirect()->route('admission.upload.create')->with($notification);
    }



    public function getNumberOfTable(Request $request){
        $academic_years = Session::all();
        $classes = InsClass::all();

        $table_number = $request->table_number;
        // $table_number = $data['table_number'];
        $year = $request->academic_year;
        $academic_class = $request->classes;
        $gender=Gender::all();
        $religion=Religion::all();

         return view($this->backendTemplate['template']['path_name'].'.student.admission.insert_admission',compact('year','academic_class','table_number','academic_years','classes','gender', 'religion'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        foreach($request->check as $key => $val){
            if($request->roll_no[$val] != '' && $request->name[$val] != '' && $request->father_name[$val] != '' && $request->mother_name[$val] != '' && $request->mobile_number[$val] != '')
                {
                    $admissionCreate                    = new Admission();
                    $admissionCreate->admission_date    = date('Y-m-d');
                    $admissionCreate->roll_no           = $request->roll_no[$val];
                    $admissionCreate->name              = $request->name[$val];
                    $admissionCreate->father_name       = $request->father_name[$val];
                    $admissionCreate->mother_name       = $request->mother_name[$val];
                    $admissionCreate->mobile_number     = $request->mobile_number[$val];
                    $admissionCreate->religion          = $request->religion[$val];
                    $admissionCreate->gender            = $request->gender[$val];
                    $admissionCreate->class_id          = $request->class_id;
                    $admissionCreate->session_id        = $request->academic_year_id;
                    $admissionCreate->shift_id          = $request->shift_id;
                    $admissionCreate->group_id          = $request->group_id;
                    $admissionCreate->category_id       = $request->category_id;
                    $admissionCreate->section_id        = $request->section_id;
                    $admissionCreate->save();
                }else{

                    $notification = array(
                        'message' =>'Information are missing',
                        'alert-type' =>'error'
                    );

                    return redirect()->back()->with($notification);
                }
        }

        $notification = array(
            'message' =>'Students added successfully',
            'alert-type' =>'success'
        );

        return redirect()->route('admission.index')->with($notification);

    }


    public function uploadcreatestore(Request $request)
    {
        $oldStuRolls =  Student::where('class_id',$request->class_id)
                        ->where('shift_id',$request->shift_id)
                        ->where('section_id',$request->section_id)
                        ->where('category_id',$request->category_id)
                        ->where('group_id',$request->group_id)->get();

        $oldRolls = [];
        foreach ($oldStuRolls as $key => $roll) {
            array_push($oldRolls, $roll->roll_no);
        }

        
        foreach($request->check as $key => $val){
            if(!in_array($request->roll_no[$val],$oldRolls)){
                $admissionCreate = new Admission();
                $admissionCreate->roll_no = $request->roll_no[$val];
                $admissionCreate->name = $request->name[$val];
                $admissionCreate->father_name = $request->father_name[$val];
                $admissionCreate->mother_name = $request->mother_name[$val];
                $admissionCreate->mobile_number = $request->mobile_number[$val];
                $admissionCreate->religion = $request->religion[$val];
                $admissionCreate->gender = $request->gender[$val];
                $admissionCreate->class_id = $request->class_id;
                $admissionCreate->session_id = $request->academic_year_id;
                $admissionCreate->shift_id   = $request->shift_id;
                $admissionCreate->group_id=$request->group_id;
                $admissionCreate->category_id=$request->category_id;
                $admissionCreate->section_id=$request->section_id;
                $admissionCreate->save();
            }else{
                $notification = array(
                    'message' => 'Roll No. '.$request->roll_no[$val].' already used',
                    'alert-type' =>'error'
                );
        
                return redirect()->back()->with($notification);
            }
        }

        $notification = array(
            'message' =>'Students added successfully',
            'alert-type' =>'success'
        );

        return redirect()->route('admission.index')->with($notification);
    }

    public function section(Request $request)
    {
      $section  = $request->section;
      $group    = $request->group;
      $category = $request->category;
      $ids      = $request->ids;

    //   array convert string
      $string = implode(" ", $ids);

      $split =  explode (",", $string);

    //   dd($split);


      if($section){
        DB::table('admissions')->whereIn('id', $split)->update(array('section_id' => $section));

      }
      if($group){
         DB::table('admissions')->whereIn('id', $split)->update(array('group_id' => $group));
      }
      if($category){
         DB::table('admissions')->whereIn('id', $split)->update(array('category_id' => $category));
      }


        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $sections   = Section::all();
        $categoties = Category::all();
        $groups     = Group::all();
        $admission  = Admission::latest()->with('section','group','category')->get();
        // dd($admission);
        return view('category', compact('admission','sections','categoties','groups'));
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
        $admission = Admission::find($id);
        $admission->delete();

        $notification = array(
            'message' =>'Admission delete successfully',
            'alert-type' =>'success'
        );


        return redirect()->route('admission.index')->with($notification);
    }
}
