@php
use App\Helper\Helper;
$helper = new Helper();
@endphp
@extends('admin.layouts.app')
@section('content')
<div class="main-panel">
    @include($adminTemplate.'.exammanagement.topmenu_exammanagement')
    <div class="card new-table">
        <div class="card-body">
            
            <h6>Tabulation Sheet</h6>
            <hr>
            <form id="student-form" action="{{ route('exam-management.report.tabulation-sheet.tabulation-result') }}" method="POST">
                @csrf
                @include('custom-blade.search-student3')
                
            </form>
        </div>
    </div>
    
    <div>
        @if ($result)
        <div class="card new-table">
            <div class="card-body">
                <a class="btn btn-warning btn-sm pull-right" href="#" onclick="printDiv('printableArea')" >Print</a>
                <section class="table-responsive" >
                    <div id="printableArea">
                        @push('css')
                        <style>
                        
                        table thead tr th, table tbody tr td {
                        text-align: center !important;
                        vertical-align: middle  !important;
                        border: 1px solid #000 !important;
                        font-size: 10px !important;
                        padding: 5 !important;
                        color: #000 !important;
                        }
                        .logo img, .logo strong {display: inline-block; vertical-align: middle;}
                        .logo img, .logo span {display: inline-block; vertical-align: middle;}
                        @media print {
                        @page {
                        size: legal landscape;
                        
                        }
                        table thead tr th, table tbody tr td {
                        font-size: 10px !important;
                        padding: 5 !important;
                        
                        }
                        
                        
                        }

                        /*#printableArea {

                             background-image: url({{Config::get('app.s3_url').Helper::academic_setting()->image}});
                             background-repeat: no-repeat;
                              background-position: center;
                              background-opacity: 0.5;
                              
                            }*/
                        
                        </style>
                        @endpush
                        <div >
                            <center>
                            @if(@Helper::academic_setting()->image)
                            <img src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" class="gradient-corona-img img-fluid" alt="Logo" height="80" width="80">
                            @endif
                            
                            <h4 class="text-uppercase">{{Helper::school_info()->name}}</h4>
                            <b>{{Helper::school_info()->address}}</b>
                            <h4 class="text-uppercase" style="margin: 5px 0;">Tabulation Sheet of {{$exam->name}} {{$exam->session->title}}</h4>
                            <b>
                            <span>Class: {{$section->class->name}}</span>,&nbsp;
                            <span>Shift: {{$section->shift->name}}</span>,&nbsp;
                            <span>Session: {{$exam->session->title}}</span>,&nbsp;
                            <span>Section: {{$section->name}}</span>
                            </b>
                            <br>
                            <!-- <b>Merit List Serial By GPA</b>    -->
                            </center>
                        </div>
                        
                        <br>
                        <div class="table-responsive">
                            <table style="width: 100%;" class="tabulation">
                            <thead>
                                <tr>
                                    <th rowspan="2">Roll</th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">MERIT <br>(SEC)</th>
                                    <th rowspan="2">MERIT <br>(CLASS)</th>
                                    
                                    @php
                                    $count = 0;
                                    $fmak = 0;
                                    @endphp
                                    @foreach ($subjects as $subject)
                                    @php
                                    $input = $result->where('class_subject_id', $subject->id)->first();
                                    @endphp
                                    @if ($input)
                                    @php $count += 1; @endphp
                                    @php
                                    $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                                    $fmak += $subtype['mark_master']->total_mark;
                                    if (count($subtype['mark_dists']) > 1) {
                                    $colspan = count($subtype['mark_dists']) + 1;
                                    } else {
                                    $colspan = count($subtype['mark_dists']);
                                    }
                                    @endphp
                                    <th colspan="{{$colspan}}">
                                        {{substr($subject->subject->sub_name, 0, 15)}}
                                        @if ($subject->subjectType->is_common == 0)
                                        <br>({{$subject->subjectType->name}})
                                        @endif
                                    </th>
                                    
                                    @endif
                                    @endforeach
                                    <th rowspan="2">Total Marks<br> {{$fmak}}</th>
                                    <th rowspan="2">GPA</th>
                                    <th rowspan="2">Grade</th>
                                    <th rowspan="2">Status</th>
                                    <th rowspan="2">Att.</th>
                                </tr>
                                <tr>
                                    @foreach ($subjects as $subject)
                                    @php
                                    $input = $result->where('class_subject_id', $subject->id)->first();
                                    @endphp
                                    @if ($input)
                                    @php
                                    $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                                    @endphp
                                    @foreach ($subtype['mark_dists'] as $type)
                                    <th>{{$type['subMarkDistType']['title']}}<br>{{$type['mark']}}</th>
                                    @endforeach
                                    @php
                                    if (count($subtype['mark_dists']) > 1) {
                                    echo "<th>Total</th>";
                                    }
                                    @endphp
                                    @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                  
                                // Sort students based on Total Marks in descending order
                                $sortedStudents = $students->sortByDesc(function ($student) use ($result, $subjects, $section, $exam, $helper) {
                                $totalMarks = 0;
                                $failedSubject = false; // Flag to track if student has failed any subject
                                foreach ($subjects as $subject) {
                                $marksInput = $result->where('class_subject_id', $subject->id)->first();
                                if ($marksInput) {
                                $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                                $subtotal = 0;
                                foreach ($subtype['mark_dists'] as $type) {
                                $details = $marksInput->details->where('student_marks_input_id', $marksInput->id)
                                ->where('student_id', $student->id)
                                ->where('sub_marks_dist_details_id', $type['id']);
                                //if ($details->count() > 0) {
                                $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                $subtotal += $marks;
                                //}
                                }
                                if ($subtotal < $subtype['mark_master']->pass_mark) {
                                $failedSubject = true; // Set flag if subject is failed
                                }
                                $totalMarks += $subtotal;
                                }
                                }
                                if ($failedSubject) {
                                $totalMarks = 0; // Set total marks to 0 if student fails any subject
                                }
                                return $totalMarks;
                                });
                                $totalMarks = 0;
                                $meritInSection = 0;
                                $meritInClass = 0;
                                $previousTotalMarks = null;
                                @endphp
                                @foreach ($sortedStudents as $key => $student)
                                @php
                                $bg = '';
                                $total_marks = 0;
                                $subgrade = 0;
                                $gross_marks = 0;
                                $full_marks = 0;
                                $failed = false;
                                @endphp
                                @php
                                // Check if the student has failed any subject
                                $failedSubject = false;
                                foreach ($subjects as $subject) {
                                $marksInput = $result->where('class_subject_id', $subject->id)->first();
                                if ($marksInput) {
                                $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                                $subtotal = 0;
                                foreach ($subtype['mark_dists'] as $type) {
                                if ($marksInput) {
                                $details = $marksInput->details->where('student_marks_input_id', $marksInput->id)
                                ->where('student_id', $student->id)
                                ->where('sub_marks_dist_details_id', $type['id']);
                                //if ($details->count() > 0) {
                                $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                $subtotal += $marks;
                                //}
                                }
                                }
                                if ($subtotal < $subtype['mark_master']->pass_mark) {
                                $failedSubject = true; // Set flag if subject is failed
                                break; // Exit the loop if any subject is failed
                                }
                                }
                                }
                                if ($failedSubject) {
                                $meritInSection = 0; // Set merit in section as 0 for failed students
                                $meritInClass = 0; // Set merit in class as 0 for failed students
                                } else {
                                $meritInSection += 1; // Increment merit in section for passed students
                                $meritInClass += 1; // Increment merit in class for passed students
                                }
                                @endphp
                                <tr>
                                    <td>{{$student->roll_no}}</td>
                                    <td>{{$student->name}}</td>
                                   <td>{{$meritInSection}}</td>
                                    <td>{{$meritInClass}}</td>
                                    <?php 
                                    $grosstotal = 0;
                                    $subgrade = 0;
                                    //$failed = false;
                                    $failed = 0;
                                   
                                    foreach ($subjects as $subject) {
                                        $input = $result->where('class_subject_id', $subject->id)->first();
                                        if ($input){
                                            $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                                            $subtotal = 0;
                                            $subgrage_marks = 0;
                                             
                                             $bg ="";
                                             $bgt ="";
                                             
                                             if (count($subtype['mark_dists']) > 1) { 
                                                foreach ($subtype['mark_dists'] as $type){
                                            $details = $input->details->where('student_marks_input_id', $input->id)
                                            ->where('student_id', $student->id)
                                            ->where('sub_marks_dist_details_id', $type['id']);
                                            $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                            $subtotal += $marks;
                                            $grosstotal += $marks;

                                             if ($subtype['mark_master']->pass_mark == 0) {
                                                if ($marks < $type['pass_mark']) {
                                            $bg = '#FF6263';
                                            $subgrage_marks = 0;
                                            //$failed = true;
                                            $failed += 1;
                                            }
                                            else{
                                                $bg = '';
                                                $subgrage_marks += $marks;
                                                }
                                            } ?>
                                            <td bgcolor="{{$bg}}">{{$marks}}</td>
                                             <?php } 
                                             if ($subtype['mark_master']->pass_mark !== 0) {
                                                if ($subtotal < $subtype['mark_master']->pass_mark) {
                                                    $bgt = '#FF6263';
                                                     //$failed = true;
                                                     $failed += 1;
                                                     $subgrage_marks = 0; 
                                                }else{
                                                  $bgt = '';
                                                   $subgrage_marks = $subtotal;  
                                                }
                                            }
                                                 $ssgrades = $helper->getGradeValue($section->class->id, $subgrage_marks, $subtype['mark_master']->total_mark);
                                                 $subgrade += $ssgrades['gp'];
                                             ?>
                                            
                                             <td bgcolor="{{$bgt}}">{{$subtotal}} 
                                               <?php $formattedGrade = number_format($ssgrades['gp'], 2, '.', '');
                                                    $formattedGrade = rtrim($formattedGrade, '0'); 
                                                    $formattedGrade = rtrim($formattedGrade, '.');
                                                ?>
                                                ({{$formattedGrade}})
                                            </td>


                                            <?php }else{ 
                                                foreach ($subtype['mark_dists'] as $type){
                                            $details = $input->details->where('student_marks_input_id', $input->id)
                                            ->where('student_id', $student->id)
                                            ->where('sub_marks_dist_details_id', $type['id']);
                                            $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                            $subtotal += $marks;
                                            $grosstotal += $marks;
                                             if ($subtype['mark_master']->pass_mark == 0) {
                                                if ($marks < $type['pass_mark']) {
                                            $bg = '#FF6263';
                                            $subgrage_marks = 0;
                                            //$failed = true;
                                            $failed += 1;
                                            }
                                            else{
                                                $bg = '';
                                                $subgrage_marks += $marks;
                                                }
                                            } else{
                                        
                                        if ($marks < $subtype['mark_master']->pass_mark) {
                                            $bg = '#FF6263';
                                             $subgrage_marks = 0;
                                             //$failed = true;
                                             $failed += 1;
                                            }else{
                                                $bg = '';
                                                $subgrage_marks += $marks;
                                            }
                                        }
                                            $sgrades = $helper->getGradeValue($section->class->id, $subgrage_marks, $subtype['mark_master']->total_mark);
                                            $subgrade += $sgrades['gp'];
                                                ?>
                                                
                                                <td bgcolor="{{$bg}}">{{$marks}} 
                                                    <?php $formattedGradet = number_format($sgrades['gp'], 2, '.', '');
                                                    $formattedGradet = rtrim($formattedGradet, '0'); 
                                                    $formattedGradet = rtrim($formattedGradet, '.');
                                                    ?>
                                                    ({{$formattedGradet}})
                                                </td>
                                                 <?php } }?>
                                                 <?php } }?>



                                      <?php           
                                      //if ($failed) {
                                      if ($failed>0) {
                                    $status = "Failed";
                                    $bg = '#FF6263';
                                    $grademark = 0;
                                    } else {
                                    $status = "Passed";
                                    $bg = '';
                                    $grademark = $subgrade / $count;
                                    }
                                    $totalgrades = $helper->getTotalGrade($section->class->id, $grademark);

                                   
                                    ?>
                                    
                                   
                                    
                                    
                                    <td bgcolor="{{$bg}}">{{$grosstotal}}</td>
                                    <td bgcolor="{{$bg}}">
                                         <?php 
                                        $formattedNumber = number_format($grademark, 2, '.', '');

                                        $formattedNumber = rtrim($formattedNumber, '0'); 
                                        $formattedNumber = rtrim($formattedNumber, '.');
                                       ?>
                                    {{$formattedNumber}}
                                    </td>
                                     <td bgcolor="{{$bg}}">{{$totalgrades['gpa']}}</td>
                                    <td bgcolor="{{$bg}}">{{$status}}</td>
                                    <td>
                                        @php
                                        $atten = $helper->getStudentAtten($student->id, $exam->start_date, $exam->end_date);
                                        @endphp
                                        {{$atten['atten_count']}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        
                        <br>
                       



                        <h4>Summery</h4>
                        <table class="table table-bordered text-center">
                            <thead>
                                <th>GPA</th>
                                @foreach ($subjects as $subject)
                                @php
                                $input = $result->where('class_subject_id', $subject->id)->first();
                                
                                @endphp
                                @if ($input)
                                <th>{{$subject->subject->sub_name}}</th>
                                @endif
                                @endforeach
                                <th>Total</th>
                            </thead>
                            <tbody>
                                @foreach ($grades as $gr)
                                <tr>
                                    <td>{{$gr->gpa_name}}</td>
                                     <?php
                                     $totlgpacount = 0;
                                    foreach ($subjects as $subject){
                                      
                                        $gradecount =0; 
                                        $input = $result->where('class_subject_id', $subject->id)->first();
                                       
                                        if ($input){

                                        foreach ($students as $student){
                                            
                                                $tabinfo = $helper->getTabulation($student->id, $exam->id, $subject->id, $section->class->id); 
                                           
                                            if ($tabinfo['gradePoint'] == $gr->gpa_point){
                                                $gradecount +=1; 
                                            }
                                            }
                                           echo "<td>".$gradecount."</td>"; 
                                        }
                                        $totlgpacount += $gradecount;
                                       }
                                   
                                     ?>

                                    <td>{{$totlgpacount}}</td>
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                        
                        
                        <!-- <style>
                        .col-md-3{border-top: 1px dashed black; margin-top: 30px; padding: 5px;}
                        .sign .middle{margin: 30px 100px;}
                        </style>
                        <div class="row text-center sign">
                            <span class="col-md-3">
                                <strong>Class Teacher</strong>
                            </span>
                            <span class="col-md-3 middle">
                                <strong>Vice Principal</strong>
                            </span>
                            <span class="col-md-3">
                                <strong>Principal</strong>
                            </span>
                        </div> -->
                        <style type="text/css">
                        .sign td{
                        border:none !important;
                        }
                        </style>
                        
                        <table style="width: 100%; margin-top: 100px;" class="sign">
                            <tr>
                                <td align="center">_____________</td>
                                <td align="center">_____________</td>
                                <td align="center">_____________</td>
                            </tr>
                            <tr>
                                <td align="center">Class Teacher</td>
                                <td align="center">Vice Principal</td>
                                <td align="center">Principal</td>
                            </tr>
                        </table>
                    </div>
                </section>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
@section('javascript')
<script>
$(document).ready(function() {
$('#customTable').DataTable();
});
</script>
@endsection
@push('js')
<script>
$('#exam_report_nav').show();
$('#setting_menu').hide();
</script>
<script type="text/javascript">
function printDiv(divName) {
var printContents = document.getElementById(divName).innerHTML;
var originalContents = document.body.innerHTML;
document.body.innerHTML = printContents;
document.body.style.marginTop="0px";
window.print();
document.body.innerHTML = originalContents;
}
</script>
@endpush
