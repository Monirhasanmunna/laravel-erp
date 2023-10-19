<?php
namespace App\Services;

use App\Helper\Helper;
use App\Models\SchoolInfo;
use App\Models\Student;

class SmsContentService{

    public function feeCollectionContent($studentFeeReceived){


        $months = collect(Helper::getMonths());
        $monthC = $months->where('id',$studentFeeReceived->month)->first();
        $month = date("M",strtotime($monthC['name']));
        $date = $month." ". $studentFeeReceived->year;


        $student = Student::find($studentFeeReceived->student_id);
        $info = SchoolInfo::first();

        $content = "Successfully Paid Your Student Fee (Month-@date) Payable: @payable Paid: @paid @due @adv Name: @name, Class: @class, Roll: @roll @schoolName";

        $replace     = ["@date","@payable", "@paid", "@due", "@adv", "@name","@class","@roll","@schoolName"];

        $advance = $studentFeeReceived['advance'] == "" ? "":"Adv:".$studentFeeReceived['advance'];
        $due     = $studentFeeReceived['due_amount'] == "" ? "":"Dues:".$studentFeeReceived['due_amount'];
        $replaceItem = [
                            $date,
                            $studentFeeReceived['total_payable'],
                            $studentFeeReceived['paid_amount'],
                            $due,
                            $advance,
                            $student->name,
                            $student->ins_class->name,
                            $student->roll_no,
                            $info->name
                        ];
        $content  = str_replace($replace, $replaceItem, $content);



        return $content;

    }

}
