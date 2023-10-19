<?php

namespace App\Http\Controllers\Accounts;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\Category;
use App\Models\Fees;
use App\Models\Group;
use App\Models\InsClass;
use App\Models\SchoolInfo;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentFeeReceived;
use App\Traits\DownloadInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;

class InvoiceReportController extends Controller
{
    use DownloadInvoice;

    public function index()
    {
        return view('template1.accountsmanagement.reports.invoice.date-to-date-report');
    }


    public function getInvoices(Request $request)
    {

        $studentPayments = StudentFeeReceived::with('student')
            ->whereHas('student', function ($q) {
                $q->where('institute_id', Helper::getInstituteId())->where('institute_branch_id', Helper::getBranchId());
            })
            ->whereBetween('date', [$request->from_date, $request->to_date])
            ->get();

        return response()->json($studentPayments);
    }


    public function store(Request $request)
    {
        $feeRecIds = explode(',', $request->fee_received_ids[0]);

        $ids = [];
        foreach ($feeRecIds as $id) {

            $payment = StudentFeeReceived::with('feeReceivedDetails.feesType', 'feeReceivedDetails.source')->find($id);
            $schoolInfo = SchoolInfo::first();
            $academicSetting = AcademicSetting::first();

            $stdName = $payment->student->name;
            $stdName = str_replace(" ", "-", $stdName);

            $pdf = Pdf::loadView('template1.accountsmanagement.payment.download-invoice', compact('payment', 'schoolInfo', 'academicSetting'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $pdf->set_paper('A4', 'portrait');
            return $pdf->download('#Inv-' . $payment->invoice_no . '-' . $stdName . '.pdf');
        }

        return redirect()->back();
    }


    public function yearlyReport()
    {
        $academic_years = Session::all();
        return view('template1.accountsmanagement.reports.yearly-report.index', compact('academic_years'));
    }


    public function getYearlyReport(Request $request)
    {
        $data['students'] = Student::where('session_id', $request->academic_year_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->get()
            ->map(function ($item) use ($request) {
                return [
                    'id'        => $item->id,
                    'id_no'           => $item->id_no,
                    'name'            => $item->name,
                    'roll_no'         => $item->roll_no,
                    'months'          => $this->getYearlyPayable($item),
                    'payable'         => $this->calTotalPayable($item),
                    'paid'            => array_sum($this->getYearlyPayable($item)),
                    'due'             => $this->calcDueAmount($item),
                    'advance'         => $this->calcAdvanceAmount($item)
                ];
            });

        $data['totalPayable'] = $data['students']->sum('payable');
        $data['totalPaid']    = $data['students']->sum('paid');
        $data['totalDue']     = $data['students']->sum('due');
        $data['totalAdvance'] = $data['students']->sum('advance');

        $monthsArray = [];

        foreach ($data['students'] as  $student) {
            foreach ($student['months'] as $key => $month) {
                $monthsArray[$key][] = $month;
            }
        }

        $monthAmoountArray = [];
        foreach ($monthsArray as $key =>  $monthAmount) {
            $monthAmoountArray[$key] = array_sum($monthAmount);
        }

        $data['monthAmoountArray'] = $monthAmoountArray;

        return response()->json($data);
    }



    public function calTotalPayable($student)
    {
        $matchThese = [
            'session_id'  => $student->session_id,
            'class_id'    => $student->class_id,
            'section_id'  => $student->section_id,
            'category_id' => $student->category_id,
            'group_id'    => $student->group_id
        ];
        $fees = Fees::where($matchThese)->sum('total');
        return $fees;
    }


    public function getYearlyPayable($student)
    {

        $months = Helper::getMonths();

        $array = [];

        foreach ($months as $month) {
            $totalAmount = StudentFeeReceived::where('student_id', $student->id)->where('month', $month['id'])->sum('paid_amount');
            $array[$month['name']] = $totalAmount;
        }

        return $array;
    }


    public function yearlyReportPrint($req)
    {
        $requests = explode('&', $req);

        $request = [];

        foreach ($requests as $requ) {
            $exReq = explode('=', $requ);
            $request[$exReq[0]] = $exReq[1];
        }

        $data['students'] = Student::where('session_id', $request['academic_year_id'])
            ->where('section_id', $request['section_id'])
            ->where('category_id', $request['category_id'])
            ->where('group_id', $request['group_id'])
            ->get()
            ->map(function ($item) use ($request) {
                return [
                    'id'        => $item->id,
                    'id_no'           => $item->id_no,
                    'name'            => $item->name,
                    'roll_no'         => $item->roll_no,
                    'months'          => $this->getYearlyPayable($item),
                    'payable'         => $this->calTotalPayable($item),
                    'paid'            => array_sum($this->getYearlyPayable($item)),
                    'due'             => $this->calcDueAmount($item),
                    'advance'         => $this->calcAdvanceAmount($item)
                ];
            });

        //monthly total array 
        $monthsArray = [];

        foreach ($data['students'] as  $student) {
            foreach ($student['months'] as $key => $month) {
                $monthsArray[$key][] = $month;
            }
        }

        $monthAmoountArray = [];
        foreach ($monthsArray as $key =>  $monthAmount) {
            $monthAmoountArray[$key] = array_sum($monthAmount);
        }

        $data['monthAmoountArray'] = $monthAmoountArray;
        $data['totalPayable']      = $data['students']->sum('payable');
        $data['totalPaid']         = $data['students']->sum('paid');
        $data['totalDue']          = $data['students']->sum('due');
        $data['totalAdvance']      = $data['students']->sum('advance');
        $data['totalDiscount']     = $this->calcTotalDiscount($data['students']->pluck('id'));
        $data['totalScholarship']     = 0;

        $class    = InsClass::find($request['class_id']);
        $section  = Section::find($request['section_id']);
        $category = Category::find($request['category_id']);
        $group    = Group::find($request['group_id']);

        $title = [
            'class'    => $class->name . '-' . $section->shift->name . '-' . $section->name,
            'category' => $category->name,
            'group'    => $group->name,
        ];

        return view('template1.accountsmanagement.reports.yearly-report.print', compact('data', 'title'));
    }


    public function calcDueAmount($item)
    {
        $totalPayable = $this->calTotalPayable($item);
        $totalPaid    = array_sum($this->getYearlyPayable($item));
        if ($totalPayable < $totalPaid) {
            return 0;
        } else {
            return $totalPayable - $totalPaid;
        }
        //dd($totalPayable);
    }

    public function calcAdvanceAmount($item)
    {

        $totalPayable = $this->calTotalPayable($item);
        $totalPaid    = array_sum($this->getYearlyPayable($item));

        if ($totalPayable > $totalPaid) {
            return 0;
        } else {
            return $totalPaid - $totalPayable;
        }
        //dd($totalPayable);
    }

    public function calcTotalDiscount($studentIds)
    {
        return StudentFeeReceived::whereIn("student_id", $studentIds)->sum('discount');
    }


    public function monthlyReport()
    {
        $sessions = Session::all();
        $months   = Helper::months();
        return view('template1.accountsmanagement.reports.monthly-report.index', compact('sessions', 'months'));
    }


    public function getMonthlyReport(Request $request)
    {


        $data['students'] = Student::where('session_id', $request->session_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->get()
            ->map(function ($item) use ($request) {
                return [
                    'id'      => $item->id,
                    'id_no'   => $item->id_no,
                    'name'    => $item->name,
                    'roll_no' => $item->roll_no,
                    'months'  => $this->getMonthlyPayable($item, $request->month),
                    'payable' => $this->calTotalPayable($item),
                    'paid'    => array_sum($this->getMonthlyPayable($item, $request->month)),
                    'due'     => $this->calcDueAmount($item),
                    'advance' => $this->calcAdvanceAmount($item)
                ];
            });

        $data['totalPayable'] = $data['students']->sum('payable');
        $data['totalPaid']    = $data['students']->sum('paid');
        $data['totalDue']     = $data['students']->sum('due');
        $data['totalAdvance'] = $data['students']->sum('advance');

        $monthsArray = [];

        foreach ($data['students'] as  $student) {
            foreach ($student['months'] as $key => $month) {
                $monthsArray[$key][] = $month;
            }
        }

        $monthAmoountArray = [];
        foreach ($monthsArray as $key =>  $monthAmount) {
            $monthAmoountArray[$key] = array_sum($monthAmount);
        }

        $data['monthAmoountArray'] = $monthAmoountArray;
        $months                    = Helper::getMonths();
        $data['months']            = collect($months)->whereIn('id', $request->month);

        return response()->json($data);
    }

    public function getMonthlyPayable($student, $month)
    {

        $months = Helper::getMonths();

        $months = collect($months)->whereIn('id', $month);

        $array = [];

        foreach ($months as $month) {
            $totalAmount = StudentFeeReceived::where('student_id', $student->id)->where('month', $month['id'])->sum('paid_amount');
            $array[$month['name']] = $totalAmount;
        }

        return $array;
    }


    public function monthlyReportPrint($req){

        $request = [];
        parse_str($req,$request);
     


        $data['students'] = Student::where('session_id', $request['session_id'])
            ->where('section_id', $request['section_id'])
            ->where('category_id', $request['category_id'])
            ->where('group_id', $request['group_id'])
            ->get()
            ->map(function ($item) use ($request) {
                return [
                    'id'        => $item->id,
                    'id_no'           => $item->id_no,
                    'name'            => $item->name,
                    'roll_no'         => $item->roll_no,
                    'months'          => $this->getMonthlyPayable($item,$request['month']),
                    'payable'         => $this->calTotalPayable($item),
                    'paid'            => array_sum($this->getMonthlyPayable($item,$request['month'])),
                    'due'             => $this->monthlyCalcDueAmount($item,$request['month']),
                    'advance'         => $this->monthlyCalcAdvanceAmount($item,$request['month'])
                ];
            });

        //monthly total array 
        $monthsArray = [];

        foreach ($data['students'] as  $student) {
            foreach ($student['months'] as $key => $month) {
                $monthsArray[$key][] = $month;
            }
        }

        $monthAmoountArray = [];
        foreach ($monthsArray as $key =>  $monthAmount) {
            $monthAmoountArray[$key] = array_sum($monthAmount);
        }

        $data['monthAmoountArray'] = $monthAmoountArray;
        $data['totalPayable']      = $data['students']->sum('payable');
        $data['totalPaid']         = $data['students']->sum('paid');
        $data['totalDue']          = $data['students']->sum('due');
        $data['totalAdvance']      = $data['students']->sum('advance');
        $data['totalDiscount']     = $this->calcTotalDiscount($data['students']->pluck('id'));
        $data['totalScholarship']  = 0;

        $months = Helper::getMonths();
        $data['months'] = collect($months)->whereIn('id',$request['month']);

        $section  = Section::find($request['section_id']);
        $class    = InsClass::find($section['ins_class_id']);
        $category = Category::find($request['category_id']);
        $group    = Group::find($request['group_id']);

        $title = [
            'class'    => $class->name . '-' . $section->shift->name . '-' . $section->name,
            'category' => $category->name,
            'group'    => $group->name,
        ];

        return view('template1.accountsmanagement.reports.monthly-report.print', compact('data', 'title'));
    }


    public function monthlyCalcDueAmount($item,$month)
    {
        $totalPayable = $this->calTotalPayable($item);
        $totalPaid    = array_sum($this->getMonthlyPayable($item,$month));
        if ($totalPayable < $totalPaid) {
            return 0;
        } else {
            return $totalPayable - $totalPaid;
        }
        //dd($totalPayable);
    }

    public function monthlyCalcAdvanceAmount($item,$month)
    {

        $totalPayable = $this->calTotalPayable($item);
        $totalPaid    = array_sum($this->getMonthlyPayable($item,$month));

        if ($totalPayable > $totalPaid) {
            return 0;
        } else {
            return $totalPaid - $totalPayable;
        }
        //dd($totalPayable);
    }
}
