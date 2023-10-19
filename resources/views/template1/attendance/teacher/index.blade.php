@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
        <div id="teacher-attendance">
            @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <p style="color: black">Teacher Attendance List</p>
                    <a  href="{{route('attendance.teacher.create')}}" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Manual Attendance</a>
                </div>
                <div class="content-wrapper text-primary pt-0">

                    <form id="teacher-atten-form" method="GET">
                        <div class="form-row mt-3">
                            <div class="col-3">
                                <label for="">Select Date</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control" name="date" id="">
                            </div>
                            <div class="col-3">
                                <label for="">Select Teacher</label>
                                <select name="teacher_id" class="form-control" id="teacher_id">
                                    <option value="all">All Teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <button type="submit" class="btn btn-primary" style="margin-top: 35px; margin-left:15px;"><i class="fa-solid fa-arrow-circle-right"></i> Process</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card new-table">
                <div id="table" class="card-body pt-0">
                    <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th> #SL </th>
                                <th> Date </th>
                                <th> Name </th>
                                <th> Designation </th>
                                <th> In Time </th>
                                <th> Out Time </th>
                                <th> Status </th>
                            </tr>
                        </thead>
                        <tbody id="t_body">
                            @foreach ($attendances as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{@$item->date}}</td>
                                    <td>{{@$item->source->name}}</td>
                                    <td>{{@$item->source->designation->title}}</td>
                                    <td>{{date('h:i A', strtotime(@$item->in_time))}}</td>
                                    <td>{{date('h:i A', strtotime(@$item->out_time))}}</td>
                                    <td>
                                        @if (@$item->status == 'present')
                                            <div class="badge badge-success">Present</div>
                                        @elseif(@$item->status == 'leave')
                                            <div class="badge badge-warning">Leave</div>
                                        @else
                                            <div class="badge badge-danger">Absent</div>
                                        @endif
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
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });

        $('#designation_id').change(function(){
                let designation_id = $(this).val();
                var url = "{{ route('attendance.teacher.get-teacher-by-designation') }}";

                $.ajax({
                    type: 'GET',
                    url : url,
                    data:{
                        designation_id
                    },
                    success:function(data){
                        let html = '<option value="">Select Teacher</option>';
                        $.each(data,function(i,v){
                            html += `<option value="${v.id}">${v.name}</option>`
                        });
                        $('#teacher_id').html(html);
                    }
                })

            });

            $("#teacher-atten-form").submit(function(e){
                e.preventDefault();
                var form = $(this);
                var url = "{{ route('attendance.teacher.get-teacher-attendanceByDate') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success : (data) => {
                       console.log(data);
                        $('#table').empty();
                        var html = '';

                        $.each(data,function(i,v){
                            var status = '';
                            if(v.status == 'present' || v.status == 'IN'){
                                status = `<span class='badge badge-sm badge-success'>Present</span>` ;
                            }else if(v.status == 'leave'){
                                status = `<span class='badge badge-sm badge-warning'>Leave</span>`;
                            }else{
                                status = `<span class='badge badge-sm badge-danger'>Absent</span>`;
                            }


                            var dateString = v.date;
                            var momentObj = moment(dateString);
                            var formattedDate = momentObj.format('D MMMM, YYYY');


                            var now = v.in_time;
                            var date = new Date();
                            date.setHours(now.split(":")[0]);
                            date.setMinutes(now.split(":")[1]);
                            var intime = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                            var now2 = v.out_time;
                            var date2 = new Date();
                            date2.setHours(now2.split(":")[0]);
                            date2.setMinutes(now2.split(":")[1]);
                            var outtime = date2.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                            html += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${v.date}</td>
                                    <td>${v.source.name}</td>
                                    <td>${v.source.designation.title}</td>
                                    <td>${intime}</td>
                                    <td>${outtime}</td>
                                    <td>${status}</td>
                                </tr>
                            `;
                        });


                        // full table assign by jquery with data
                        $("#table").html(`
                            <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th> #SL </th>
                                        <th> Date </th>
                                        <th> Name </th>
                                        <th> Designation </th>
                                        <th> In Time </th>
                                        <th> Out Time </th>
                                        <th> Status </th>
                                    </tr>
                                </thead>
                                <tbody id="t_body">
                                    ${html}
                                </tbody>
                            </table>
                        `);
                        $('#customTable').DataTable();
                    }
                });
            });


    $('#manual_attendance').removeClass('d-none');
    </script>
@endpush
