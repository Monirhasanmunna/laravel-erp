<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\Fees;
use App\Models\FeesDetail;
use App\Models\Notification;
use App\Models\Student;
use App\Models\StudentAdvanceAmount;
use App\Models\StudentDueAmount;
use App\Models\StudentFeeReceived;
use App\Models\StudentFees;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;

class FeeReceivedService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /*
     |--------------------------------------------------------------------------
     | INSERT REFULAR FEES
     |--------------------------------------------------------------------------
     */
    public function storeRegularFees()
    {


        $advance = '';
        $due     = '';
        $paymentStatus = 'paid';


        if ($this->request->total_payable < $this->request->paid_amount) {
            $advance = $this->request->paid_amount - $this->request->total_payable;
        }
        if ($this->request->total_payable > $this->request->paid_amount) {
            $due = $this->request->total_payable - $this->request->paid_amount;
        }

        //set Payment Status
        if ($advance > 0) {
            $paymentStatus = 'advance-paid';
            $this->studentAdvanceCreate($advance);
        } else {
            $this->studentAdvanceCreate($advance);
        }

        if ($due > 0) {
            $paymentStatus = 'partial-paid';
            $this->studentDueCreate($due);
        } else {
            $this->studentDueCreate($due);
        }


        $studentFeeReceived = StudentFeeReceived::create([
            'invoice_no'    => Helper::generateSPInvoiceNo($this->request->student_id),
            'date'          => date('Y-m-d'),
            'student_id'    => $this->request->student_id,
            'year'          => date('Y'),
            'month'         => $this->request->month,
            'invoice_total' => $this->request->invoice_total,
            'discount'      => $this->request->discount,
            'advance'       => $advance ?? 0,
            'due_amount'    => $due ?? 0,
            'total_payable' => $this->request->total_payable,
            'paid_amount'   => $this->request->paid_amount,
            'status'        => $paymentStatus

        ]);

        //fees received details create
        foreach ($this->request->fees_details_id as $key => $feeDetailsId) {

            $feeDetails = FeesDetail::find($feeDetailsId);

            $feeDetails->feesPayment()->create([
                'student_fee_received_id' =>  $studentFeeReceived->id,
                'fees_type_id'            =>  $this->request->fees_type_id[$key],
                'amount'                  =>  $this->request->amount[$key]
            ]);
        }


        $smsNotification = Notification::where('type','payment-collection')->first();
        if(@$smsNotification && $smsNotification->status == 1){
            $student = Student::find($this->request->student_id);
            //get smsm content
            $smsContentService = new SmsContentService();
            $content = $smsContentService->feeCollectionContent($studentFeeReceived);
            //sms service
            $smsmService = new SmsService($student->institute_id,$student->institute_branch_id);
            $smsmService->sendSms("Fees Collection",$student->mobile_number,$content);
        }




    }

    public function storeStudentFees()
    {

        $advance = '';
        $due     = '';
        $paymentStatus = 'paid';


        if ($this->request->total_payable < $this->request->paid_amount) {
            $advance = $this->request->paid_amount - $this->request->total_payable;
        }
        if ($this->request->total_payable > $this->request->paid_amount) {
            $due = $this->request->total_payable - $this->request->paid_amount;
        }

        //set Payment Status
        if ($advance > 0) {
            $paymentStatus = 'advance-paid';
            $this->studentAdvanceCreate($advance);
        } else {
            $this->studentAdvanceCreate($advance);
        }

        if ($due > 0) {
            $paymentStatus = 'partial-paid';
            $this->studentDueCreate($due);
        } else {
            $this->studentDueCreate($due);
        }

        $studentFeeReceived = StudentFeeReceived::create([
            'invoice_no'    => Helper::generateSPInvoiceNo($this->request->student_id),
            'date'          => date('Y-m-d'),
            'student_id'    => $this->request->student_id,
            'year'          => date('Y'),
            'month'         => $this->request->month,
            'invoice_total' => $this->request->invoice_total,
            'discount'      => $this->request->discount,
            'advance'       => $advance ?? 0.00,
            'due_amount'    => $due ?? 0.00,
            'total_payable' => $this->request->total_payable,
            'paid_amount'   => $this->request->paid_amount,
            'status'        => $paymentStatus

        ]);

        //fees received details create
        foreach ($this->request->fees_details_id as $key => $feeDetailsId) {

            $feeDetails = StudentFees::find($feeDetailsId);

            $feeDetails->feesPayment()->create([
                'student_fee_received_id' =>  $studentFeeReceived->id,
                'fees_type_id'            =>  $this->request->fees_type_id[$key],
                'amount'                  =>  $this->request->amount[$key]
            ]);
        }
    }

    public function studentAdvanceCreate($advance)
    {

        StudentAdvanceAmount::create([
            'student_id' => $this->request->student_id,
            'month'      => $this->request->month,
            'year'       => date('Y'),
            'amount'     =>  $advance
        ]);
    }

    public function studentDueCreate($due)
    {

        StudentDueAmount::create([
            'student_id' => $this->request->student_id,
            'month'      => $this->request->month,
            'year'       => date('Y'),
            'amount'     =>  $due
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET TOTAL PAYABLE
    |--------------------------------------------------------------------------
    */

    public function getTotalPayable($student)
    {
        $regFees = $this->getRegularFees($student);
        $stdFees = $this->getStudentFees($student);

        $totalPayable = $regFees + $stdFees;
        return $totalPayable;
    }


    public function getPaidAmount($student)
    {
        $studentFeeReceived = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('session_id', $this->request->session_id);
        })
            ->where('student_id', $student->id)
            ->where('month', $this->request->month)
            ->sum('paid_amount');
        return $studentFeeReceived;
    }

    public function getDiscountAmount($student)
    {
        $studentFeeReceived = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('session_id', $this->request->session_id);
        })
            ->where('student_id', $student->id)
            ->where('month', $this->request->month)
            ->sum('discount');
        return $studentFeeReceived;
    }


    public function getDueAmount($student)
    {
        $payable        = $this->getTotalPayable($student);

        $paidAmount     = $this->getPaidAmount($student);
        $discountAmount = $this->getDiscountAmount($student);

        $paid           = ($paidAmount + $discountAmount);

        if($paid < $payable){
            $due =  $payable - $paid;
        }
        else{
            $due = 0;
        }

        return $due;
    }

    public function getAdvanceAmount($student)
    {
        $payable        = $this->getTotalPayable($student);

        $paidAmount     = $this->getPaidAmount($student);
        $discountAmount = $this->getDiscountAmount($student);

        $paid           = ($paidAmount + $discountAmount);

        if($paid > $payable){
            $advance = $paid - $payable;
        }
        else{
            $advance = 0;
        }

        return $advance;
    }


    public function checkPaymentStatus($student)
    {
        $due      = $this->getDueAmount($student);
        $advance  = $this->getAdvanceAmount($student);

        $payable  = $this->getTotalPayable($student);

        $paid     = $this->getPaidAmount($student);
        $discount = $this->getDiscountAmount($student);

        $totalPaid = $paid + $discount;

        $status = "";

        if($payable == $totalPaid){
            $status = "Paid";
        }

        if($payable > $totalPaid && $totalPaid > 0){
            $status = "Partial Paid";
        }

        if($payable < $totalPaid){
            $status = "Advance Paid";
        }

        if($totalPaid == 0){
            $status = "Unpaid";
        }


        return $status;

    }

    /*
    |--------------------------------------------------------------------------
    | GET REGULAR FEES SUM
    |--------------------------------------------------------------------------
    */

    public function getRegularFees($student)
    {

        $fees = Fees::where('session_id', $student->session_id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('category_id', $student->category_id)
            ->where('group_id', $student->group_id)
            ->get();
        $feesIds = [];
        foreach ($fees as $fee) {
            array_push($feesIds, $fee->id);
        }

        $feesDetails = FeesDetail::where('month', $this->request->month)
            ->whereIn('fees_id', $feesIds)
            ->sum('total_amount');

        return $feesDetails;
    }

    /*
    |--------------------------------------------------------------------------
    | GET STUDENT FEES SUM
    |--------------------------------------------------------------------------
    */

    public function getStudentFees($student)
    {
        $fees = StudentFees::where('student_id', $student->id)
            ->where('month', $this->request->month)
            ->sum('total_amount');
        return $fees;
    }

    /*
    |--------------------------------------------------------------------------
    | Check Student Payment Status
    |--------------------------------------------------------------------------
    */

}
