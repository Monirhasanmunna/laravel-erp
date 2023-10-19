<?php

namespace App\Http\Controllers\Accounts;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\Category;
use App\Models\Fees;
use App\Models\FeesDetail;
use App\Models\SchoolInfo;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentAdvanceAmount;
use App\Models\StudentDueAmount;
use App\Models\StudentFeeReceived;
use App\Models\StudentFees;
use App\Models\Transaction;
use App\Services\FeeReceivedService;
use App\Services\TransactionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Session as StdSession;

class PaymentController extends Controller {

    private $service;

    public function __construct(FeeReceivedService $feeReceivedService) {
        $this->service = $feeReceivedService;
        parent::__construct();
    }

    public function index() {
        $academic_years = StdSession::all();
        $categories = Category::all();
        $months = Helper::months();
        return view($this->backendTemplate['template']['path_name'] . '.accountsmanagement.payment.index', compact('academic_years', 'categories', 'months'));
    }


    public function store(Request $request){


        if(empty($request->fees_details_id)){
            $notification = array(
                'message' =>'Please Select Fees',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }

        if($request->fees_type === 'regular_fees')
        {
            $this->service->storeRegularFees();
        }

        if($request->fees_type === 'student_fees')
        {
            $this->service->storeStudentFees();
        }

        //notification
        $notification = array(
            'message' =>'Fee Collection Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function getStudentPayments(Request $request) {



        $section    = Section::find($request->section_id);
        $classId    = $section->ins_class_id;
        $shiftId    = $section->shift_id;
        $groupId    = $request->group_id;
        $categoryId = $request->category_id;

        $getStudents = Student::with('ins_class', 'shift', 'section', 'category','group','advanceAmount','dueAmount')
                                ->where('session_id', $request->session_id)
                                ->where('shift_id', $shiftId)
                                ->where('category_id', $categoryId)
                                ->where('group_id', $groupId)
                                ->where('section_id', $request->section_id)
                                ->where('class_id', $classId)
                                ->get()
                                ->map(function ($item) use ($request) {
                                    return [
                                        'id'            => $item->id,
                                        'id_no'         => $item->id_no,
                                        'roll_no'       => $item->roll_no,
                                        'name'          => $item->name,
                                        'month'         => $request->month,
                                        'total_payable' => $this->service->getTotalPayable($item),
                                        'paid_amount'   => $this->service->getPaidAmount($item),
                                        'discount'      => $this->service->getDiscountAmount($item),
                                        'due'           => $this->service->getDueAmount($item),
                                        'advance'       => $this->service->getAdvanceAmount($item),
                                        'status'        => $this->service->checkPaymentStatus($item),
                                    ];
                                });

        return $getStudents;
    }


    public function paymentStatus($studentId,$request,$regularFee,$studentFees){
        $totalFees   = $regularFee + $studentFees;
        $feeReceived = StudentFeeReceived::where('student_id',$studentId)->where('month',$request->month)->where('year',date('Y'))->sum('paid_amount');

        if($feeReceived == 0){
            return 'Unpaid';
        }
        elseif ($feeReceived > 0 && $totalFees > $feeReceived){
            return 'Partial Paid';
        }
        elseif ($totalFees == $feeReceived){
            return 'Paid';
        }
        elseif ($totalFees < $feeReceived){
            return 'Advance Paid';
        }
    }

    public function storeTransactions(Request $request) {

        $student = Student::find($request->student_id);

        $feeReceived = StudentFeeReceived::create([
                    'date' => date('Y-m-d'),
                    'student_id' => $request->student_id,
                    'month' => $request->month,
                    'year' => date('Y'),
                    'total' => $request->total,
                    'status' => 1
        ]);

        //get regular fees
        $regularFees = Fees::where('class_id', $student->class_id)
                        ->where('category_id', $student->category_id)
                        ->where('month', $request->month)->get();

        if ($regularFees->count() > 0) {

            foreach ($regularFees as $regularFee) {
                $regularFee->fees_received_details()->create([
                    'student_fee_received_id' => $feeReceived->id,
                    'amount' => $regularFee->total_amount
                ]);
            }
        }

        //get Student fees
        $studentFees = StudentFees::where('student_id', $student->id)->where('month', $request->month)->get();

        if ($studentFees->count() > 0) {

            foreach ($studentFees as $studentFee) {
                $studentFee->fees_received_details()->create([
                    'student_fee_received_id' => $feeReceived->id,
                    'amount' => $studentFee->total_amount
                ]);
            }
        }

        //journal entry
        $transaction = new TransactionService($feeReceived);
        $transaction->feeReceived();

        return response()->json(['status' => 200]);
    }

    /*
      |--------------------------------------------------------------------------
      | GET ALL PAYMENT DETAILS
      |--------------------------------------------------------------------------
     */

    public function viewPayments($studentId,$month)
    {
        $student    = Student::with('ins_class','shift','section')->find($studentId);
        $schoolInfo = SchoolInfo::first();

        $advanceAmount = StudentAdvanceAmount::where('student_id',$studentId)->latest()->first();
        $dueAmount = StudentDueAmount::where('student_id',$studentId)->latest()->first();

        return view('template1.accountsmanagement.payment.view',compact('student','schoolInfo','month','advanceAmount','dueAmount'));
    }


    public function viewInvoices($studentId,$month)
    {
        $student    = Student::with('ins_class','shift','section')->find($studentId);
        $studentPayments = StudentFeeReceived::where('student_id',$studentId)
                            ->where('month',$month)
                            ->get();

        return view('template1.accountsmanagement.payment.invoice-list',compact('studentPayments','month','student'));
    }

    public function getStudentPaymentList(Request $request){

        //return $request->all();
        $student = Student::find($request->student_id);

        if($request->fees_type == 'regular_fees'){

            $fees = Fees::where('session_id',$student->session_id)
                    ->where('class_id',$student->class_id)
                    ->where('section_id',$student->section_id)
                    ->where('category_id',$student->category_id)
                    ->where('group_id',$student->group_id)
                    ->get();

            $feesIds = [];
            foreach ($fees as $fee){
                array_push($feesIds,$fee->id);
            }

            //get paid fees details
            $paidPayments = StudentFeeReceived::where('student_id',$student->id)
                ->where('month',$request->month)
                ->where('year',date('Y'))
                ->get();

            $feesDetailIds = [];
            foreach ($paidPayments as $paidPayment){
                foreach ($paidPayment->feeReceivedDetails as $detail){
                    array_push($feesDetailIds,$detail->source_id);
                }
            }

            $feesDetails = FeesDetail::with('fees','fees.feesType')
                ->where('month',$request->month)
                ->where('year',date('Y'))
                ->whereNotIn('id',$feesDetailIds)
                ->whereIn('fees_id',$feesIds)
                ->get()
                ->map(function ($item){
                    return [
                        'id' => $item->id,
                        'fees_type_id' => $item->fees->feesType->id,
                        'fees_type' => $item->fees->feesType->type,
                        'amount' => $item->total_amount,
                        'fees_head' => $item->feesHead
                    ];
                });
            return $feesDetails;
        }
        else{
            $feesDetails = StudentFees::where('month',$request->month)
                            ->where('month',$request->month)
                            ->where('year',date('Y'))
                            ->where('student_id',$student->id)
                            ->get()
                            ->map(function ($item){
                                return [
                                    'id' => $item->id,
                                    'fees_type_id' => $item->feesType->id,
                                    'fees_type' => $item->feesType->type,
                                    'amount' => $item->total_amount,
                                    'fees_head' => $item->feesHead
                                ];
                            });
            return $feesDetails;

        }

    }


    public function downloadInvoice($id){

        $payment = StudentFeeReceived::with('feeReceivedDetails.feesType','feeReceivedDetails.source')->find($id);
        $schoolInfo = SchoolInfo::first();
        $academicSetting = AcademicSetting::first();

        $stdName = $payment->student->name;
        $stdName = str_replace(" ","-",$stdName);

        $pdf = Pdf::loadView('template1.accountsmanagement.payment.download-invoice',compact('payment','schoolInfo','academicSetting'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->set_paper('A4', 'portrait');
        return $pdf->download('#Inv-'.$payment->invoice_no.'-'.$stdName.'.pdf');
    }

}
