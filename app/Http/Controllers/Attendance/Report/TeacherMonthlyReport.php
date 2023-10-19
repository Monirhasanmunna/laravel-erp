<?php

namespace App\Http\Controllers\Attendance\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DeviceConfigure;
use App\Models\LeaveApplication;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeacherMonthlyReport extends Controller
{
    public function index()
    {
        return view($this->backendTemplate['template']['path_name'].'.attendance.teacher_report.monthly-report');
    }

    public function TeacherMonthlyReport(Request $request)
    {
      // return $request;
       return $this->ReportGenerate($request);
    }

    function ReportGenerate($request){
       // return $month;
    //    $data = [];
    //    $data['month'] = date('m',strtotime($month));

    //     $data['monthLength'] = $this->getMonthLength($data['month']);
    //     $api_key  = DeviceConfigure::orderBy('id','DESC')->first();
    //     $data['teachers'] = Teacher::where('type','teacher')->get();

    //     $fingerId = [];
    //     $deviceId = [];
    //     foreach ($data['teachers'] as $teacher){
    //         array_push($fingerId,$teacher->finger_id);
    //         array_push($deviceId,$teacher->device_id);
    //     }

    //     $apiResponse = Http::get('http://teamx-digital-attendance.test/api/v1/get-attendance-by-month', [
    //         'api_key'   => 'bFvMrVYLCzXA6yE',
    //         'finger_id[]' => $fingerId,
    //         'device_id[]' => $deviceId,
    //         'month'     => $data['month'],
    //     ])->json();
         
    //     $collectData = collect($apiResponse);

    //     $data['teachers'] = $data['teachers']->map(function($item) use ($collectData,$data,$month){
    //             return [
    //                 'name' => $item->name,
    //                 'status' => $this->getStatus($collectData,$item->finger_id)
    //             ];
    //     });

    //     return $data;

        $data = [];
        $data['month'] = date('m',strtotime($request['month']));
        $data['monthLength'] = $this->getMonthLength($data['month']);
        $data['design_type'] = $request['desig_type'];
        
        $data['teachers'] = Teacher::where('type', $request['desig_type'])->get();
        return $data;
    }


    public function getStatus($collectData,$fingerId)
    {
       return $collectData;
    }
    


    function getMonthLength($month)
    {
        $year = Carbon::now()->year;
        // Create a Carbon instance for the first day of the specified month
        $date = Carbon::createFromDate($year, $month, 1);
        // Use the 'daysInMonth' method to get the number of days in the month
        $monthLength = $date->daysInMonth;
        return $monthLength;
    }


    public function pdfGenerate($month,$design_type)
    {   
        $item = ['desig_type' => $design_type , 'month' => $month];

        $data = $this->ReportGenerate($item);
        $htmlData = $this->insertDataInTable($data,$month);
       // return view($this->backendTemplate['template']['path_name'].'.attendance.teacher_report.pdf.monthly-report',compact('htmlData'));
        $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.attendance.teacher_report.pdf.monthly-report',compact('htmlData'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->setPaper('a4');
        return $pdf->download('teacher-monthly-report.pdf');
    }


    public function insertDataInTable($data,$month)
    {
        // table head
        $table_head = '';
        $table_head = '<tr> <td style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">Name</td>';
            $counts = 1;
            for ($i=0; $i < $data['monthLength'] ; $i++) { 
                $table_head .= '<td style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">'.$counts+$i.'</td>';       
            }
        $table_head .= '</tr>';
        // table head

        // table body
        $table_body = '';
        $source_ids = [];
        foreach ($data['teachers'] as  $teacher) {
          $source_ids[] = $teacher->teacherUser ? $teacher->teacherUser->id : '';

            $table_body .= '<tr style="border:1px solid #2c2e33;">
                <td align="center" style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:2px;padding-bottom:2px;font-size:11px;">'.$teacher->name.'</td>';
                $counts = 1;
                for ($i=0; $i < $data['monthLength'] ; $i++) { 
                $table_body .= '<td style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:2px;padding-bottom:2px;font-size:11px;"></td>';       
            }
            $table_body .='</tr>';
        }
        // table body
        
       $applications = LeaveApplication::whereIn('source_id',$source_ids)->whereMonth('to_date', $month)->get();

       
        
        $data = [];
        $data['table_head'] = $table_head;
        $data['table_body'] = $table_body;
        $data['applications'] = $applications;
        return $data;
    }
}
