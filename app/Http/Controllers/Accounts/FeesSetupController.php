<?php

namespace App\Http\Controllers\Accounts;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Fees;
use App\Models\FeesDetail;
use App\Models\FeesHead;
use App\Models\FeesType;
use App\Models\InsClass;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use FontLib\TrueType\Collection;
use Illuminate\Http\Request;

class FeesSetupController extends Controller
{
    public function index()
    {
        $fees = Fees::with('feesType','session','class','section.shift','section.class','category','group')
                    ->get()
                    ->map(function ($item){
                        return [
                          'id'        => $item->id,
                          'fees_type' => $item->feesType->type,
                          'session'   => $item->session->title,
                          'class'     => $item->class->name.'-'.$item->section->shift->name.'-'.$item->section->name,
                          'category'  => $item->category->name,
                          'group'     => $item->group->name,
                          'total'     => $item->total,
                          'students'  => $this->studentCount($item),
                          'total_payable' => $item->total * $this->studentCount($item)
                        ];
                    });

        return view($this->backendTemplate['template']['path_name'].'.accountsmanagement.fees-setup.index',compact('fees'));
    }

    public function studentCount($item){
        $students = Student::where('session_id',$item->session_id)
                            ->where('section_id',$item->section_id)
                            ->where('category_id',$item->category_id)
                            ->where('group_id',$item->group_id)
                            ->count();
        return $students;
    }

    public function create(){
        $academic_years = Session::all();
        $feesTypes      = FeesType::all();
        $months         = Helper::months();
        return view($this->backendTemplate['template']['path_name'].'.accountsmanagement.fees-setup.create',compact('academic_years','feesTypes','months'));
    }

    public function store(Request $request)
    {
        $data  = $request->all();

        //calculate total amount
        $amountArray = array_filter($data, function($key) {
            return strpos($key, 'amount-') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $totalAmount = 0;
        foreach ($amountArray as $amount){
            $totalAmount += array_sum($amount);
        }


        $exists = Fees::where('session_id',$request->academic_year_id)
                        ->where('class_id',$request->class_id)
                        ->where('section_id',$request->section_id)
                        ->where('category_id',$request->category_id)
                        ->where('group_id',$request->group_id)
                        ->where('fees_type_id',$request->fees_type)
                        ->get();

        if($exists->count() > 0){
            //notification
            $notification = array(
                'message' =>'Fees Already Setup',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }
        else{
            $fees = Fees::create([
                'session_id'   =>  $request->academic_year_id,
                'class_id'     =>  $request->class_id,
                'section_id'   =>  $request->section_id,
                'category_id'  =>  $request->category_id,
                'group_id'     =>  $request->group_id,
                'fees_type_id' =>  $request->fees_type,
                'total'        =>  $totalAmount,
            ]);
            foreach ($request->month as $key => $month){

                $monthWithZero = $month > 9 ? $month : '0'.$month;

                $regularFee = FeesDetail::updateOrCreate([
                    'fees_id'      =>  $fees->id,
                    'date'         =>  date('Y',strtotime($request->due_date[$key])).'-'.$monthWithZero.'-'.'01',
                    'month'        =>  $month,
                    'year'         =>  date('Y',strtotime($request->due_date[$key])),
                    'due_date'     =>  $request->due_date[$key],
                    'total_amount' =>  array_sum($data['amount-'.$month])
                ]);

                foreach($data['head-'.$month] as $key2 =>  $feeHead){
                    $regularFee->feesHead()->create([
                        'head'   => $feeHead,
                        'amount' => $data['amount-'.$month][$key2],
                    ]);
                }

            }
        }



        //notification
        $notification = array(
            'message' =>'Fees Setup Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('fees-setup.index')->with($notification);
    }


    public function edit($id){
        $feesTypes = FeesType::all();
        $fees   = Fees::with('details','details.feesHead')->find($id);
        $months = Helper::months();
        return view($this->backendTemplate['template']['path_name'].'.accountsmanagement.fees-setup.edit',compact('feesTypes','fees','months'));
    }


    public function update(Request $request,$id){

        $data = $request->all();

        $fees = Fees::find($id);
        $fees->update([
           'fees_type_id' => $request->fees_type
        ]);

        //delete previous data
        $feesDetails = FeesDetail::where('fees_id',$id)->get();
        foreach ($feesDetails as $detail){
            foreach ($detail->feesHead as $head){
                $head->delete();
            }
        }
        FeesDetail::where('fees_id',$id)->delete();

        //calculate total amount
        $amountArray = array_filter($data, function($key) {
            return strpos($key, 'amount-') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $totalAmount = 0;
        foreach ($amountArray as $amount){
            $totalAmount += array_sum($amount);
        }

        $fees = Fees::find($id);
        $fees->update([
           'total' => $totalAmount
        ]);

        foreach ($request->month as $key => $month){

            $monthWithZero = $month > 9 ? $month : '0'.$month;

            $regularFee = FeesDetail::updateOrCreate([
                'fees_id'      =>  $fees->id,
                'date'         =>  date('Y',strtotime($request->due_date[$key])).'-'.$monthWithZero.'-'.'01',
                'month'        =>  $month,
                'year'         =>  date('Y',strtotime($request->due_date[$key])),
                'due_date'     =>  $request->due_date[$key],
                'total_amount' =>  array_sum($data['amount-'.$month])
            ]);

            foreach($data['head-'.$month] as $key2 =>  $feeHead){
                $regularFee->feesHead()->create([
                    'head'   => $feeHead,
                    'amount' => $data['amount-'.$month][$key2],
                ]);
            }

        }

        //notification
        $notification = array(
            'message' =>'Fees Update Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('fees-setup.index')->with($notification);

    }


    public function deleteFees($request){

        $fees = Fees::with('feeDetails')->where('session_id',$request->academic_year_id)->where('class_id',$request->class_id)->get();
        foreach($fees as $fee){
            $fee->feeDetails()->delete();
            $fee->delete();
        }
    }


    public function getFeesData(Request $request){

        $section = Section::find($request->section_id);


        $data['fees']      = Fees::with('feeDetails')->where('class_id',$section->ins_class_id)->get();
        $data['fees_type'] = FeesType::all();
        $data['months'] = Helper::getMonths();

        return $data;
    }
}
