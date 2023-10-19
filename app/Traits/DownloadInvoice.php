<?php

namespace App\Traits;

use App\Models\AcademicSetting;
use App\Models\SchoolInfo;
use App\Models\StudentFeeReceived;
use Barryvdh\DomPDF\Facade\Pdf;

trait DownloadInvoice
{

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