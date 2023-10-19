<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Ataglance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FrontLanding;
use App\Models\Notice;
use App\Models\Banner;
use App\Models\Video;
use App\Models\Event;
use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\Institutephoto;
use App\Models\Sociallink;
use App\Models\Messages;
use App\Models\SchoolInfo;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Page;
use App\Models\PeriodTime;
use App\Models\Subject;

use App\Models\ClassRoutine as Routine;
use App\Models\ContactUs;
use App\Models\District;
use App\Models\Division;
use App\Models\Institution;
use App\Models\MeritStudent;
use App\Models\News;
use App\Models\Session;
use App\Models\Upazila;
use App\Models\Website;
use App\Traits\FileSaver;
use Carbon\Carbon;

class WebsiteController extends Controller
{
    use FileSaver;
    public $template = 'theme1';

    public function __construct()
    {
        parent::__construct();
        $path_name = Helper::getTemplate();

        if($path_name){
            $this->template = $path_name;
        }
    }

    public function index()
    {
        $data['banners']       = Banner::orderBy('sl_no','ASC')->get();
        $data['notices']       = Notice::latest('id')->get();
        $data['videos']        = Video::latest('id')->get();
        $data['events']        = Event::latest('id')->get();
        $data['ins_photos']    = Institutephoto::latest('id')->get();
        $data['social_links']  = Sociallink::find(1);
        $data['messages']      = Messages::where('status', 1)->get();
        $data['info']          = SchoolInfo::where('institute_id',Helper::getInstituteId())->first();
        $data['teachers']      = Teacher::all();
        $data['students']      = Student::all();
        $data['ataglance']     = Ataglance::orderBy('created_at','DESC')->first();
        $data['meritstudent']  = MeritStudent::with('student')->get()->take(5);

        

        $seller                = Institution::with('seller')->find(Helper::getInstituteId());


        $data['sellerName']    = $seller->seller->name ?? "Seller";

        return view("frontent.".$this->template.".index",$data);

    }


    public function onlineAdmission()
    {
        $data['sessions']      = Session::all(); 
        $data['classes']       = InsClass::all();
        $data['divisions']     = Division::all();
        $data['districts']     = District::all();
        $data['upazilas']      = Upazila::all();

        return view("frontent.".$this->template.".online_admission",$data);
    }


    public function getGroup(Request $request){

        $class_id = $request->class_id;
        $data = Group::where('ins_class_id',$class_id)->get();
        return response()->json($data);
    }
    public function getStudent(Request $request){
        $query = Student::with('class','group');
        if($request->class_id){
            $query->where('class_id',$request->class_id);
        }
        if($request->group_id){
            $query->where('group_id',$request->group_id);
        }
        $data = $query->get();
        return response()->json($data);
    }



    public function merit_student_list()
    {
        $meritStudents = MeritStudent::all();
        return view("frontent.".$this->template.".merit_student",compact('meritStudents'));
    }

    public function gallery()
    {
        $galleries = Institutephoto::all();
        return view("frontent.".$this->template.".gallery",compact('galleries'));
    }


    public function student_list(Request $request)
    {
        if($request->search){
            $students = Student::where('class_id',$request->class_id)->where('group_id',$request->group_id)->get();
            // $student  = $students->find(1);
            $data['students'] = $students;
            $data['class']    = InsClass::where('id',$request->class_id)->first()->name;
            $data['group']    = Group::where('ins_class_id',$request->class_id)->first()->name;
            $data['section']  = Section::where('ins_class_id',$request->class_id)->first()->name;
        }
        $data['classes']       = InsClass::all();
        $data['groups']        = Group::all();
        $data['social_links']  = Sociallink::find(1);

        $data['academic_years'] = Session::all();
        $data['sections']       = Section::all();

        return view("frontent.".$this->template.".student_list",$data);
    }


    public function staff_list(){

        $data['social_links']  = Sociallink::find(1);
        $staffs = Teacher::where('type', 'staff')->get();

        return view("frontent.".$this->template.".staff_list",compact('staffs'),$data);

    }

    public function getRoutine(Request $request)
    {
        $groups    = Group::all();
        $subjects  = Subject::all();
        $periods   = PeriodTime::all();
        $teachers  = Teacher::all();
        $ins_class  = InsClass::all();
        $routine  = Routine::all();
        $sections= Section::all();

        return view("frontent.".$this->template.".class_routine",compact( 'sections', 'groups', 'subjects', 'periods', 'teachers','routine'));
    }


    public function exam_routine(Request $request)
    {
        $session  = $request->session;
        $class    = $request->class;
        $exam     = $request->exam;

        $routine = new ExamRoutine();
        $routine = $routine->where('session_id',$session);
        $routine = $routine->where('ins_class_id',$class);
        $routine = $routine->where('exam_id',$exam);
        $routine = $routine->first();

        return view("frontent.".$this->template.".exam_routine",compact('routine','exam','class'));
    }

    public function book_list()
    {
        return view("frontent.".$this->template.".book_list");
    }

    public function result()
    {
        return view("frontent.".$this->template.".result");
    }

    public function notice()
    {
        $notice= Notice::all();
        return view("frontent.".$this->template.".notice",compact('notice'));
    }

    public function governing_body()
    {
        $committees = Teacher::where('type', 'committee')->get();
        return view("frontent.theme1.governing_body",compact('committees'));
    }


    public function Message($slug)
    {
        $msg = Messages::where('institute_id',Helper::getInstituteId())->where('slug',$slug)->where('status',1)->first();
        return view("frontent.".$this->template.".chairman_msg",compact('msg'));
    }


    // public function principal_msg(){

    //     return view ('frontent.theme1.principal_msg');
    // }

    public function teacher_list()
    {

        $teachers = Teacher::where('type', 'teacher')->where('institute_id',Helper::getInstituteId())->get();
        $data['social_links']  = Sociallink::find(1);

        return view("frontent.".$this->template.".teacher_list",compact('teachers'),$data);
    }

    public function at_a_glance()
    {
        $ataglance=Page::where('id',10)->first();
        return view("frontent.".$this->template.".at_a_glance",compact('ataglance'));
    }

    public function school_history()
    {
        return view("frontent.".$this->template.".school_history");
    }

    public function why_study()
    {
        return view("frontent.".$this->template.".why_study");
    }

    public function infrastructure()
    {
        return view("frontent.".$this->template.".infrastructure");
    }

    public function achievement()
    {
        return view("frontent.".$this->template.".achievement");
    }

    public function news()
    {
        return view("frontent.".$this->template.".news");
    }

    public function digital_content()
    {
        return view("frontent.".$this->template.".digital_content");
    }

    public function hand_book()
    {
        return view("frontent.".$this->template.".hand_book");
    }

    public function home_work()
    {
        return view("frontent.".$this->template.".home_work");
    }
    public function class_note()
    {
        return view("frontent.".$this->template.".class_note");
    }

    public function other_download()
    {
        return view("frontent.".$this->template.".other_download");
    }

    public function contact_us()
    {
        return view("frontent.".$this->template.".contact_us");
    }

    public function contact_us_store(Request $request){

        $message = new ContactUs();

        $message->name=$request->name;
        $message->email=$request->email;
        $message->message=$request->message;
        if( $request->status == 1){
            $message->status = true;
        }else{
            $message->status = false;
        }

        $message->save();
        return redirect()->back();
    }

    public function sport()
    {
        return view("frontent.".$this->template.".sport");
    }

    public function scout()
    {
        return view("frontent.".$this->template.".scout");
    }

    public function tour()
    {
        return view("frontent.".$this->template.".tour");
    }

    public function noticeShow($id)
    {
        $notice = Notice::findOrfail($id);
        return view("frontent.".$this->template.".noticeshow",compact('notice'));
    }

    public function eventShow($id)
    {
        $event = Event::findOrfail($id);
        return view("frontent.".$this->template.".noticeshow",compact('event'));
    }

    public function Messages($id)
    {
        $msg = Messages::where('id',$id)->where('status',1)->first();
        return view("frontent.".$this->template.".messeges",compact('msg'));
    }

    public function setupCheck(){
        return User::where('institute_id',Helper::getInstituteId())->count();
    }
    public function expiredCheck(){

        if(@$_SERVER['HTTP_HOST']){
            $currentUrl = str_replace('www.','',$_SERVER['HTTP_HOST']);
        }else{
            $currentUrl='127.0.0.1:8000';
        }
        $domain = Institution::with('package')->where('domain', $currentUrl)->first();

        $data = [];
        if (!empty($domain)) {

            $data['paymentMethod'] = $domain->payment_method;

            if ($domain->status != 'active') {
                $data['msgHead'] = "YOUR APPLICATION IS UNDER REVIEW";
                $data['msgBody'] = "Please be with us, we will get back soon (Please Contact with Administrator).";
                $data['expired']  = true;
            }

            $today   = Carbon::today();
            $expire_date = Institution::where('domain', $currentUrl)->selectRaw('GREATEST(`expire_date`,`trial_period_end`) as expire')->first();
           
            $expiredOrdDate = Carbon::parse($expire_date->expire);
     
       

            if($today->lte($expiredOrdDate)){
                $data['expired'] = false;
            }
            else{
                $data['msgHead'] = "YOUR PAYMENT DATE IS EXPIRED";
                $data['msgBody'] = "Please pay as soon as possible to continue your website & application.";
                $data['expired']  = true;
            }
        }

        return response()->json($data);
    }

    public function expiredDayCount(){
        if(@$_SERVER['HTTP_HOST']){
            $currentUrl = str_replace('www.','',$_SERVER['HTTP_HOST']);
        }else{
            $currentUrl='127.0.0.1:8000';
        }
        $domain = Institution::where('domain', $currentUrl)->first();
        $today   = Carbon::now();

        $start =  Carbon::parse($today);
        $end   =  Carbon::parse($domain->expire_date);

        $difDay = $end->diffInDays($start) + 1;

        if($difDay < 7){
            return $difDay;
        }

    }
}
