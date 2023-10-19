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

                        <form action="{{ route('exam-management.exam-setup.save_generalsetup') }}" method="post" id="general">
                                @csrf
                    <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label for="session_id"> Session</label>
                        <select id="session_id" name="session_id" class="form-control" required="">
                            <option value="">Select</option>
                            @foreach ($academic_years as $academic_year)
                            <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4"> <label>Select Exam</label><br>
                        <select id="exam_id" name="exam_id" class="form-control" required="">
                            <option value="">Select</option>
                            @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                            @endforeach
                        </select>


            </div>
                    <div class="col-sm-6" id="class_id">

                </div>


        </div>

        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Class Test %</label>

                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                          <input type="checkbox" name="class_test" value="1">
                          <span class="slider"></span>
                        </label>
                    </div>


                <div class="col-sm-2">

                <input type="number" name="calss_test_per" class="form-control">
            </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Attendance Report Show in Transcript</label>

                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                          <input type="checkbox" name="attn_show" value="1">
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
                        <td><input type="number" name="calss_pos_gpa" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td> Total Marks</td>
                        <td><input type="number" name="calss_pos_total" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td> Attendance</td>
                        <td><input type="number" name="calss_pos_atten" class="form-control" required></td>
                    </tr>
                </table>

            </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Sequentially</label>

                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                          <input type="radio" name="sequentially" value="1">
                          <span class="slider"></span>
                        </label>
                    </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Non-Sequentially</label>

                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                          <input type="radio" name="sequentially" value="">
                          <span class="slider"></span>
                        </label>
                    </div>
        </div>
        <center><button type="submit" class="btn btn-success mt-3" id="save-btn"><i class="fa fa-save"></i>Save</button></center>
                            </form>
    </div>
</div>
</div>
</div>
</div>
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
      $('#setting').addClass('active');
      $('#setting_menu').show();
      $('form').submit(function(event) {
        //var exam = $("[name='exam_id[]']:checked");
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
