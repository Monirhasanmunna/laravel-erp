<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Helper\Helper;
use App\Models\Category;
use App\Models\Fees;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentFeeReceived;
use App\Models\StudentFees;
use App\Models\TransactionDetail;
use App\Services\FeeReceivedService;
use App\Services\FeesCalculateService;
use App\Traits\DateFormat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReportController extends Controller
{
    use DateFormat;

    private $service;
    private $feesCalculate;

    public function __construct(FeeReceivedService $feeReceivedService, FeesCalculateService $feesCalculateService)
    {
        parent::__construct();
        $this->service = $feeReceivedService;
    }

    public function index()
    {
        $sessions = Session::all();
        $sections = Section::where('institute_id', Helper::getInstituteId())->get();
        $categories = Category::all();
        $months = Helper::months();
        return view($this->backendTemplate['template']['path_name'] . '.accountsmanagement.reports.paid-report', compact('sessions', 'sections', 'categories', 'months'));
    }

    public function unpaid()
    {
        $sessions = Session::all();
        $sections = Section::where('institute_id', Helper::getInstituteId())->get();
        $categories = Category::all();
        $months = Helper::months();
        return view($this->backendTemplate['template']['path_name'] . '.accountsmanagement.reports.unpaid-report', compact('sessions', 'sections', 'categories', 'months'));
    }

    public function paidReport(Request $request)
    {

        $feesReceived = StudentFeeReceived::whereBetween('date', [$request->from_date, $request->to_date])->pluck('student_id')->toArray();
        $studentIds   = array_unique($feesReceived);

        $students = Student::whereIn('id',$studentIds)
                    ->get()
                    ->map(function ($item) use($request){
                       return [
                           'image'        => $item->photo,
                           'id_no'        => $item->id_no,
                           'name'         => $item->name,
                           'roll_no'      => $item->roll_no,
                           'class'        => $item->class->name."-".$item->shift->name."-".$item->section->name,
                           'details'      => $this->getFeesDetails($item->id,$request->all()),
                           'total_amount' => $this->caclTotalAmount($request->all(),$item->id)
                       ];
                    });


        return response()->json($students);

    }


    public function paidReportPrint(Request $request) 
    {
        $feesReceived = StudentFeeReceived::whereBetween('date', [$request->from_date, $request->to_date])->pluck('student_id')->toArray();
        $studentIds   = array_unique($feesReceived);

     
        $fromDate = date("d M Y",strtotime($request->from_date));;
        $toDate   = date("d M Y",strtotime($request->to_date));;

        $students = Student::whereIn('id',$studentIds)
                    ->get()
                    ->map(function ($item) use($request){
                       return [
                           'image'        => $item->photo,
                           'id_no'        => $item->id_no,
                           'name'         => $item->name,
                           'roll_no'      => $item->roll_no,
                           'class'        => $item->class->name."-".$item->shift->name."-".$item->section->name,
                           'details'      => $this->getFeesDetails($item->id,$request->all()),
                           'total_amount' => $this->caclTotalAmount($request->all(),$item->id)
                       ];
                    });
        return view('template1.accountsmanagement.reports.paid-report.index',compact('students','fromDate','toDate'));
    }


    public function getFeesDetails($studentId,$request){

        $feesRecs = StudentFeeReceived::where('student_id',$studentId)
                    ->whereBetween('date', [$request["from_date"], $request["to_date"]])
                    ->get();
        $detailStr = "";
        foreach ($feesRecs as $feeRec){
            foreach ($feeRec->feeReceivedDetails as $detail){
                $detailStr .= $detail->feesType->type.":".$detail->amount.', ';
            }
        }

        return rtrim($detailStr, ", ");
    }



    public function caclTotalAmount($request,$studentId){
        $amount = StudentFeeReceived::where('student_id',$studentId)
                        ->whereBetween('date', [$request["from_date"], $request["to_date"]])
                        ->sum('paid_amount');
        return $amount;
    }

    public function unpaidReport(Request $request)
    {

        $section = Section::find($request->section_id);
        $classId = $section->ins_class_id;
        $shiftId = $section->shift_id;

        $getStudents = Student::with('ins_class', 'shift', 'section', 'category')->where('session_id', $request->session_id)
                        ->where('shift_id', $shiftId)
                        ->where('section_id', $request->section_id)
                        ->where('class_id', $classId)
                        ->where('category_id', $request->category_id)
                        ->get()->map(function ($item) use ($request) {
                            return [
                                'id' => $item->id,
                                'id_no' => $item->id_no,
                                'name' => $item->name,
                                'class' => $item->ins_class->name,
                                'shift' => $item->shift->name,
                                'section' => $item->section->name,
                                'category' => $item->category->name,
                                'month' => $request->month,
                                'regular_fee' => $this->service->getRegularFees($item),
                                'student_fee' => $this->service->getStudentFees($item),
                                'total' => $this->service->getRegularFees($item) + $this->service->getStudentFees($item),
                                'status' => $this->service->checkPaymentStatus($item),
                            ];
                        });

        return response()->json($getStudents->where('status', 'Unpaid'));
    }

    public function downloadReport(Request $request, $id, $month)
    {

        $student = Student::find($id);
        // $regularFees = $this->feesCalculate->regular_fees($student, $month);

        $data = [];
        $key = 0;
        $key2 = 0;

        /*
          foreach ($regularFees as $regFee) {
          foreach ($regFee->feeDetails as $item) {
          $key++;
          $data['regular_fees'][$key] = $item;
          }
          }

          $student_fees = $this->feesCalculate->student_fees($student, $month);

          foreach ($student_fees as $studentFee) {
          foreach ($studentFee->feeDetails as $fee) {
          $key2++;
          $data['student_fees'][$key2] = $fee;
          }
          }
         *
         */



        $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'] . '.accountsmanagement.payment.invoice', compact('student', 'data'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('invoice.pdf');
    }
}
