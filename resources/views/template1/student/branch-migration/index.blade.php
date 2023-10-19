@extends('admin.layouts.app')
@push('css')
    <style>
        td{
            height: 38px!important;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.student.studentnav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6 class="float-left">Student Branch Migration</h6>
                        <div class="float-right">
                            <a href="{{route('student.branch-migration.student-list')}}" class="btn btn-dark" ><i class="fa fa-list"></i>Migrated Student</a>

                        </div>
                    </div>
                    <div class="card-body">

                        <form id="session-form" method="GET">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <select name="session_id" class="form-control" id="session_id">
                                        <option value="">Select Session</option>
                                        @foreach ($sessions as $session)
                                            <option value="{{$session->id}}">{{$session->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="class_id" class="form-control" id="class_id">
                                        <option value="">Select Class</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="branch_id" class="form-control" id="branch_id">
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-2 mt-1">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Process</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-2 mb-3 d-none" id="preload"></div>
                <form action="{{route('student.branch-migration.store')}}" method="POST" onsubmit="return submitForm(this);">
                    @csrf
                    <input type="hidden" name="branch_id" id="bra_id">
                    <div class="card" id="std-card">
                        <div class="card-header">
                            <p>Student List</p>
                            {{-- <a href="" target="_blank" class="btn btn-success" id="printBtn"><i class="fa fa-print"></i>Print</a> --}}
                            <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right"></i>Migrate</button>
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
                                        <th>Id No</th>
                                        <th>Name</th>
                                        <th>Roll No</th>
                                        <th>Class</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script>
        function submitForm(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Confirm it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
            return false;
        }
        $(document).ready(function() {



            $("#checkAll").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $('#branch_id').change(function(){
                $('#bra_id').val($(this).val());
            });

            $('#session_id').change(function(){
                let session_id = $(this).val();
                $.ajax({
                    url : '/sms/student-sms/get-class/'+session_id,
                    type: 'Get',
                    success: (data)=>{
                        $("#class_id").html(`<option>Select class</option>`);
                        $.each(data,function(i,v){
                            $("#class_id").append(`<option value='${v.id}'>${v.name}</option>`);
                        });
                    }
                });
            });


            $('#session-form').submit(function(e){
                e.preventDefault();
                let form = $(this);

                $.get("{{route('student.branch-migration.get-students')}}",form.serialize(),function(data){

                    let html = "";
                    $.each(data,function(idx,val){
                        html += `<tr>
                                    <td style="text-align:center;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"  checked name="student_ids[]" id="exampleCheck1" value="${val.id}">
                                            <label class="form-check-label" for="exampleCheck1"></label>
                                        </div>
                                    </td>

                                    <td>${val.id_no}</td>
                                    <td>${val.name}</td>
                                    <td>${val.roll_no}</td>
                                    <td>${val.class}</td>
                                    <td>${val.phone}</td>
                                </tr>
                                `;
                    });
                    $('tbody').html(html);
                });
            });
        });
    </script>
@endpush
