@extends('admin.layouts.app')
@section('content')
<div class="main-panel" id="marks-id">
    @include($adminTemplate.'.exammanagement.topmenu_exammanagement')
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
                    
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="card new-table mt-2">
        <div class="card-body pt-2">
            <ul class="nav nav-tabs" style="border:none">
                <li class="nav-item mr-1">
                    <a class="nav-link bg-info active py-2" data-toggle="tab" href="#general_settings">General Settings Data</a>
                </li>
                <li class="nav-item mr-1">
                    <a class="nav-link bg-info py-2" data-toggle="tab" href="#average_settings">Average Settings Data</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="general_settings" class=" tab-pane active">
                    <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">General Settings</h4>
                            </div>
                        </div>
                    
                    <div class="row">
                <div class="col-xl-12 col-sm-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <table style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Session</th>
                                        <th>Exam</th>
                                        <th>Class</th>
                                        <th>Class Test %</th>
                                        <th>Attendance Report Show in Transcript</th>
                                        <th>Class Position Identify</th>
                                        <th>Sequentially</th>
                                        <th>Non-Sequentially</th>
                                        <th style="min-width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($general_exam_setting as $general_setting)
                                    <tr>
                                        <td>{{$general_setting->session->title}}</td>
                                        <td>{{$general_setting->exam->name}}</td>
                                        <td>{{$general_setting->class->name}}</td>
                                        
                                        <td>
                                            @if($general_setting->class_test !== NULL)
                                            On ({{ $general_setting->calss_test_per }}%)
                                            @else
                                            Off ({{ $general_setting->calss_test_per }}%)
                                            @endif
                                        </td>
                                        <td>
                                            @if($general_setting->attn_show !== NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                            </td>
                                        <td>GPA Point-{{ $general_setting->calss_pos_gpa }}<br> Total Marks-{{ $general_setting->calss_pos_total }}<br> Attendance-{{ $general_setting->calss_pos_atten }}</td>
                                        <td>
                                             @if($general_setting->sequentially !== NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                           </td>
                                        <td>
                                             @if($general_setting->sequentially == NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                           </td>
                                        <td>
                                            <a class="btn btn-primary p-1 pr-0" href="{{route('exam-management.exam-setup.edit_generalsetup',$general_setting->id)}}"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                                            <a class="btn btn-danger p-1 pr-0 deleteBtn" href="{{route('exam-management.exam-setup.destroy_generalsetup',$general_setting->id)}}"><i style="margin-left: 0.3125rem;" class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
                </div>


                <div id="average_settings" class=" tab-pane fade">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Average Settings</h4>
                            </div>
                        </div>
                       <div class="row">
                <div class="col-xl-12 col-sm-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <table style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Session</th>
                                        <th>Class</th>
                                        <th style="min-width: 200px;">Exam</th>
                                        <th>Only GPA Average</th>
                                        <th>Attendance Report Show in Transcript</th>
                                        <th>Class Position Identify</th>
                                        <th>Sequentially</th>
                                        <th>Non-Sequentially</th>
                                        <th style="min-width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($average_exam_setting as $average_setting)
                                    <tr>
                                        <td>{{$average_setting->session->title}}</td>
                                        <td>{{$average_setting->class->name}}</td>
                                        <td>
                                            <ul>
                                                @foreach ($average_setting->Average_exam_setting_detail as $detail)
                                                    <li>{{ $detail->exam->name }} - 
                                                        @if($detail->exam_status !== NULL)
                                                        On 
                                                        @else
                                                        Off
                                                        @endif
                                                        -{{ $detail->exam_per }}%
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            @if($average_setting->gpa_average !== NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                        </td>
                                        <td>
                                            @if($average_setting->attn_show !== NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                            </td>
                                        <td>GPA Point-{{ $average_setting->calss_pos_gpa }}<br> Total Marks-{{ $average_setting->calss_pos_total }}<br> Attendance-{{ $average_setting->calss_pos_atten }}</td>
                                        <td>
                                             @if($average_setting->sequentially !== NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                           </td>
                                        <td>
                                             @if($average_setting->sequentially == NULL)
                                            On 
                                            @else
                                            Off
                                            @endif
                                           </td>
                                        <td>
                                            <!-- <a class="btn btn-primary p-1 pr-0" href="{{route('exam-management.exam-setup.edit_averagesetup',$average_setting->id)}}"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a> -->
                                            <a class="btn btn-danger p-1 pr-0 deleteBtn" href="{{route('exam-management.exam-setup.destroy_averagesetup',$average_setting->id)}}"><i style="margin-left: 0.3125rem;" class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
                      
                </div>


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
