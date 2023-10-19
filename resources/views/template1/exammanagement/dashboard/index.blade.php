@php
use App\Helper\Helper;
$helper = new Helper();
@endphp
@extends('admin.layouts.app')
@section('content')
<div class="main-panel">
    @include($adminTemplate.'.exammanagement.topmenu_exammanagement')
    <div>
        <div class="card new-table">
            <div class="card">
                <div class="card-body">
                    <form id="student-form" action="{{ route('exam-management.dashboard.dashboard-result') }}" method="POST">
                            @csrf

                        @include('custom-blade.search-student3')
                    </form>
    <br>
     @if ($result)
     <a class="btn btn-warning btn-sm" href="#" onclick="printDiv('printableArea')" style="float: right;">Print</a><br>
    <h4>Session: {{$exam->session->title}}, Class:{{$section->class->name}}, Shift: {{$section->shift->name}}, Section: {{$section->name}} Result of {{$exam->name}} Exam</h4> <br>

    <div class="row" id="printableArea">
        <style type="text/css">
            @media print {
  .printableArea {
    background-color: transparent !important;
  }
}
        </style>
        
        <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <div class="d-flex align-items-center align-self-start">
                                <h3 class="mb-0 text-black">{{count($subjects)}}</h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="icon icon-box-success ">
                                <span class="mdi mdi-school icon-item"></span>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total Subjects</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <div class="d-flex align-items-center align-self-start">
                                @php
                                    $entry =0; 
                                    $remaining =0; 
                                    
                                    @endphp
                                    @foreach ($subjects as $subject)
                                    @php
                                    $input = $result->where('class_subject_id', $subject->id)->first();
                                    @endphp
                                     @if ($input)
                                     @php $entry +=1; @endphp
                                     @else
                                     @php $remaining +=1; @endphp
                                     @endif
                                     @endforeach
                                <h3 class="mb-0 text-black">
                                    {{$entry}}
                                </h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="icon icon-box-success">
                                <span class="mdi mdi-arrow-top-right icon-item"></span>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Marks Entry</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <div class="d-flex align-items-center align-self-start">
                                <h3 class="mb-0 text-black">{{$remaining}}</h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="icon icon-box-danger">
                                <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Remaining Entry</h6>
                </div>
            </div>
        </div>
 @foreach ($subjects as $subject)

        @php
        $pass =0; 
        $fail =0; 
        $input = $result->where('class_subject_id', $subject->id)->first();
        @endphp
        @if ($input)
        @php
         foreach ($students as $student){
        $tabinfo = $helper->getTabulation($student->id, $exam->id, $subject->id, $section->class->id); 
        if ($tabinfo['passOrFail'] == "Pass"){
                                                $pass +=1;
                                            }else  if ($tabinfo['passOrFail'] == "Fail"){
                                                $fail +=1;
                                            }
        }
        @endphp
        <div class="col-xl-12 col-sm-12 grid-margin stretch-card">
                   <div class="card">
                <div class="card-body ">
                    <h6 class="text-muted font-weight-normal">{{$subject->subject->sub_name}} (Pass ({{$pass}} Std, Failed {{ $fail}} Std)
                    (
                    @foreach ($grades as $gr)
                    {{$gr->gpa_name}} :
                     <?php
                            $gradecount =0; 

                            foreach ($students as $student){
                                
                                    $tabinfo = $helper->getTabulation($student->id, $exam->id, $subject->id, $section->class->id); 
                               
                                if ($tabinfo['gradePoint'] == $gr->gpa_point){
                                    $gradecount +=1; 
                                }
                                }
                               echo $gradecount.", "; 
                            
                            
                           
                       
                         ?>
                    @endforeach
                    )
                    </h6>
                </div>
            </div>
        </div>
      @endif
       @endforeach
    </div>
@endif
</div>
</div>
    <div class="card">
        <div class="card-header">
            <p>Result Report Summary</p>
            <div class="d-flex">
                <select class="form-control" name="session_id">
                    <option value="">2023</option>
                </select>
                <select class="form-control ml-2" name="session_id">
                    <option value="">1st Semister</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Category</th>
                        <th>Group</th>
                        <th>Students</th>
                        <th>Total Subjects</th>
                        <th>Entry</th>
                        <th>Remaining</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

</div>


</div>
@endsection
@push('js')
<script>
$(document).ready(function() {
$('#customTable').DataTable();
});
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
