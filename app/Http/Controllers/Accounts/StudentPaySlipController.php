<?php

namespace App\Http\Controllers\Accounts;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fees;
use App\Models\FeesDetail;
use App\Models\Session;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StudentPaySlipController extends Controller
{
    public function index(){
        $academic_years = Session::all();
        $months = Helper::months();
        return view('template1.accountsmanagement.student-payslip.index',compact('academic_years','months'));
    }


    public function getStudentPaymentByMonth(Request $request){

        $data['requests'] = $request->all();

        $data["students"] = Student::where('session_id',$request->academic_year_id)
                    ->where('section_id',$request->section_id)
                    ->where('category_id',$request->category_id)
                    ->where('group_id',$request->group_id)
                    ->get()
                    ->map(function ($item) use($request){
                       return [
                         'id' => $item->id,
                         'id_no' => $item->id_no,
                         'name' => $item->name,
                         'roll_no' => $item->roll_no,
                         'months' => $request->month,
                         'class' => $item->class->name.'-'.$item->shift->name.'-'.$item->section->name,
                         'regular_fees' => $this->regularFeesCalc($item->id,$request->month),
                         'student_fees' => 0,
                         'total' => $this->regularFeesCalc($item->id,$request->month),
                       ];
                    });

        return response()->json($data);
    }


    public function regularFeesCalc($studentId,$months){
        $student = Student::find($studentId);

        $fees = Fees::where('session_id',$student->session_id)
                    ->where('class_id',$student->class_id)
                    ->where('section_id',$student->section_id)
                    ->where('category_id',$student->category_id)
                    ->where('group_id',$student->group_id)
                    ->get();
        $feesAmount = 0;
        foreach ($fees as $key => $fee){
            $amount = collect($fee->details)->whereIn('month',$months)->sum('total_amount');
            $feesAmount += $amount;
        }

        return number_format($feesAmount,2);
    }

    public function downloadInvoice($id,$months)
    {
        $months = explode(',',$months);
        $singleStudent = Student::find($id);



        $fees = Fees::where('session_id',$singleStudent->session_id)
                    ->where('class_id',$singleStudent->class_id)
                    ->where('section_id',$singleStudent->section_id)
                    ->where('category_id',$singleStudent->category_id)
                    ->where('group_id',$singleStudent->group_id)
                    ->get()
                    ->map(function ($item) use($months,$singleStudent){
                        return [
                            'description' => $item->feesType->type." ".$this->getMonthsFromFees($item->id,$months),
                            'amount' => $this->regularFeesCalcByFeesType($item->fees_type_id,$singleStudent->id,$months)
                        ];
                    });



        return view('template1.accountsmanagement.student-payslip.invoice.invoice-4',compact('fees','singleStudent'));
    }

    public function downloadInvoiceAll(Request $request){

        $months = explode(',',$request->month);

        $students = Student::where('session_id',$request->academic_year_id)
                    ->where('section_id',$request->section_id)
                    ->where('category_id',$request->category_id)
                    ->where('group_id',$request->group_id)
                    ->get()
                    ->map(function ($item) use($months){
                           return [
                               'name'     => $item->name,
                               'id_no'    => $item->id_no,
                               'roll_no'  => $item->roll_no,
                               'photo'    => $item->photo,
                               'session'  => $item->session,
                               'class'    => $item->class,
                               'shift'    => $item->shift,
                               'section'  => $item->section,
                               'category' => $item->category,
                               'group'    => $item->group,
                               'fees'     => $this->getStudentFees($item->id,$months)
                           ];
                     });
        $students = collect($students);

        return view('template1.accountsmanagement.student-payslip.invoice.invoice-4',compact('students'));

    }

    public function getStudentFees($studentId,$months){
        $student = Student::find($studentId);

        $fees = Fees::where('session_id',$student->session_id)
            ->where('class_id',$student->class_id)
            ->where('section_id',$student->section_id)
            ->where('category_id',$student->category_id)
            ->where('group_id',$student->group_id)
            ->get()
            ->map(function ($item) use($months,$student){
                return [
                    'description' => $item->feesType->type." ".$this->getMonthsFromFees($item->id,$months),
                    'amount' => $this->regularFeesCalcByFeesType($item->fees_type_id,$student->id,$months)
                ];
            });
        return $fees;

    }

    public function regularFeesCalcByFeesType($feesTypeId,$studentId,$months){

        $student = Student::find($studentId);

        $fees = Fees::where('session_id',$student->session_id)
            ->where('class_id',$student->class_id)
            ->where('section_id',$student->section_id)
            ->where('category_id',$student->category_id)
            ->where('group_id',$student->group_id)
            ->where('fees_type_id',$feesTypeId)
            ->get();
        $feesAmount = 0;
        foreach ($fees as $key => $fee){
            $amount = collect($fee->details)->whereIn('month',$months)->sum('total_amount');
            $feesAmount += $amount;
        }

        return $feesAmount;
    }


    public function getMonthsFromFees($feesId,$months)
    {
        $feesDetails = FeesDetail::where('fees_id',$feesId)->whereIn('month',$months)->get();
        $string = "";

        foreach ($feesDetails as $detail){
            $month = Helper::getMonthShortFromNumber($detail->month);
            $year = substr($detail->year, strlen($detail->year)-2);

            $string .= $month." ".$year.",";
        }

        $trimedText = rtrim($string, ",");
        $stringWithPthis = "(".$trimedText.")";
        return $stringWithPthis;
    }



}
