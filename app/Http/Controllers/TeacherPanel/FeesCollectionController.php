<?php

namespace App\Http\Controllers\TeacherPanel;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Fees;
use App\Models\FeesDetail;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\SchoolInfo;
use App\Models\Section;
use App\Models\Session;
use App\Models\Shift;
use App\Models\Student;
use App\Models\StudentAdvanceAmount;
use App\Models\StudentDueAmount;
use App\Models\StudentFeeReceived;
use App\Models\StudentFees;
use App\Models\TeacherSectionAssign;
use App\Services\FeeReceivedService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class FeesCollectionController extends Controller
{

    private $service;

    public function __construct(FeeReceivedService $feeReceivedService)
    {
        $this->service = $feeReceivedService;
        parent::__construct();
    }

    public function index()
    {
        $teacherId  = Auth::user()->teacher_id;
        $sessions   = TeacherSectionAssign::where('teacher_id',$teacherId)->get()->pluck('session_id');
        $sessionids = array_unique($sessions->toArray());
     
        $academic_years = Session::whereIn('id',$sessionids)->get();
        $months = Helper::months();
        return view('teacherpanel.account-management.collection.index', compact('academic_years', 'months'));
    }


    public function store(Request $request)
    {


        if (empty($request->fees_details_id)) {
            $notification = array(
                'message' => 'Please Select Fees',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        if ($request->fees_type === 'regular_fees') {
            $this->service->storeRegularFees();
        }

        if ($request->fees_type === 'student_fees') {
            $this->service->storeStudentFees();
        }

        //notification
        $notification = array(
            'message' => 'Fee Collection Successfully ',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function getSections(Request $request)
    {
        $session   =  Session::with('section')->find($request->session_id);

        $teacherId    = Auth::user()->teacher_id;
        $sectionIds   = TeacherSectionAssign::where('teacher_id',$teacherId)->where('session_id',$request->session_id)->get()->pluck('section_id');
       
        

        $data['sections']  =  Section::whereIn('id',$sectionIds)->get()->map(function ($item) {
            return [
                'id'    => $item->id,
                'name'  => $item->name,
                'shift' => $this->getShiftById($item->shift_id),
                'class' => $this->getClassById($item->ins_class_id),
            ];
        });
        $data['exams']   = Exam::where('session_id', $session->id)->get();

        return $data;
    }

    public function getShiftById($id)
    {
        $shift = Shift::find($id);
        return $shift->name;
    }

    public function getClassById($id)
    {
        $class = InsClass::find($id);
        return $class->name;
    }


    public function getCatGroup(Request $request)
    {
        $data['category'] = Category::where('section_id', $request->section_id)->get();
        $data['group']    = Group::where('section_id', $request->section_id)->get();
        return $data;
    }


    public function getStudentPayments(Request $request)
    {



        $section    = Section::find($request->section_id);
        $classId    = $section->ins_class_id;
        $shiftId    = $section->shift_id;
        $groupId    = $request->group_id;
        $categoryId = $request->category_id;

        $getStudents = Student::with('ins_class', 'shift', 'section', 'category', 'group', 'advanceAmount', 'dueAmount')
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


    public function viewPayments($studentId, $month)
    {
        $student    = Student::with('ins_class', 'shift', 'section')->find($studentId);
        $schoolInfo = SchoolInfo::first();

        $advanceAmount = StudentAdvanceAmount::where('student_id', $studentId)->latest()->first();
        $dueAmount = StudentDueAmount::where('student_id', $studentId)->latest()->first();

        return view('teacherpanel.account-management.collection.view', compact('student', 'schoolInfo', 'month', 'advanceAmount', 'dueAmount'));
    }


    public function viewInvoices($studentId, $month)
    {
        $student    = Student::with('ins_class', 'shift', 'section')->find($studentId);
        $studentPayments = StudentFeeReceived::where('student_id', $studentId)
            ->where('month', $month)
            ->get();

        return view('teacherpanel.account-management.collection.invoice-list', compact('studentPayments', 'month', 'student'));
    }


    public function getStudentPaymentList(Request $request)
    {

        //return $request->all();
        $student = Student::find($request->student_id);

        if ($request->fees_type == 'regular_fees') {

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

            //get paid fees details
            $paidPayments = StudentFeeReceived::where('student_id', $student->id)
                ->where('month', $request->month)
                ->where('year', date('Y'))
                ->get();

            $feesDetailIds = [];
            foreach ($paidPayments as $paidPayment) {
                foreach ($paidPayment->feeReceivedDetails as $detail) {
                    array_push($feesDetailIds, $detail->source_id);
                }
            }

            $feesDetails = FeesDetail::with('fees', 'fees.feesType')
                ->where('month', $request->month)
                ->where('year', date('Y'))
                ->whereNotIn('id', $feesDetailIds)
                ->whereIn('fees_id', $feesIds)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'fees_type_id' => $item->fees->feesType->id,
                        'fees_type' => $item->fees->feesType->type,
                        'amount' => $item->total_amount,
                        'fees_head' => $item->feesHead
                    ];
                });
            return $feesDetails;
        } else {
            $feesDetails = StudentFees::where('month', $request->month)
                ->where('month', $request->month)
                ->where('year', date('Y'))
                ->where('student_id', $student->id)
                ->get()
                ->map(function ($item) {
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

    public function downloadInvoice($id)
    {

        $payment         = StudentFeeReceived::with('feeReceivedDetails.feesType', 'feeReceivedDetails.source')->find($id);
        $schoolInfo      = SchoolInfo::first();
        $academicSetting = AcademicSetting::first();

        $stdName = $payment->student->name;
        $stdName = str_replace(" ", "-", $stdName);

        $pdf = Pdf::loadView('teacherpanel.account-management.collection.download-invoice', compact('payment', 'schoolInfo', 'academicSetting'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->set_paper('A4', 'portrait');
        return $pdf->download('#Inv-' . $payment->invoice_no . '-' . $stdName . '.pdf');
    }
}
