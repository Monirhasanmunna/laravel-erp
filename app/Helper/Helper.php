<?php

namespace App\Helper;

use App\Models\AcademicSetting;
use App\Models\ClassSubject;
use App\Models\InsClass;
use App\Models\Institution;
use App\Models\InstitutionTemplate;
use App\Models\SchoolInfo;
use App\Models\Session;
use App\Models\Sms;
use App\Models\StockSms;
use App\Models\Student;
use App\Models\StudentAdvanceAmount;
use App\Models\StudentDueAmount;
use App\Models\StudentFeeReceived;
use App\Models\Teacher;
use App\Models\GeneralGrade;
use App\Models\OnlineAdmission;
use App\Models\SubMarksDist;
use App\Models\StudentMarksInput;
use App\Models\StudentMarksInputDetail;
use App\Models\studentTestimonial;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;
use Illuminate\Support\Str;

class Helper
{

    public static function getSubjectByClassId($innClass_id)
    {
        $items = ClassSubject::where('ins_class_id', $innClass_id)
            ->select('subject_id')->get();

        $subject_ids = $items->map(function ($item) {
            return $item['subject_id'];
        });

        return $subject_ids->toArray();
    }

    public static function blood_groups()
    {
        $blood_groups = ['A+', 'A-', ' B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        return $blood_groups;
    }
    public static function months()
    {
        $months = [
            '1' => "January",
            '2' => "February",
            '3' => "March",
            '4' => "April",
            '5' => "May",
            '6' => "June",
            '7' => "July",
            '8' => "August",
            '9' => "September",
            '10' => "October",
            '11' => "November",
            '12' => "December",
        ];
        return $months;
    }

    public static function getMonths(){
        $months = [
            0 => ['id' => 1,'name' => "January"],
            1 => ['id' => 2,'name' => "February"],
            2 => ['id' => 3,'name' => "March"],
            3 => ['id' => 4,'name' => "April"],
            4 => ['id' => 5,'name' => "May"],
            5 => ['id' => 6,'name' => "June"],
            6 => ['id' => 7,'name' => "July"],
            7 => ['id' => 8,'name' => "August"],
            8 => ['id' => 9,'name' => "September"],
            9 => ['id' => 10,'name' => "October"],
            10 => ['id' => 11,'name' => "November"],
            11 => ['id' => 12,'name' => "December"],

        ];
        return $months;
    }

    public static function getDays(){
        $days = [
            'Saturday',
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
        ];
        return $days;
    }


    public static function studentCurrentAdvance($studentId)
    {
        $advance = StudentAdvanceAmount::where('student_id',$studentId)->latest()->first();
        if($advance){
            return $advance->amount;
        }
    }

    public static function studentCurrentDue($studentId)
    {
        $due = StudentDueAmount::where('student_id',$studentId)->latest()->first();
        if($due){
            return $due->amount;
        }
    }

    public static function getMonthFromNumber($month)
    {

        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F'); // March
        return $monthName;
    }

    public static function getMonthShortFromNumber($month)
    {
        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('M'); // March
        return $monthName;
    }

    public static function generateTeacherId()
    {

        $teacher = Teacher::orderBy('id', 'DESC')->first();

        if ($teacher == null) {

            $fistReg = 0;
            $teacherId = $fistReg + 1;

            if ($teacherId < 10) {
                $id_no = '000' . $teacherId;
            } elseif ($teacherId < 100) {
                $id_no = '00' . $teacherId;
            } elseif ($teacherId < 1000) {
                $id_no = '0' . $teacherId;
            }

        } else {
            $teacher = Teacher::orderBy('id', 'DESC')->first()->id ?? 0;
            $teacherId = $teacher + 1;
            
            if ($teacherId < 10) {
                $id_no = '000' . $teacherId;
            } elseif ($teacherId < 100) {
                $id_no = '00' . $teacherId;
            } elseif ($teacherId < 1000) {
                $id_no = '0' . $teacherId;
            }
        }

        $final_id_no = $id_no;
        return $final_id_no;
    }



    public static function TestimonialSerialId($session_id)
    {
        $check_year     = Session::find($session_id)->title;
        $year           = substr($check_year, 2);
        $testimonial    = studentTestimonial::orderBy('id','DESC')->first();
        
        if($testimonial == null){
            $firstTestimonial = 0;
            $testimonialSerialNo = $firstTestimonial+1;

            if ($testimonialSerialNo < 10) {
                $testimonialSerialNo = '000' . $testimonialSerialNo;
            } elseif ($testimonialSerialNo < 100) {
                $testimonialSerialNo = '00' . $testimonialSerialNo;
            } elseif ($testimonialSerialNo < 1000) {
                $testimonialSerialNo = '0' . $testimonialSerialNo;
            }

        }else{
            $testimonialSerialNo = (int) @$testimonial->id + 1;

            if ($testimonialSerialNo < 10) {
                $testimonialSerialNo = '000' . $testimonialSerialNo;
            } elseif ($testimonialSerialNo < 100) {
                $testimonialSerialNo = '00' . $testimonialSerialNo;
            } elseif ($testimonialSerialNo < 1000) {
                $testimonialSerialNo = '0' . $testimonialSerialNo;
            }
        }

        $final_serial_no = $year . $testimonialSerialNo;
        return $final_serial_no;
    }




    public  static function studentIdGenerate($session_id, $class_id)
    {
        $check_year     = date("Y");
        $year           = substr($check_year, 2);
        $class_code     = self::getClassName($class_id);


        $student    = Student::where('class_id',$class_id)->orderBy('id', 'DESC')->first();

        if ($student == null) {

            $fistReg = 0;
            $studentId = $fistReg + 1;

            if ($studentId < 10) {
                $studentId = '000' . $studentId;
            } elseif ($studentId < 100) {
                $studentId = '00' . $studentId;
            } elseif ($studentId < 1000) {
                $studentId = '0' . $studentId;
            }


        } else {
           // $student = Student::orderBy('id', 'DESC')->first()->id;
            $studentId = (int) $student->id + 1;

            if ($studentId < 10) {
                $studentId = '000' . $studentId;
            } elseif ($studentId < 100) {
                $studentId = '00' . $studentId;
            } elseif ($studentId < 1000) {
                $studentId = '0' . $studentId;
            }


        }

        $final_id_no = $year . $class_code . $studentId;

        return $final_id_no;
    }



    public  static function onlineAdmissionId($session_id, $class_id)
    {
        $check_year     = Session::find($session_id)->title;
        $year           = substr($check_year, 2);
        $class_code     = self::getClassName($class_id);

        $uniqueId = random_int(0, 999);

        $date = Carbon::now();
        $month = $date->format('m');
        $day = $date->format('d');

        $id_no = $year.$class_code.$month.$day.$uniqueId;

        return $id_no;
    }




    public static function getClassName($class_id)
    {
        //dd($class_id);
        $class = InsClass::find($class_id);
        $class_code = substr($class->code, 1);
        return $class_code;
    }

    public static function default_image()
    {
        return "https://png.pngtree.com/png-vector/20210129/ourlarge/pngtree-boys-default-avatar-png-image_2854357.jpg";
    }

    public static function default_signature_image()
    {
        return asset('signature.png');
    }

    public static function default_image_female()
    {
        return asset('uploads/avater2.png');
    }

    public static function default_image_male()
    {
        return asset('uploads/boy.png');
    }


    public static function banner_image()
    {
        return asset('uploads/banner2.jpg');
    }

    public static function teacher_image()
    {
        return asset('uploads/teacher.png');
    }

    public static function video_image()
    {
        return "https://thumbs.dreamstime.com/z/blogging-blog-concepts-ideas-worktable-blogging-blog-concepts-ideas-white-worktable-110423482.jpg";
    }
    public static function ins_image($item)
    {
        return asset('uploads/institutephoto/'.$item->image);
    }

    public static function all_teacher()
    {
        return Teacher::where('institute_id', Helper::getInstituteId())->where('type','teacher')->get();
    }


    public static function all_student()
    {
        return Student::where('institute_id', Helper::getInstituteId())->get();
    }

    public static function class_subjects($id)
    {
        $subjects = InsClass::with('subjects')->findOrfail($id);
        return $subjects;
    }


    public static function getDomain(){
        $user   = Auth::user();
        $domain = $user->institute->domain;
        return $domain;
    }


    public static function getBranchId()
    {
        if(@Auth::user()->institute_branch_id){
            $branchId = Auth::user()->institute_branch_id;
        }
        elseif(@Auth::guard('teacher')->user()->institute_branch_id){
            $branchId = Auth::guard('teacher')->user()->institute_branch_id;
        }
        else{
            $branchId = null;
        }
        
        return $branchId;
    }

    public static function getInstituteId(){

        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
            $currentUrl = str_replace('www.','',$_SERVER['HTTP_HOST']) ?? env('APP_URL');
        }else{
            $currentUrl =  env('APP_URL');
        }
        $ins = Institution::where('domain',$currentUrl)->first();
        return $ins->id ?? 1;

    }
    public static function getTemplate(){

        $template =  InstitutionTemplate::with('template')->first();

        return $template->template->path_name ?? "theme1";
    }

    public static function institute_info()
    {
        return Institution::find(Helper::getInstituteId());
    }

    public static function smsBalance()
    {
        return StockSms::where('institute_id',Helper::getInstituteId())->first();
    }

    public static function academic_setting()
    {
        return AcademicSetting::where('institute_id',Helper::getInstituteId())->first();
    }


    public static function school_info()
    {
        return SchoolInfo::where('institute_id',Helper::getInstituteId())->first();
    }

    // send SMS from API

    public static function sd_send_sms_api($number, $message): bool
    {
        $sms_api_key=env('SMS_API_KEY');
        $sms_secret_key=env('SMS_SECRET_KEY');
        $sms_sender_id=env('SMS_SENDER_ID');

        $stock_sms = StockSms::where('institute_id',Helper::getInstituteId())->first();

        if($stock_sms->total_balance > 0){

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://188.138.41.146:7788/sendtext?apikey='. $sms_api_key .'&secretkey=' . $sms_secret_key . '&callerID=' . $sms_sender_id . '&toUser=' . $number . '&messageContent=' . urlencode($message),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $responseToJson = json_decode($response, true);

            curl_close($curl);

            if ($responseToJson['Text'] != "ACCEPTD" && $responseToJson['Status'] != 0) {
                return false;
            }

            return true;

        }else{
            return false;
        }

    }


    public static function getCustomerInfo(){
        return [
            'currency' => 'BDT',
            "discount_amount" => 0,
            "disc_percent" => 0,
            "customer_address" => "Dhaka",
            "customer_city" => "Dhaka",
            "customer_state" => "Dhaka",
            "customer_postcode" => 1216,
            "customer_country" => "BD"
        ];
    }


    public static function institutePaymentMethod(){
        $institute = Institution::find(self::getInstituteId());
        return $institute->payment_method;
    }


    public static function convertNumberFormat($amount){
        return number_format($amount,2);
    }



         public static function sd_shurjopay_integration($data)
         {


             $payment_url = sd_application_setting('shurjopay_test') == 'test' ? 'https://sandbox.shurjopayment.com' : 'https://engine.shurjopayment.com';
             $api_url = $payment_url . '/api/get_token';

             $curl = curl_init();

             curl_setopt_array($curl, array(
                 CURLOPT_URL => $api_url,
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 0,
                 CURLOPT_FOLLOWLOCATION => false,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('username' => sd_application_setting('shurjopay_merchant_username'),'password' => sd_application_setting('shurjopay_merchant_password'))
             ));

             $response = curl_exec($curl);

             $err = curl_error($curl);
             $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
             $curlErrorNo = curl_errno($curl);
             curl_close($curl);

             $response_json = json_decode($response, true);

             if ($code == 200 & !($curlErrorNo)) {
                 return sd_payment_curl($response_json, $data);
             }

             return 'cURL Error #:' . $err;
         }


     //Payment Curl

         public static function sd_payment_curl($response, $data)
         {
             $curl = curl_init();

             curl_setopt_array($curl, array(
                 CURLOPT_URL => $response['execute_url'],
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 0,
                 CURLOPT_FOLLOWLOCATION => false,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => array('token' => $response['token'],'store_id' => $response['store_id'],'prefix' => sd_application_setting('shurjopay_merchant_key_prefix'),'currency' => 'BDT','return_url' => route('package.payment.redirect'),'cancel_url' => url('dashboard'),'amount' => $data["package_price"],'order_id' => sd_application_setting('shurjopay_merchant_key_prefix') . sd_generate_username_prefix() . sd_generate_random_password(7),'customer_name' => $data["user"]->name,'customer_phone' => $data["user"]->mobile_number,'customer_address' => $data["customer_address"],'customer_city' => $data["customer_city"],'value1' => $data["package_info"]->id,'value2' => $data['description'],'value3' => $data["user"]->id, 'client_ip' => $data["client_ip"]),
             ));

             $res = curl_exec($curl);

             $err = curl_error($curl);
             $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
             $curlErrorNo = curl_errno($curl);
             curl_close($curl);

             $response_json = json_decode($res, true);

             if ($code == 200 & !($curlErrorNo)) {
                 return ['response' => $response_json, 'token' => $response['token']];
             }

             return 'cURL Error #:' . $err;
         }

    function getGradeValue($class, $mark, $fullmark) {
        $grade_point        = 0;
        $grade_point_letter = '';
        $grade_comment      = '';
        $mark_persentage    = ($mark / $fullmark) * 100;

        $grades = GeneralGrade::where('ins_class_id', $class)
                            ->get();

        foreach ($grades as $grade) {
            if ($mark_persentage >= $grade->range_from && $mark_persentage <= $grade->range_to){
                $grade_point        = $grade->gpa_point;
                $grade_point_letter = $grade->gpa_name;
                $grade_comment      = $grade->comment;
            }
        }

        $output = array();
        $output['gp']      = $grade_point;
        $output['gpa']     = $grade_point_letter;
        $output['comment'] = $grade_comment;
        return $output;
    }

    function getTotalGrade($class, $mark) {
        $grade_point        = 0;
        $grade_point_letter = '';
        $grade_comment      = '';

        $grades = GeneralGrade::where('ins_class_id', $class)
                            ->get();

        foreach ($grades as $grade) {
            if ($mark >= $grade->point_from && $mark <= $grade->point_to){
                $grade_point        = $grade->gpa_point;
                $grade_point_letter = $grade->gpa_name;
                $grade_comment      = $grade->comment;
            }
        }

        $output = array();
        $output['gp']      = $grade_point;
        $output['gpa']     = $grade_point_letter;
        $output['comment'] = $grade_comment;
        return $output;
    }

     public function getSubMarksDist($class, $exam, $subject)
{
    $markDist = SubMarksDist::with(['details' => function ($query) {
    $query->with('subMarkDistType');
}])
->where('class_id', $class)
->where('exam_id', $exam)
->where('class_subject_id', $subject)
->first();

$data = [];

if ($markDist) {
    $data['mark_master'] = $markDist;
    $data['mark_dists'] = $markDist->details->map(function ($detail) {
        $detailData = $detail->toArray();
        $detailData['subMarkDistType'] = $detail->subMarkDistType->toArray();
        return $detailData;
    });
} else {
    $data['mark_dists'] = null;
}

return $data;

}
 public function getStudentAtten($student_id, $from_date, $to_date){

        $attendances = Student::find($student_id)->attendance()
        ->whereBetween('date', [$from_date, $to_date])
        ->where('status', "present")
        ->with('source')->get();

    $output = array();
    $atten_count = $attendances->count();
    $output['atten_count']  = $atten_count;
    return $output;

}

public static function generateSPInvoiceNo($studentId)
{
    $lastInvoice = StudentFeeReceived::whereHas('student',function ($query){
                        $query->where('institute_id',self::getInstituteId());
                    })
                    ->latest()
                    ->first();

    if(@$lastInvoice && $lastInvoice->count() > 0){
        $invoiceNo = intval($lastInvoice->invoice_no) + 1;
    }
    else{
        $invoiceNo = 100001;
    }

    return $invoiceNo;
}


    public static function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }

     // Helper function to get mark for a specific sub type ID

function getMarkBySubtype($studentId, $detailsId, $subtypeId)
{
    $details = StudentMarksInputDetail::where('student_marks_input_id', $detailsId)
        ->where('student_id', $studentId)
        ->whereHas('subMarksDistDetails', function ($query) use ($subtypeId) {
            $query->where('sub_marks_dist_type_id', $subtypeId);
        })
        ->first();

    if ($details) {
        return $details->marks;
    }

    return "-"; // or any default value if the mark is not found
}

    public function getHighestMarks($studentMarksInputId)
{
    $result = StudentMarksInputDetail::where('student_marks_input_id', $studentMarksInputId)
        ->groupBy('student_id')
        ->selectRaw('student_marks_input_id, sum(marks) as total_marks')
        ->orderByDesc('total_marks')
        ->first();

    return $result;
}

    // Helper function to get tabulation data for a student, exam, and subject
public function getTabulation($studentId, $examId, $subjectId, $classId)
{
    $marksInput = StudentMarksInput::where('exam_id', $examId)
        ->where('class_subject_id', $subjectId)
        ->first();

    if ($marksInput) {
        $subtype = $this->getSubMarksDist($classId, $examId, $subjectId);
        $totalMarks = 0;
        $passOrFail = '';
        $classPosition = '';
        $sectionPosition = '';

        $subtotal = 0;
        $subgrage_marks = 0;
         
        if (count($subtype['mark_dists']) > 1) { 
            foreach ($subtype['mark_dists'] as $type){
        $details =$marksInput->details->where('student_marks_input_id', $marksInput->id)
                ->where('student_id', $studentId)
                ->where('sub_marks_dist_details_id', $type['id']);
        $marks = $details->count() > 0 ? $details->first()->marks : 0;
        $subtotal += $marks;
       

         if ($subtype['mark_master']->pass_mark == 0) {
            if ($marks < $type['pass_mark']) {
        $subgrage_marks = 0;
        $passOrFail = 'Fail';
        }
        else{
            $passOrFail = 'Pass';
            $subgrage_marks += $marks;
            }
        } 
         } 
         if ($subtype['mark_master']->pass_mark !== 0) {
            if ($subtotal < $subtype['mark_master']->pass_mark) {
                 $passOrFail = 'Fail';
                 $subgrage_marks = 0; 
            }else{
              $passOrFail = 'Pass';
               $subgrage_marks = $subtotal;  
            }
        }
             $ssgrades = $this->getGradeValue($classId, $subgrage_marks, $subtype['mark_master']->total_mark);
             
             $gradePoint = $ssgrades['gp'];
            $gradeName = $ssgrades['gpa'];
         
         }else{ 
            foreach ($subtype['mark_dists'] as $type){
        $details =$marksInput->details->where('student_marks_input_id', $marksInput->id)
                ->where('student_id', $studentId)
                ->where('sub_marks_dist_details_id', $type['id']);
        $marks = $details->count() > 0 ? $details->first()->marks : 0;
        $subtotal += $marks;
        if ($subtype['mark_master']->pass_mark == 0) {
            if ($marks < $type['pass_mark']) {
        $subgrage_marks = 0;
        $passOrFail = 'Fail';
        }
        else{
            $passOrFail = 'Pass';
            $subgrage_marks += $marks;
            }
        }else{

    if ($marks < $subtype['mark_master']->pass_mark) {
         $passOrFail = 'Fail';
         $subgrage_marks = 0;
         
        }else{
            $passOrFail = 'Pass';
            $subgrage_marks += $marks;
        }
    }
        $sgrades = $this->getGradeValue($classId, $subgrage_marks, $subtype['mark_master']->total_mark);
        
            
            $gradePoint = $sgrades['gp'];
            $gradeName = $sgrades['gpa'];
              } }}

  
           return [
            'totalMarks' => $totalMarks,
            'passOrFail' => $passOrFail,
            'gradePoint' => $gradePoint,
            'gradeName' => $gradeName,
            'classPosition' => $classPosition ?: 'N/A',
            'sectionPosition' => $sectionPosition ?: 'N/A',
        ];
}




}



