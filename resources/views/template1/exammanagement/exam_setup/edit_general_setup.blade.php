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
                    
                       
                          <form action="{{ route('exam-management.exam-setup.update_generalsetup',$gsetting->id)}}" method="post">
                                @csrf
                            <br>
                      <center><h3 class="bg-info" style="width: 100%;height: 34px;">General Exam Setting of Session : {{$gsetting->session->title}}, Exam : {{$gsetting->exam->name}}, Class: {{$gsetting->class->name}}</h3> <br></center>




                                  

        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Class Test %</label>
                       
                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                        
                         <input type="checkbox" name="class_test" value="1" {{ old('class_test', $gsetting->class_test) ? 'checked' : '' }}>


                          <span class="slider"></span>
                        </label>
                    </div>
                   
                
                <div class="col-sm-2"> 
                
                <input type="number" name="calss_test_per" class="form-control" value="{{$gsetting->calss_test_per}}">
            </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Attendance Report Show in Transcript</label>
                       
                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                         
                          <input type="checkbox" name="attn_show" value="1" {{ old('attn_show', $gsetting->attn_show) ? 'checked' : '' }}>
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
                        <td><input type="number" name="calss_pos_gpa" class="form-control" required value="{{$gsetting->calss_pos_gpa}}"></td>
                    </tr>
                    <tr>
                        <td> Total Marks</td>
                        <td><input type="number" name="calss_pos_total" class="form-control" required value="{{$gsetting->calss_pos_total}}"></td>
                    </tr>
                    <tr>
                        <td> Attendance</td>
                        <td><input type="number" name="calss_pos_atten" class="form-control" required value="{{$gsetting->calss_pos_atten}}"></td>
                    </tr>
                </table>
               
            </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Sequentially</label>
                       
                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                          
                           <input type="radio" name="sequentially" value="1" {{ old('sequentially', $gsetting->sequentially) ? 'checked' : '' }}>
                          <span class="slider"></span>
                        </label>
                    </div>
        </div>
        <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label class="btn btn-primary mr-2" style="width: 175px;"> Non-Sequentially</label>
                       
                    </div>
                    <div class="col-sm-2">

                        <label class="switch">
                         
                           <input type="radio" name="sequentially" value="" {{ old('sequentially', $gsetting->sequentially) ? '' : 'checked' }}>
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
