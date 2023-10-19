@extends('admin.layouts.app')
@section('content')
<div class="main-panel" id="marks-id">
  @include($adminTemplate.'.exammanagement.topmenu_exammanagement')
  <style>
  .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
  }
  .switch input {
  opacity: 0;
  width: 0;
  height: 0;
  }
  .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f32121;
  -webkit-transition: .4s;
  transition: .4s;
  }
  .slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  }
  input:checked + .slider {
  background-color: #15964d;
  }
  input:focus + .slider {
  box-shadow: 0 0 1px #15964d;
  }
  input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
  }
  </style>
  <div>
    <div class="card new-table">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <a href="{{route('exam-management.exam-setup.generalsetup')}}" class="btn btn-primary mr-2" style="width: 175px;height: 34px;">General Setting</a>
            </div>
            
            <a href="{{route('exam-management.exam-setup.averagesetup')}}" class="btn btn-primary mr-2" style="width: 175px;height: 34px;">Average Setting</a>
          </div>
          <form action="{{ route('exam-management.exam-setup.update_generalsetup',$asetting->id)}}" method="post">
            @csrf
            <br>
            <center><h3 class="bg-info" style="width: 100%;height: 34px;">Average Exam Setting of Session : {{$asetting->session->title}}, Class: {{$asetting->class->name}}</h3> <br></center>
            <div class="row py-2" id="all-row-py-2">
              <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Select Semester</label>
              
            </div>
            <div class="col-sm-4">
              <table style="width: 100%;">
                @foreach ($asetting->average_exam_setting_detail as $detail)
                <tr>
                  <td width="150"> {{ $detail->exam->name }} %
                    <input type="hidden" value="{{ $detail->exam->id }}" name="exam_id[]">
                  </td>
                  <td>
                    <label class="switch">
                      <input type="checkbox" name="exam_status[]" value="1" {{ old('exam_status', $detail->exam_status) ? 'checked' : '' }}>
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td><input type="number" name="exam_per[]" class="form-control" value="{{ $detail->exam_per }}"></td>
                </tr>
                @endforeach
              </table>
            </div>
            
            
          </div>
          <div class="row py-2" id="all-row-py-2">
            <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Only GPA Average</label>
            
          </div>
          <div class="col-sm-2">
            <label class="switch">
              <input type="checkbox" name="gpa_average" value="1">
              <input type="checkbox" name="gpa_average" value="1" {{ old('class_test', $asetting->gpa_average) ? 'checked' : '' }}>
              <span class="slider"></span>
            </label>
          </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
          <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Attendance Report Show in Transcript</label>
          
        </div>
        <div class="col-sm-2">
          <label class="switch">
            
            <input type="checkbox" name="attn_show" value="1" {{ old('attn_show', $asetting->attn_show) ? 'checked' : '' }}>
            <span class="slider"></span>
          </label>
        </div>
      </div>
      <div class="row py-2" id="all-row-py-2">
        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Class Position Identify</label>
        
      </div>
      
      
      <div class="col-sm-4">
        <table style="width: 100%;">
          <tr>
            <td> GPA Point</td>
            <td><input type="number" name="calss_pos_gpa" class="form-control" required value="{{$asetting->calss_pos_gpa}}"></td>
          </tr>
          <tr>
            <td> Total Marks</td>
            <td><input type="number" name="calss_pos_total" class="form-control" required value="{{$asetting->calss_pos_total}}"></td>
          </tr>
          <tr>
            <td> Attendance</td>
            <td><input type="number" name="calss_pos_atten" class="form-control" required value="{{$asetting->calss_pos_atten}}"></td>
          </tr>
        </table>
        
      </div>
    </div>
    <div class="row py-2" id="all-row-py-2">
      <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Sequentially</label>
      
    </div>
    <div class="col-sm-2">
      <label class="switch">
        
        <input type="checkbox" name="sequentially" value="1" {{ old('sequentially', $asetting->sequentially) ? 'checked' : '' }}>
        <span class="slider"></span>
      </label>
    </div>
  </div>
  <div class="row py-2" id="all-row-py-2">
    <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Non-Sequentially</label>
    
  </div>
  <div class="col-sm-2">
    <label class="switch">
      
      <input type="checkbox" name="non_sequentially" value="1" {{ old('non_sequentially', $asetting->non_sequentially) ? 'checked' : '' }}>
      <span class="slider"></span>
    </label>
  </div>
</div>
<center><button type="submit" class="btn btn-success mt-3" id="save-btn"><i class="fa fa-save"></i>Update</button></center>
</form>
</div>
</div>
</div>
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
$('#session_id').change(function(){
let session_id = $('#session_id').val();
$.get("{{route('student.get-classes')}}",
{
session_id
},
function(data){
let html = ' <label>Class</label><br>';
data.classes.map(function(item){
html += `<input type="checkbox" name="class_id[]" value="${item.id}"> <label>${item.name}</label> `;
});
$('#class_id').html(html);
});
});
$(document).ready(function() {
$('form').submit(function(event) {
//var exam = $("[name='exam_status[]']:checked");
var class_id = $("[name='class_id[]']:checked");
// if (exam.length == 0) {
//   event.preventDefault();
//   alert("Please select at least one Exam!");
// }
if (class_id.length == 0) {
event.preventDefault();
alert("Please select at least one Class!");
}
});
});
</script>
@endpush
