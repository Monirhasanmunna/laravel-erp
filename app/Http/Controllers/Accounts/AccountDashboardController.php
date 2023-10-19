<?php

namespace App\Http\Controllers\Accounts;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Fees;
use App\Models\FeesDetail;
use App\Models\FeesType;
use App\Models\Scholarship;
use App\Models\ScholarshipDetail;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentFeeReceived;
use App\Models\StudentFeeReceivedDetail;
use App\Models\StudentFees;
use Illuminate\Http\Request;

class AccountDashboardController extends Controller
{
    public function index()
    {

        $data['todayOfflineCollection'] = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('institute_id', Helper::getInstituteId())
                ->where('institute_branch_id', Helper::getBranchId());
        })
            ->whereDate('date', date('Y-m-d'))->where('payment_type', 'offline')->sum('paid_amount');

        $data['todayOnlineCollection']  = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('institute_id', Helper::getInstituteId())
                ->where('institute_branch_id', Helper::getBranchId());
        })
            ->whereDate('date', date('Y-m-d'))->where('payment_type', 'online')->sum('paid_amount');

        $data['todayTotalCollection']   = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('institute_id', Helper::getInstituteId())
                ->where('institute_branch_id', Helper::getBranchId());
        })
            ->whereDate('date', date('Y-m-d'))->sum('paid_amount');

        $data['todayExpense']           = Expense::whereDate('date', date('Y-m-d'))->sum('amount');



        $data['sessions']               = Session::all();
        $data['months']                 = Helper::months();

        //total dues calculation
        $currentMonth     = date('n');
        $currentYear      = date('Y');


        $data['currentMonth']     = $currentMonth;
        $data['currentYear']      = $currentYear;
        return view($this->backendTemplate['template']['path_name'] . '.accountsmanagement.dashboard.index', $data);
    }


    public function overview()
    {
        $data['sessions'] = Session::all();
        $data['months']   = Helper::months();
        return view($this->backendTemplate['template']['path_name'] . '.accountsmanagement.dashboard.overview', $data);
    }



    public function getDashboardDataToday()
    {

   


        $data['collection_(Cash)']  = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('institute_id', Helper::getInstituteId())
                ->where('institute_branch_id', Helper::getBranchId());
        })
            ->whereDate('date', date('Y-m-d'))->where('payment_type', 'offline')->sum('paid_amount');



        $data['collection_(Online)']  = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('institute_id', Helper::getInstituteId())
                ->where('institute_branch_id', Helper::getBranchId());
        })
            ->whereDate('date', date('Y-m-d'))->where('payment_type', 'online')->sum('paid_amount');


        $data['collection']   = StudentFeeReceived::whereHas('student', function ($query) {
            $query->where('institute_id', Helper::getInstituteId())
                ->where('institute_branch_id', Helper::getBranchId());
        })
            ->whereDate('date', date('Y-m-d'))->sum('paid_amount');    

        //total due ,discount & advance
        $sessionIds = Session::get()->pluck('id');

        $data['due']         = StudentFeeReceived::whereHas('student', function ($query) use ($sessionIds) {
            $query->whereIn('session_id', $sessionIds);
        })
            ->whereDate('date', date('Y-m-d'))
            ->sum('due_amount');

        $data['discount']    = StudentFeeReceived::whereHas('student', function ($query) use ($sessionIds) {
            $query->whereIn('session_id', $sessionIds);
        })
            ->whereDate('date', date('Y-m-d'))
            ->sum('discount');

        $data['advance']     = StudentFeeReceived::whereHas('student', function ($query) use ($sessionIds) {
            $query->whereIn('session_id', $sessionIds);
        })
            ->whereDate('date', date('Y-m-d'))
            ->sum('advance');
        //total scholarship
        $data['scholarship'] = 0;
        //total expense & Fine
        $data['expense'] = 0;
        $data['fine']    = 0;

        $array = [];
        foreach ($data as $key => $amount) {
            $array[$key] = Helper::convertNumberFormat($amount);
        }

        return $array;
    }



    public function getDashboardData(Request $request)
    {


        if ($request->session_id && $request->month) {

            $session = Session::find($request->session_id);
            $month   = $request->month;
            //total Payable
            $fees              = Fees::with('details')
                ->whereHas('details', function ($query) use ($month) {
                    $query->where('month', $month);
                })
                ->where('session_id', $request->session_id)
                ->get()
                ->map(function ($item) use ($month) {
                    return [
                        'total_amount' => $this->calculateRegularFees($item, $month)
                    ];
                });


            $regularFees              = $fees->sum('total_amount');

            $studentFees              = StudentFees::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->where('month', $month)
                ->sum('total_amount');


            $data['receiveable']     = $regularFees  + $studentFees;
            //total dues
            $totalCollection          = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->where('month', $month)
                ->sum('total_payable');
            //total income , online & offline

            $data['collection_(Cash)']      = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->where('payment_type', 'offline')
                ->whereMonth('date', $month)
                ->sum('paid_amount');

            $data['collection_(Online)']      = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->where('payment_type', 'online')
                ->whereMonth('date', $month)
                ->sum('paid_amount');



            $data['total_collection']      = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->whereMonth('date', $month)
                ->sum('paid_amount');

            //total due ,discount & advance
            $data['due']         = $data['receiveable'] - $totalCollection;
            $data['discount']    = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->whereMonth('date', $month)
                ->sum('discount');
            $data['advance']     = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->whereMonth('date', $month)
                ->sum('advance');

            //total scholarship
            $data['scholarship'] = 0;
            //total expense & Fine
            $data['expense'] = 0;
            $data['fine']    = 0;
        }




        $array = [];
        foreach ($data as $key => $amount) {
            $array[$key] = Helper::convertNumberFormat($amount);
        }

        return $array;
    }


    public function calculateRegularFees($item, $month)
    {

        $studentCount = Student::where('session_id', $item->session_id)
            ->where('class_id', $item->class_id)
            ->where('section_id', $item->section_id)
            ->where('category_id', $item->category_id)
            ->where('group_id', $item->group_id)
            ->count();

        $feesDetails = FeesDetail::where('fees_id', $item->id)->where('month', $month)->sum('total_amount');


        return $studentCount * $feesDetails;
    }

    public function calculateRegularFeesBySession($item)
    {

        $studentCount = Student::where('session_id', $item->session_id)
            ->where('class_id', $item->class_id)
            ->where('section_id', $item->section_id)
            ->where('category_id', $item->category_id)
            ->where('group_id', $item->group_id)
            ->count();

        $feesDetails = FeesDetail::where('fees_id', $item->id)->sum('total_amount');


        return $feesDetails * $studentCount;
    }

    public function getDashboardDataBySession(Request $request)
    {

        if ($request->session_id) {
            $session = Session::find($request->session_id);



            $fees                     = Fees::where('session_id', $request->session_id)
                ->get()
                ->map(function ($item) {
                    return [
                        'total_amount' => $this->calculateRegularFeesBySession($item)
                    ];
                });

            $regularFees              = $fees->sum('total_amount');
            $studentFees              = StudentFees::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->sum('total_amount');

            $data['receiveable']     = $regularFees  + $studentFees;
            //total dues
            $totalCollection          = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->sum('paid_amount');

            //total income 
          
            //total income offline
            $data['collection_(Cash)']      = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->where('payment_type', 'offline')
                ->sum('paid_amount');
            //total income online
            $data['collection_(Online)']      = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->where('payment_type', 'online')
                ->sum('paid_amount');

          

            $data['total_collection']      = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->sum('paid_amount');
            //total due
            $discount = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->sum('discount');

            $data['due']              = $data['receiveable'] - ($totalCollection + $discount);

            //total discount
            $data['discount']         = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->sum('discount');

            //total advance
            $data['advance']          = StudentFeeReceived::whereHas('student', function ($query) use ($request) {
                $query->where('session_id', $request->session_id);
            })
                ->sum('advance');

            //total scholarship
            $data['scholarship'] = 0;

            //total expense
            $data['expense']     = 0;

            //total expense
            $data['fine']        = 0;
        }

        $array = [];
        foreach ($data as $key => $amount) {
            $array[$key] = Helper::convertNumberFormat($amount);
        }

        return $array;
    }


    public function getOverviewData(Request $request)
    {

        $students = Student::where('session_id', $request->session_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->get();
        $studentCount = $students->count();

        $fees         = Fees::where('session_id', $request->session_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->get();
        $feesIds    = [];
        $studentIds = [];
        foreach ($fees as $fee) {
            array_push($feesIds, $fee->id);
        }
        foreach ($students as $student) {
            array_push($studentIds, $student->id);
        }

        if ($request->month) {
            $feesAmount            = FeesDetail::whereIn('fees_id', $feesIds)->where('month', $request->month)->sum('total_amount');
            $totalOnlineCollection = StudentFeeReceived::whereIn('student_id', $studentIds)
                ->where('month', $request->month)
                ->where('payment_type', 'online')->sum('paid_amount');
            $totalOfflineCollection = StudentFeeReceived::whereIn('student_id', $studentIds)
                ->where('month', $request->month)
                ->where('payment_type', 'offline')->sum('paid_amount');
            $totalCollection       = StudentFeeReceived::whereIn('student_id', $studentIds)
                ->where('month', $request->month)->sum('paid_amount');
        } else {
            $feesAmount  = FeesDetail::whereIn('fees_id', $feesIds)->sum('total_amount');
            $totalOnlineCollection  = StudentFeeReceived::whereIn('student_id', $studentIds)
                ->where('payment_type', 'online')->sum('paid_amount');
            $totalOfflineCollection = StudentFeeReceived::whereIn('student_id', $studentIds)
                ->where('payment_type', 'offline')->sum('paid_amount');
            $totalCollection        = StudentFeeReceived::whereIn('student_id', $studentIds)->sum('paid_amount');
        }

        $regularFees = $feesAmount * $studentCount;
        $studentFees = StudentFees::whereIn('student_id', $studentIds)->sum('total_amount');

        $data['totalPayable']     = $regularFees  + $studentFees;

        //total dues
        $data['totalDue']         = $data['totalPayable'] - $totalCollection;
        //total scholarship
        $data['totalScholarship'] = 0.00;
        //total income & Expense
        $data['totalOnlineCollection']  = $totalOnlineCollection;
        $data['totalOfflineCollection']  = $totalOfflineCollection;
        $data['totalCollection']  = $totalCollection;


        //table data
        $data['feesTypesData'] = FeesType::get()->map(function ($item) use ($request, $studentCount, $studentIds) {
            return [
                'id'               => $item->id,
                'name'             => $item->type,
                'total_payable'    => $this->calcTotalPayable($request, $item->id, $studentCount),
                'total_collection' => $this->calcTotalCollection($request, $item->id, $studentIds),
                'total_dues'       => $this->calcTotalPayable($request, $item->id, $studentCount) -  $this->calcTotalCollection($request, $item->id, $studentIds),
                'scholarship'      => 0.00,
                'discount'         => 0.00,
                'fine'             => 0.00,
                'student_count'    => $studentCount
            ];
        });



        return response()->json($data);
    }


    public function calcTotalPayable($request, $feesTypeId, $studentCount)
    {
        $fees         = Fees::where('session_id', $request->session_id)
            ->where('section_id', $request->section_id)
            ->where('category_id', $request->category_id)
            ->where('group_id', $request->group_id)
            ->where('fees_type_id', $feesTypeId)
            ->get();
        $feesIds    = [];
        foreach ($fees as $fee) {
            array_push($feesIds, $fee->id);
        }

        if ($request->month) {
            $feesAmount  = FeesDetail::whereIn('fees_id', $feesIds)->where('month', $request->month)->sum('total_amount');
        } else {
            $feesAmount  = FeesDetail::whereIn('fees_id', $feesIds)->sum('total_amount');
        }
        return  $feesAmount * $studentCount;
    }

    public function calcTotalCollection($request, $feesTypeId, $studentIds)
    {

        if ($request->month) {
            $feeReceiveds = StudentFeeReceived::whereIn('student_id', $studentIds)->where('month', $request->month)->get();
        } else {
            $feeReceiveds = StudentFeeReceived::whereIn('student_id', $studentIds)->get();
        }

        $feeRecIds = [];
        foreach ($feeReceiveds as $fee) {
            array_push($feeRecIds, $fee->id);
        }

        $totalCollection = StudentFeeReceived::whereHas("feeReceivedDetails", function ($query) use ($feeRecIds, $feesTypeId) {
            $query->whereIn('student_fee_received_id', $feeRecIds)->where('fees_type_id', $feesTypeId);
        })->sum('paid_amount');

        // $totalCollection          = StudentFeeReceivedDetail::whereIn('student_fee_received_id', $feeRecIds)->where('fees_type_id', $feesTypeId)->sum('amount');
        return $totalCollection;
    }

    public function getOverviewDataBySessionMonth(Request $request)
    {

        $data = FeesType::get()->map(function ($item) use ($request) {
            return [
                'id'               => $item->id,
                'name'             => $item->type,
                'total_payable'    => $this->calcTotalPayableBySessionMonth($request, $item->id),
                'total_collection' => $this->calcTotalCollectionBySessionMonth($request, $item->id),
                'total_dues'       => $this->calcTotalPayableBySessionMonth($request, $item->id) - $this->calcTotalCollectionBySessionMonth($request, $item->id),
                'scholarship'      => 0.00,
                'discount'         => 0.00,
                'fine'             => 0.00,
            ];
        });
        return $data;
    }


    public function calcTotalPayableBySessionMonth($request, $feesTypeId)
    {

        $fees         = Fees::where('session_id', $request->session_id)
            ->where('fees_type_id', $feesTypeId)
            ->get();
        $regFees      = 0;

        foreach ($fees as $fee) {
            $studentCount = Student::where('session_id', $fee->session_id)
                ->where('class_id', $fee->class_id)
                ->where('section_id', $fee->section_id)
                ->where('category_id', $fee->category_id)
                ->where('group_id', $fee->group_id)
                ->count();

            $feesAmount  = FeesDetail::where('fees_id', $fee->id)->where('month', $request->month)->sum('total_amount');

            $totalAmount = $studentCount * $feesAmount;

            $regFees += $totalAmount ?? 0;
        }

        //calc student Fees
        $studentIds  = Student::where('session_id', $request->session_id)->pluck('id');
        $studentFees = StudentFees::whereIn('student_id', $studentIds)->where('month', $request->month)->where('fees_type_id', $feesTypeId)->sum('total_amount');

        return $regFees + $studentFees;
    }

    public function calcTotalCollectionBySessionMonth($request, $feesTypeId)
    {

        $studentIds  = Student::where('session_id', $request->session_id)->pluck('id');
        $feeReceiveds = StudentFeeReceived::whereIn('student_id', $studentIds)->where('month', $request->month)->get();


        $feeRecIds = [];
        foreach ($feeReceiveds as $fee) {
            array_push($feeRecIds, $fee->id);
        }

        $totalCollection = StudentFeeReceived::whereHas("feeReceivedDetails", function ($query) use ($feeRecIds, $feesTypeId) {
            $query->whereIn('student_fee_received_id', $feeRecIds)->where('fees_type_id', $feesTypeId);
        })->sum('paid_amount');

        //$totalCollection = StudentFeeReceivedDetail::whereIn('student_fee_received_id',$feeRecIds)->where('fees_type_id',$feesTypeId)->sum('amount');
        return $totalCollection;
    }
}
