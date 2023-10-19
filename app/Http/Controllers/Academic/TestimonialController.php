<?php

namespace App\Http\Controllers\Academic;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\District;
use App\Models\Division;
use App\Models\Exam;
use App\Models\InsClass;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\studentTestimonial;
use App\Models\Upazila;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ViewErrorBag;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class TestimonialController extends Controller
{
    public function index()
    {
      //return  Student::where('id',4)->first()->testimonial;
        $academic_years = Session::all();
        $sections       = Section::all();
        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.index', compact('academic_years', 'sections'));
    }


    public function create($id)
    {
       // dd($id);
        $student        = Student::where('id', $id)->first();
        $divisions      = Division::all();
        $districts      = District::all();
        $upazilas       = Upazila::all();
        $sessions       = Session::all();
        $classes        = InsClass::all();
        $semesters      = Exam::all();
        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.create', compact('student','divisions','districts','upazilas','sessions','classes','semesters'));
    }

    
    public function testimonialCreate(Request $request)
    {
        
        $request->validate([
            'name'              => 'required',
            'father_name'       => 'required',
            'mother_name'       => 'required',
            'roll'              => 'required',
            'division_id'       => 'required',
            'district_id'       => 'required',
            'upazila_id'        => 'required',
            'session_id'        => 'required',
            'class_id'          => 'required',
            'semister_id'       => 'required',
            'result'            => 'required',
            'board_name'        => 'required',
            'registration_no'   => 'required'
        ]);

        //dd($request->all());
       // return Helper::TestimonialSerialId($request['session_id']);

        studentTestimonial::create([
            'student_id'        => $request->student_id,
            'name'              => $request->name,
            'father_name'       => $request->father_name,
            'mother_name'       => $request->mother_name,
            'roll'              => $request->roll,
            'division_id'       => $request->division_id,
            'district_id'       => $request->district_id,
            'upazila_id'        => $request->upazila_id,
            'session_id'        => $request->session_id,
            'class_id'          => $request->class_id,
            'semister_id'       => $request->semister_id,
            'result'            => $request->result,

            'serial_no'         => Helper::TestimonialSerialId($request['session_id']),
            'issue_date'        => date('d-m-Y'),
            'board'             => $request->board_name,
            'registration_no'   => $request->registration_no,
        ]);

        $notification = array(
            'message' =>'Testimonial Created Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('academic.testimonial.index')->with($notification);
    }



    public function edit($id)
    {
        $template       = AcademicSetting::orderBy('id','DESC')->first();
        $testimonial    = studentTestimonial::where('id', $id)->first();
        $student        = $testimonial->student;
        $divisions      = Division::all();
        $districts      = District::all();
        $upazilas       = Upazila::all();
        $sessions       = Session::all();
        $classes        = InsClass::all();
        $semesters      = Exam::all();
        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.edit', compact('testimonial','template','divisions','districts','upazilas','sessions','classes','semesters','student'));
    }



    public function testimonialUpdate(Request $request,$id)
    {
        $request->validate([
            'name'              => 'required',
            'father_name'       => 'required',
            'mother_name'       => 'required',
            'roll'              => 'required',
            'division_id'       => 'required',
            'district_id'       => 'required',
            'upazila_id'        => 'required',
            'session_id'        => 'required',
            'class_id'          => 'required',
            'semister_id'       => 'required',
            'result'            => 'required',
            'board_name'        => 'required',
            'registration_no'   => 'required',
        ]);

      //  dd($request->all());

        studentTestimonial::find($id)->update([
            'student_id'        => $request->student_id,
            'name'              => $request->name,
            'father_name'       => $request->father_name,
            'mother_name'       => $request->mother_name,
            'roll'              => $request->roll,
            'division_id'       => $request->division_id,
            'district_id'       => $request->district_id,
            'upazila_id'        => $request->upazila_id,
            'session_id'        => $request->session_id,
            'class_id'          => $request->class_id,
            'semister_id'       => $request->semister_id,
            'result'            => $request->result,
            'serial_no'         => $request->serial_no ?? Helper::TestimonialSerialId($request['session_id']),
            'board'             => $request->board_name,
            'registration_no'   => $request->registration_no,
        ]);

        $notification = array(
            'message' =>'Testimonial Update Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('academic.testimonial.index')->with($notification);
    }



    public function testimonialPrint($id)
    {
        $testimonial        = studentTestimonial::where('id',$id)->first();
        $student            = $testimonial->student;
        $template           = AcademicSetting::orderBy('id','DESC')->first();
        
        $session            = $testimonial->session;
        $class              = $testimonial->class;
        $division           = $testimonial->division;
        $district           = $testimonial->district;
        $upazila            = $testimonial->upazila;

        $name               = $testimonial->name;
        $fName              = $testimonial->father_name;
        $MName              = $testimonial->mother_name;
        $roll               = $testimonial->roll;
        $result             = $testimonial->result;
        $serial_no          = $testimonial->serial_no;
        $board_name         = $testimonial->board;
        $issue_date         = $testimonial->issue_date;
        $registration_no    = $testimonial->registration_no;

        $group              = $student->group->name;
        $birth_date         = $student->dob;

        $find = [':name',':father_name',':mother_name', ':roll' ,':division',':district',':upazila',':class', ':session', ':result', ':group', ':birth_date', ':board_name', ':registration_no'];
        $replacement = [@$name, @$fName, @$MName, @$roll, @$division->name, @$district->name, @$upazila->name, @$class->name, @$session->title, @$result, @$group, @$birth_date, @$board_name, @$registration_no];
        $content = str_replace($find, $replacement, $template->testimonial_content);

        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.demo', compact('content','student','serial_no','issue_date'));
    }

    // public function update(Request $request)
    // {
    //     $student    = Student::where('id',$request->student_id)->first();
    //     $template   = AcademicSetting::orderBy('id','DESC')->first();
    //     $session    = Session::where('id',$request->session_id)->first();
    //     $class      = InsClass::where('id',$request->class_id)->first();
    //     $division   = Division::where('id',$request->division_id)->first();
    //     $district   = District::where('id',$request->district_id)->first();
    //     $upazila    = Upazila::where('id',$request->upazila_id)->first();

    //     $name       = $request->name;
    //     $fName      = $request->father_name;
    //     $MName      = $request->mother_name;
    //     $roll       = $request->roll_no;
    //     $result     = $request->result;

    //     $find = [':name',':father_name',':mother_name', ':roll' ,':division',':district',':upazila',':class', ':session', ':result'];
    //     $replacement = [@$name, @$fName, @$MName, @$roll, @$division->name, @$district->name, @$upazila->name, @$class->name, @$session->title, @$result];
    //     $content = str_replace($find, $replacement, $template->testimonial_content);

    //     return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.print', compact('content','student'));
    // }

    public function view($id)
    {
        $template = AcademicSetting::where('institute_id',Helper::getInstituteId())->first();
        $student = Student::find($id);
        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.view', compact('student','template'));
    }


    public function downloadCard($id){

        $student = Student::find($id);              
        
        $template = AcademicSetting::where('institute_id',Helper::getInstituteId())->first();

        // $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.academic.testimonial.download-pdf', compact('student','template'))->setOptions(['defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        // $pdf->set_paper('A4','landscape');
        // return $pdf->download('student-'.$student->id_no.'-testimonial.pdf');
        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.download-pdf', compact('student','template'));
    }

    public function allDownload(Request $request)
    {
        $student_id = $request->student_id;
        $students   = Student::whereIn('id', $student_id)->get();               
        $template = AcademicSetting::where('institute_id',Helper::getInstituteId())->first();

        // $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.academic.testimonial.download-pdf', compact('students','template'))->setOptions(['defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        // $pdf->set_paper('A4','landscape');
        // return $pdf->download('student-'.'123'.'-testimonial.pdf');
        return view($this->backendTemplate['template']['path_name'].'.academic.testimonial.download-pdf', compact('students','template'));
    }
}
