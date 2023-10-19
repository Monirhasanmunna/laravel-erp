@extends('admin.layouts.app')
@push('css')
    <style>
        td {
            height: 38px !important;
        }
        #btn-submit{
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.student.studentnav')
        <form action="{{route('student.branch-migration.student-store')}}">
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6 class="float-left">Migrated Student List</h6>
                        <div class="float-right">
                            <a href="{{route('student.branch-migration.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('custom-blade.student-migration-search')
                    </div>
                </div>

                <div class="mt-2 mb-3 d-none" id="preload"></div>
                <div class="card" id="std-card">
                    <div class="card-header">
                        <p>Student List</p>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align:center;">
                                        <div class="form-check py-0 my-0">
                                            <input type="checkbox" class="form-check-input" checked id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>Image</th>
                                    <th>Id No</th>
                                    <th>Name</th>
                                    <th>Roll No</th>
                                    <th>Class</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td style="text-align:center;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"  checked name="student_ids[]" id="exampleCheck1" value="{{$student->id}}">
                                                <label class="form-check-label" for="exampleCheck1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{Config::get('app.s3_url')}}{{$student->photo == null? 'male.png':$student->photo}}" alt="">
                                        </td>
                                        <td>{{$student->id_no}}</td>
                                        <td>{{$student->name}}</td>
                                        <td width="9%" class="role_no">
                                            <input type="number" name="" class="form-control " id="">
                                        </td>
                                        <td>{{$student->class}}</td>
                                        <td>{{$student->mobile_number}}</td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $("#checkAll").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
                inputRequired();
            });

            $('input:checkbox').click(function (){
                inputRequired();
            });
            inputRequired();
            function inputRequired(){

                let roleTd = $(".role_no").get();

                roleTd.forEach(function (elem){
                    let checkBox = $(elem).prev().prev().prev().prev().children().children().prop('checked');
                    if(checkBox === true){
                        $(elem).children().prop('required',true).attr('name', 'roll_no[]');
                    }
                    else{
                        $(elem).children().prop('required',false).attr('name', '');
                    }
                });
            }

        });
    </script>
@endpush
