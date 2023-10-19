<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <title>Transcript</title>
        <style>
        .table>thead>tr>th,
        .table>tbody>tr>td {
        padding: 3px;
        font-size: 12px;
        }
        .transcript .table>tbody>tr>td {
        text-align: left !important; vertical-align: middle !important; font-weight: bold;
        }
        .table>thead>tr>th,
        .table>tbody>tr>td {
        text-align: center !important; vertical-align: middle !important; font-weight: bold;
        }
        .logo img, .logo strong {display: inline-block; vertical-align: middle;}
        fieldset {
        border: 1.5px solid green;
        border-left: none;
        border-right: none;
        /* padding: 0 1em 1em 1em !important; */
        margin: 5px 0 0em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 2px 0px #000;
        }
        legend{
        width:inherit;
        font-size: 1em !important;
        font-weight: bold !important;
        text-align: center !important;
        border: 1px solid pink;
        background-color: whitesmoke;
        border-radius: 12px;
        padding: 0px 8px;
        }
        section {
        page-break-after: always;
        }
        .failed {
            background-color: #f2dede;
          }
        @media print {
        @page {
        size: A4;
        
        }

        .failed {
            background-color: #f2dede;
          }
        
        
        }
        </style>
    </head>
    <body class="container-fluid">
        @php
        use App\Helper\Helper;
        $helper = new Helper();
        @endphp
        <br><br><br>
        <br><br><br>
        <a class="btn btn-warning btn-sm" href="#" onclick="printDiv('printableArea')" >Print</a>
        <a class="btn btn-success btn-sm" href="{{ route('exam-management.transcript.index') }}" >Go Back</a>
        <br>
        <div id="printableArea">
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
            //$marks = $details->first()->marks;
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
            $previousTotalMarks = null
            
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
            <section style="border:2px solid green; margin-top: 20px;" class="watermark">
                <div class="text-center logo">
                    <span>
                        <img src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="Logo" height="80" width="80">
                    </span>
                    <strong>
                    <h3 class="text-uppercase">{{Helper::school_info()->name}}</h3>
                    <b>
                    <span>ESTD: {{Helper::school_info()->founded_at}}</span>&nbsp;
                    <span>EIIN: {{Helper::school_info()->eiin_no}}</span>
                    <p>{{Helper::school_info()->address}}</p>
                    </b>
                    </strong>
                </div>
                <fieldset>
                    <legend>
                        <h4>ACADEMIC TRANSCRIPT</h4>
                    </legend>
                    <table class="transcript">
                        <tr class="row">
                            <td class="col-md-9">
                                <table class="table table-bordered">
                                    <tr>
                                        <td rowspan="6" style="text-align: center !important;">
                                            @if(isset($student->photo))
                                            <img src="{{Config::get('app.s3_url').$student->photo}}" alt="Profile pic" height="120" width="100">
                                            @else
                                    <img src="{{asset('male.png')}}" alt="Profile pic" height="120" width="100">
                                    @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Name</td>
                                        <td>:</td>
                                        <td>{{$student->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Student Id</td>
                                        <td>:</td>
                                        <td>{{$student->id_no}} (Class Roll : {{$student->roll_no}})</td>
                                    </tr>
                                    <tr>
                                        <td>Father's Name</td>
                                        <td>:</td>
                                        <td>{{$student->father_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Mother's Name</td>
                                        <td>:</td>
                                        <td>{{$student->mother_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Mobile Number</td>
                                        <td>:</td>
                                        <td>{{$student->mobile_number}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="col-md-3">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Class</td>
                                        <td>:</td>
                                        <td>{{$student->ins_class->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Group</td>
                                        <td>:</td>
                                        <td>{{$student->group->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Section</td>
                                        <td>:</td>
                                        <td>{{$student->section->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Session</td>
                                        <td>:</td>
                                        <td>{{$student->session->title}}</td>
                                    </tr>
                                    <tr>
                                        <td>Semester</td>
                                        <td>:</td>
                                        <td>{{$exam->name}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                @php
                
                $sucount = count($marks_dis_type)+2;
                $sucountbt = count($marks_dis_type);
                @endphp
                <table class="table table-bordered marksheet">
                    <thead>
                        <tr>
                            <th rowspan="2">SL</th>
                            <th rowspan="2">Subject Name</th>
                            <th rowspan="2">Full Mark</th>
                            <th colspan="{{$sucount}}">OBTAINED MARKS</th>
                            <th rowspan="2">GPA</th>
                            <th rowspan="2">Grade</th>
                        </tr>
                        <tr>
                            @foreach ($marks_dis_type as $dis_type)
                            <th>{{$dis_type->title}}</th>
                            @endforeach
                            <th>TOTAL</th>
                            <th>HM</th>
                        </tr>
                    </thead>
                    <style>
                    .buttom tr td{padding: 5px !important;}
                    </style>
                    <tbody class="buttom">
                        
                        @php
                        $sl = 1;
                        $count = 0;
                        $fmak = 0;
                        $failed_subject = 0;
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
                        <?php
                        $marksInput = $result->where('class_subject_id', $subject->id)->first();
                        $marks = 0;
                        ?>
                        @if ($marksInput)
                        <?php
                        $subtype = $helper->getSubMarksDist($section->class->id, $exam->id, $subject->id);
                        $subtotal = 0;
                        
                        $subtotalfull = 0;
                        $subgrage_marks = 0;
                        ?>
                        @foreach ($subtype['mark_dists'] as $type)
                        <?php
                        if ($marksInput) {
                        $details = $marksInput->details->where('student_marks_input_id', $marksInput->id)
                        ->where('student_id', $student->id)
                        ->where('sub_marks_dist_details_id', $type['id']);
                        
                        //if ($details->count() > 0) {
                        //$marks = $details->first()->marks;
                        $marks = $details->count() > 0 ? $details->first()->marks : 0;
                        
                        $subtotal += $marks;
                        $subtotalfull += $type['mark'];
                        
                        if ($subtype['mark_master']->pass_mark == 0) {
                        if ($marks < $type['pass_mark']) {
                        $bg = 'class=failed';
                        $total_marks = 0;
                        $subgrage_marks = 0;
                        $gross_marks += $marks;
                        $full_marks += $subtype['mark_master']->total_mark;
                        if ($subject->subjectType->is_common == 1) {
                        $failed = true;
                        }
                        } else {
                        $bg = '';
                        if ($subject->subjectType->is_common == 1) {
                        $total_marks += $marks;
                        $gross_marks += $marks;
                        $subgrage_marks += $marks;
                        $full_marks += $subtype['mark_master']->total_mark;
                        }
                        }
                        } else {
                        if ($subtotal < $subtype['mark_master']->pass_mark) {
                        $subtotal += $subtype['mark_master']->grace;
                        }
                        
                        if ($subtotal < $subtype['mark_master']->pass_mark) {
                        $bg = 'class=failed';
                        $total_marks = 0;
                        $subgrage_marks = 0;
                        $gross_marks += $subtotal;
                        $full_marks += $subtype['mark_master']->total_mark;
                        if ($subject->subjectType->is_common == 1) {
                        $failed = true;
                        
                        }
                        } else {
                        $bg = '';
                        if ($subject->subjectType->is_common == 1) {
                        $total_marks += $marks;
                        $gross_marks += $marks;
                        $subgrage_marks += $marks;
                        $full_marks += $subtype['mark_master']->total_mark;
                        }
                        }
                        }
                        //}
                        }
                        ?>
                        
                        @endforeach
                        @php
                        if ($failed){
                        $failed_subject++;
                        
                        }
                        $sgrades = $helper->getGradeValue($section->class->id, $subgrage_marks, $subtype['mark_master']->total_mark);
                        
                        
                        @endphp
                        
                        
                        @endif
                        <tr>
                            <td>{{$sl++}}</td>
                            <td>
                                {{$subject->subject->sub_name}}
                                @if ($subject->subjectType->is_common == 0)
                                <br>({{$subject->subjectType->name}})
                                @endif
                            </td>
                            
                            <td>{{$subtype['mark_master']->total_mark}}</td>
                            @foreach ($marks_dis_type as $dis_type)
                            
                            <td>
                                {{$helper->getMarkBySubtype($student->id, $marksInput->id, $dis_type->id);}}
                            </td>
                            @endforeach
                            <td {{$bg}}>{{$subtotal}}</td>
                            @php
                            @endphp
                            <td>{{$helper->getHighestMarks($marksInput->id)->total_marks}}</td>
                            @php
                            $sgrades = $helper->getGradeValue($section->class->id, $subgrage_marks, $subtype['mark_master']->total_mark);
                            $subgrade += $sgrades['gp'];
                            
                            
                            @endphp
                            <td {{$bg}}>{{$sgrades['gp']}}</td>
                            <td {{$bg}}>{{$sgrades['gpa']}}</td>
                        </tr>
                        @endif
                        
                        @endforeach
                        
                        <tr>
                            <td colspan="2">
                                <b class="pull-right">FULL MARK :</b>
                            </td>
                            <td class="bg-info">{{$fmak}}</td>
                            <td colspan="{{$sucountbt}}">
                                <b class="pull-right text-danger">TOTAL MARK :</b>
                            </td>
                            <td class="bg-danger text-danger">{{$gross_marks}}</td>
                            
                        </tr>
                        <!-- <tr>
                            <td colspan="9">
                                <b class="pull-right">Model Test Mark :</b>
                            </td>
                            <td>594</td>
                        </tr>
                        <tr>
                            <td colspan="9">
                                <b class="pull-right">Total Mark - 90 % (679.5) + Model Test Mark - 10 % (59.4) :</b>
                            </td>
                            <td>738.9</td>
                        </tr> -->
                        <tr>
                            @php
                            if ($failed) {
                            $status = "Failed";
                            $bg = 'class=failed';
                            $total_marks = 0;
                            $grademark = 0;
                            
                            } else {
                            $status = "Passed";
                            $bg = '';
                            $grademark = $subgrade / $count;
                            }
                            $totalgrades = $helper->getTotalGrade($section->class->id, $grademark);
                            @endphp
                            <td colspan="2">
                                <b class="pull-right">Position In Class :</b>
                            </td>
                            <td>{{$meritInClass}}</td>
                            <td colspan="3">
                                <b class="pull-right">GPA :</b>
                            </td>
                            <td colspan="2" class="text-danger">@php
                                $formattedNumber = number_format($grademark, 2, '.', '');
                                $formattedNumber = rtrim($formattedNumber, '0');
                                $formattedNumber = rtrim($formattedNumber, '.');
                                @endphp
                            {{$formattedNumber}}</td>
                            <!--  <td colspan="3">Total Subject Failed :</td>
                            <td>{{$failed_subject}}</td> -->
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b class="pull-right">Position In Section:</b>
                            </td>
                            <td>{{$meritInSection}}</td>
                            <td colspan="3">
                                <b class="pull-right">Grade Letter :</b>
                            </td>
                            <td colspan="2" class="text-danger">{{$totalgrades['gpa']}}</td>
                            <td colspan="3">Total Activities Days :</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b class="pull-right">Result Status :</b>
                            </td>
                            <td colspan="6">
                                <b>{{$status}}</b>
                            </td>
                            <td colspan="3">Present Days :</td>
                            <td>@php
                                $atten = $helper->getStudentAtten($student->id, $exam->start_date, $exam->end_date);
                                @endphp
                            {{$atten['atten_count']}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b class="pull-right">School Opinion :</b>
                            </td>
                            <td colspan="6">
                                <b>{{$totalgrades['comment']}}</b>
                            </td>
                            <td colspan="3">Absent Days :</td>
                            <td>
                                @php
                                $date1 = new DateTime($exam->start_date);
                                $date2 = new DateTime($exam->end_date);
                                $interval = $date1->diff($date2);
                                $absent = $interval->days - $atten['atten_count'];
                                @endphp
                                {{$absent}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                
                                <br>
                                <small><br>Class Teacher</small>
                            </td>
                            <td colspan="6">
                                <p style="padding-top: 20px;"><br>Guardian/Parents Signature</p>
                            </td>
                            <td colspan="4">
                                @if (Helper::academic_setting()->signImage)
                                <img src="{{Config::get('app.s3_url').Helper::academic_setting()->signImage}}" alt="Logo"  width="80">
                                @endif
                                <b></b>
                                <br>
                                <small><br>{{Helper::academic_setting()->signText}}</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
            @endforeach
            
        </div>
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
    </body>
</html>
