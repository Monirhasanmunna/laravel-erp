@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="student-ad" style="overflow: scroll;">
        @include($adminTemplate.'.student.studentnav')
        <div>
            <div class="card new-table mb-2">
                <div class="card-header">
                    <div class="card-title float-left">
                        <h6 style="color: black">Online Student Admission List</h6>
                    </div>
                    {{-- <div class="admission-table">
                    <a class="btn btn-primary  float-right" href="javascript:void(0)" id="confirm-btn">Confirm Student</a> 
                    </div> --}}
                    
                </div>
                <div class="card-body">
                    <form class="confirm-form" action="{{route('student.online-admission.store')}}" method="POST">
                        @csrf
                    <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:center;" width=5%>
                                    <div class="form-check py-0 my-0">
                                        <input type="checkbox" class="form-check-input" checked id="checkAll">
                                        <label class="form-check-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th style="text-align:center;"> Image </th>
                                <th style="text-align:center;"> Addmission No. </th>
                                <th style="text-align:center;" width='10%'> Roll No. </th>
                                <th style="text-align:center;"> Name </th>
                                <th style="text-align:center;"> Session </th>
                                <th style="text-align:center;"> Class </th>
                                <th style="text-align:center;"> F.Name </th>
                                <th style="text-align:center;"> M.Name </th>
                                {{-- <th style="text-align:center;"> Address </th> --}}
                                <th style="text-align:center;"> Religion </th>
                                <th style="text-align:center;"> Gendar </th>
                                <th style="text-align:center;"> Mobile Number </th>
                                {{-- <th width='10%' style="text-align:center;">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admissions as $ad)
                                <tr>
                                    <td style="text-align:center;"><div class="form-check"><input type="checkbox" class="form-check-input"  checked name="check[]" id="exampleCheck1" value="{{$ad->id}}"><label class="form-check-label" for="exampleCheck1"></label></div></td>
                                    <td style="width:10%; text-align:center;">
                                        <img style="width: 30px!important;height: 30px!important;" src="{{@$ad->photo ?Config::get('app.s3_url').$ad->photo:Helper::default_image()}}" alt="">
                                    </td>
                                    <td style="text-align:center;"> {{@$ad->admission_no}} </td>
                                    <td style="text-align:center;"> <input type="number" class="form-control" name="roll_no[{{$ad->id}}]"> </td>
                                    <td style="text-align:center;"> {{@$ad->name}} </td>
                                    <td style="text-align:center;">{{@$ad->session->title}}</td>
                                    <td style="text-align:center;">{{@$ad->class->name}}</td>
                                    <td style="text-align:center;">{{@$ad->father_name}}</td>
                                    <td style="text-align:center;">{{@$ad->mother_name}}</td>
                                    {{-- <td style="text-align:center;">{{$ad->division->name}}-{{$ad->district->name}}-{{$ad->upazila->name}}</td> --}}
                                    <td style="text-align:center;">{{@$ad->religion}}</td>
                                    <td style="text-align:center;"> {{@$ad->gender}} </td>
                                    <td style="text-align:center;">{{@$ad->mobile_number}}</td>
                                    {{-- <td style="text-align:center;">
                                        <a class="btn-sm btn-danger" href="{{route('admission.destroy',$ad->id)}}"><i class="fa-solid fa-trash"></i></a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card new-table">
                <div class="card-body">
                    <div>
                        @include('custom-blade.online-admission-search')
                        <input type="hidden" name="type" value="online_admission">
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });

        $("#confirm-btn").click(function(){
            $(".confirm-form").submit();
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#admission").addClass('custom_nav');
    </script>
@endpush
