@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="student-ad">
        @include($adminTemplate.'.student.studentnav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <div class="card-title float-left">
                        <h6 style="color: black">Student Admission List</h6>
                    </div>
                    <div class="admission-table">
                       <a class="btn btn-secondary  float-right ml-2" href="{{ route('admission.upload') }}">Upload Admission</a>
                    <a class="btn btn-primary  float-right ml-2" href="{{ route('admission.create') }}">New Student Admission</a>
                    <a class="btn btn-primary  float-right" href="javascript:void(0)" id="confirm-btn">Confirm Student</a> 
                    <a class="btn btn-success  float-right mr-2" href="{{route('student.online-admission.list')}}" id="confirm-btn">Online Admission</a> 
                    </div>
                    
                </div>
                <div class="card-body">
                    <form class="confirm-form" action="{{route('student.store')}}" method="POST">
                        @csrf
                    <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align:center;">
                                    <div class="form-check py-0 my-0">
                                        <input type="checkbox" class="form-check-input" checked id="checkAll">
                                        <label class="form-check-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th style="text-align:center;"> Name </th>
                                <th style="text-align:center;"> Roll No </th>
                                <th style="text-align:center;"> Mobile Number </th>
                                <th style="text-align:center;"> Religion </th>
                                <th style="text-align:center;"> Gendar </th>
                                <th width='10%' style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admission as $ad)
                                <tr>
                                    <td style="text-align:center;"><div class="form-check"><input type="checkbox" class="form-check-input"  checked name="check[]" id="exampleCheck1" value="{{$ad->id}}"><label class="form-check-label" for="exampleCheck1"></label></div></td>
                                    <td style="text-align:center;"> {{$ad->name}} </td>
                                    <td style="text-align:center;">{{$ad->roll_no}}</td>
                                    <td style="text-align:center;">{{$ad->mobile_number}}</td>
                                    <td style="text-align:center;">{{$ad->religion}}</td>
                                    <td style="text-align:center;"> {{$ad->gender}} </td>
                                    <td style="text-align:center;">
                                        <a class="btn-sm btn-danger" href="{{route('admission.destroy',$ad->id)}}"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
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

        $("#confirm-btn").click(function(){
            $(".confirm-form").submit();
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
            });
    </script>
@endpush
