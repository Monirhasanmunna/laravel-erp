@extends('admin.layouts.app')
@push('css')

@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <div>
                        <p class="card-title" style="color:black">Student Attendance Create</p>
                        <p class="card-description"> Student attendance create from here, </p>
                    </div>
                    <a  href="{{route('attendance.student.index')}}" class="btn btn-primary" ><i class="fa fa-list"></i> List
                    </a>
                </div>
                <div class="card-body">


                    <form id="student-form" method="GET">
                        @include('custom-blade.search-student-attend-create')
                    </form>

                    <div class="row mt-3" id="table-card" style="display: none">
                        <div class="col-md-12">
                            <div class="card">
                                <form action="{{route('attendance.student.store')}}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <table class="table table-hover" style="border: 1px solid grey">
                                            <thead>
                                                <th>
                                                    <div class="form-check py-0 my-0">
                                                        <input type="checkbox" class="form-check-input"  checked id="checkAll">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th>
                                                <th>ST ID</th>
                                                <th>Student Name</th>
                                                <th>Roll</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                                <th class="text-center" width='20%'>Status</th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
        $('#manual_attendance').removeClass('d-none');
        $('.attendance').closest('li').addClass('custom_nav');

            $('#student-form').submit(function(e) {
                e.preventDefault();
                var form= $(this);
                var url = "{{ route('student.get-admited-students') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        $('#table-card').css('display','block');
                        let html = '';

                        console.log(data);

                        if(data.students.length > 0){
                            $.each(data.students,function(i,value){
                            html += ` <tr>
                                        <input type="hidden" name="student_id[]" value="${value.id}"/>
                                        <input type="hidden" name="date" value="${data.searchItems.date}"/>
                                        <td scope="col">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input checkbox" value="${i}" name="check[]" id="exampleCheck1" checked>
                                                <label class="form-check-label" for="exampleCheck1"></label>
                                            </div>
                                        </td>
                                        <td>${value.id_no}</td>
                                        <td>${value.name}</td>
                                        <td>${value.roll_no}</td>
                                        <td>
                                            <input type="time" value="${data.searchItems.in_time}" class="form-control in_time_input" name="in_time_input[]" />
                                        </td>
                                        <td>
                                            <input type="time" value="${data.searchItems.out_time}" class="form-control out_time_input" name="out_time_input[]" />
                                        </td>
                                        <td>
                                            <div class="switch-toggle switch-3 switch-candy">
                                                <input name="attend_status${value.id}" type="radio" checked value="present"
                                                    id="present${value.id}">
                                                <label for="present${value.id}">Present</label>
                                                <input name="attend_status${value.id}" value="absent" type="radio"
                                                    id="absent${value.id}" >
                                                <label for="absent${value.id}">Absent</label>
                                                <input name="attend_status${value.id}" value="leave" type="radio"
                                                    id="leave${value.id}" >
                                                <label for="leave${value.id}">Leave</label>
                                            </div>
                                        </td>
                                      </tr>`;
                        });
                        }
                        else{
                            html += `<tr>
                                        <td colspan="6"><p class="text-center text-danger">No Data Found!</p></td>
                                        </tr>
                                    `;
                        }


                        $('tbody').html(html);
                    }


                })
            });


            $(document).on('change','.checkbox',function(){
                var checked = $(this).is(':checked');
                var input   = $(this).closest('tr').find('input[type="time"]');
                var select = $(this).closest('tr').find('select,input');
                input.prop('required', checked);
                select.prop('required', checked)
            });
        });
    </script>
@endpush
