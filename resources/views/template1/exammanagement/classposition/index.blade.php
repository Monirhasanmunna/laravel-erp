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
            
            <h6>Class Position Sheet</h6>
            <hr>
            <form id="student-form" action="{{ route('exam-management.report.class-position.class-position-result') }}" method="POST">
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
                <section>
                    <div id="printableArea">
                        @push('css')
                        <style>
                        
                         .table thead tr th, .table tbody tr td {
                            text-align: center !important; vertical-align: middle !important;

                        }
                        .logo img, .logo strong {display: inline-block; vertical-align: middle;}
                        .logo img, .logo span {display: inline-block; vertical-align: middle;}
                        .sign{width: auto; float: right; border-top: 1px dashed black; margin-top: 70px;}
                        
                        .table th img, .jsgrid .jsgrid-table th img, .table td img, .jsgrid .jsgrid-table td img {
                            width: 40px;
                            height: 40px;
                            border-radius: 100%;
                        }

                        @media print {
                        @page {
                        size: legal;
                        
                        }
                        .table thead tr th, .table tbody tr td {
                        /*font-size: 10px !important;*/
                        padding: 5 !important;
                         border: 1px solid #000 !important;
                         color: #000;
                        
                        }
                        .sign{width: auto; float: right; border-top: 1px dashed black; margin-top: 70px;}
                        }

                        .table th img, .jsgrid .jsgrid-table th img, .table td img, .jsgrid .jsgrid-table td img {
                            width: 40px;
                            height: 40px;
                            border-radius: 100%;
                        }

                        /*#printableArea {

                             background-image: url({{Config::get('app.s3_url').Helper::academic_setting()->image}});
                             background-repeat: no-repeat;
                              background-position: center;
                              background-opacity: 0.5;
                              
                            }*/
                        
                        </style>
                        @endpush
                    <div class="text-center logo">
                        <span>
                            @if(@Helper::academic_setting()->image)
                            <img src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" class="gradient-corona-img img-fluid" alt="Logo" height="80" width="80">
                            @endif
                        </span>
                        <strong>
                        <h3 class="text-uppercase">{{Helper::school_info()->name}}</h3>
                        <h5>
                        <span>ESTD: {{Helper::school_info()->founded_at}}</span>
                        &nbsp;
                        <span>EIIN: {{Helper::school_info()->eiin_no}}</span>
                        <p>{{Helper::school_info()->address}}</p>
                        </h5>
                        <h4 class="text-uppercase">Student class position list</h4>
                        </strong>
                    </div>
                    <br>
                    <table class="table table-bordered" style="width: 100%; margin-bottom: 10px;">
                            <tr>
                                <td><strong>Class: {{$section->class->name}}</strong></td>
                                <td><strong>Section: {{$section->shift->name}}-{{$section->name}}</strong></td>
                                <td><strong>Session: {{$exam->session->title}}</strong></td>
                                <td><strong>Semester: {{$exam->name}}</strong></td>
                            </tr>
                        </table>
                    
                    <table class="table table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Class Position</th>
                                <th>Section Position</th>
                                <th>Roll</th>
                                <th>Image</th>
                                <th width="25%">Name</th>
                                <th>Total Mark</th>
                                <th>Obtained</th>
                                <th>GPA</th>
                                <th>Grade</th>
                                <th>Atd Days</th>
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
                                if ($details->count() > 0) {
                                $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                $subtotal += $marks;
                                }
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
                                if ($details->count() > 0) {
                                $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                $subtotal += $marks;
                                }
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
                                <td>{{$meritInClass}}</td>
                                <td>{{$meritInSection}}</td>
                                <td>{{$student->roll_no}}</td>
                                <td class="logo">
                                    @if(isset($student->photo))
                                    <img src="{{Config::get('app.s3_url').$student->photo}}" alt="Profile pic" height="90" width="80">
                                    @else
                                    <img src="{{asset('male.png')}}" alt="Profile pic"  height="90" width="80">
                                    @endif
                                </td>
                                <td>
                                    <span class="text-uppercase">
                                        {{$student->name}}
                                    </span>
                                </td>
                                <?php
                                    $count = 0;
                                    $fmak = 0;
                            
                                    foreach ($subjects as $subject)
                                    {
                                    $input = $result->where('class_subject_id', $subject->id)->first();
                                    if ($input)
                                    {
                                    $count += 1;
                                    
                                    $marksInput = $result->where('class_subject_id', $subject->id)->first();
                                    $marks = 0;
                                    if ($marksInput){
                                    $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                                    $fmak += $subtype['mark_master']->total_mark;
                                    $subtotal = 0;
                                    
                                    $subtotalfull = 0;
                                    $subgrage_marks = 0;
                                    foreach ($subtype['mark_dists'] as $type){
                                    $details = $marksInput->details->where('student_marks_input_id', $marksInput->id)
                                    ->where('student_id', $student->id)
                                    ->where('sub_marks_dist_details_id', $type['id']);
                                    $marks = $details->count() > 0 ? $details->first()->marks : 0;
                                    $subtotal += $marks;
                                    $subtotalfull += $type['mark'];
                                    
                                    if ($subtype['mark_master']->pass_mark == 0) {
                                    if ($marks < $type['pass_mark']) {
                                    $bg = '#f2dede';
                                    $total_marks = 0;
                                    $subgrage_marks = 0;
                                    $gross_marks += $marks;
                                   
                                    if ($subject->subjectType->is_common == 1) {
                                    $failed = true;
                                    }
                                    } else {
                                    $bg = '';
                                    if ($subject->subjectType->is_common == 1) {
                                    $total_marks += $marks;
                                    $gross_marks += $marks;
                                    $subgrage_marks += $marks;
                                    
                                    }
                                    }
                                    } else {
                                    if ($subtotal < $subtype['mark_master']->pass_mark) {
                                    $subtotal += $subtype['mark_master']->grace;
                                    }
                                    
                                    if ($subtotal < $subtype['mark_master']->pass_mark) {
                                    $bg = '#f2dede';
                                    $total_marks = 0;
                                    $subgrage_marks = 0;
                                    $gross_marks += $subtotal;
                                    
                                    if ($subject->subjectType->is_common == 1) {
                                    $failed = true;
                                    }
                                    } else {
                                    $bg = '';
                                    if ($subject->subjectType->is_common == 1) {
                                    $total_marks += $marks;
                                    $gross_marks += $marks;
                                    $subgrage_marks += $marks;
                                    
                                    }
                                    }
                                    }
                                    
                                    

                                }
                                $sgrades = $helper->getGradeValue($section->class->id, $subgrage_marks, $subtype['mark_master']->total_mark);
                                    $subgrade += $sgrades['gp']; 

                                }
                                }

                                    }
                                    
                                    
                                  
                                    if ($failed) {
                                    $status = "Failed";
                                    $bg = '#f2dede';
                                  
                                    $grademark = 0;
                                    } else {
                                    $status = "Passed";
                                    $bg = '';
                                    $grademark = $subgrade / $count;
                                    }
                                    
                                   
                                    $totalgrades = $helper->getTotalGrade($section->class->id, $grademark);
                                    ?>
                                    <td>{{$fmak}}</td>
                                    <td bgcolor="{{$bg}}">{{$gross_marks}}</td>
                                    <td bgcolor="{{$bg}}">
                                        @php 
                                        $formattedNumber = number_format($grademark, 2, '.', '');

                                        $formattedNumber = rtrim($formattedNumber, '0'); 
                                        $formattedNumber = rtrim($formattedNumber, '.');
                                        @endphp
                                    {{$formattedNumber}}</td>
                                    <td bgcolor="{{$bg}}">{{$totalgrades['gpa']}}</td>
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
                    
                    <div class="sign">
                       
                        <p style="text-align: right;">{{Helper::academic_setting()->signText}}</p>
                    </div>
                </section>
                </div>
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
