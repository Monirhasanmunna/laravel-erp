@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">

        @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
            <div class="card new-table">
                <div class="card-header" style="background-color: #f3f5f9 !important">
                    <div class="card-title float-left">
                        <h6 style="color: black">Student Attendance List</h6>
                    </div>
                    <div class="btn-wrapper">
                        <a  href="{{route('attendance.student.create')}}" class="btn btn-primary mr-2 float-right"><i class="fa fa-plus"></i> Manual Attendance</a>
                    </div>
                </div>
                <div class="content-wrapper text-primary">
                    <form id="student-form" method="GET">
                        @include('custom-blade.student-attendance-search')
                    </form>
                </div>
            </div>

            <br>
            <div class="card new-table">
                <div id="table" class="card-body pt-0">
                    <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align:center"> #SL </th>
                                <th style="text-align:center"> Date </th>
                                <th style="text-align:center"> ST ID </th>
                                <th style="text-align:center"> Student Name </th>
                                <th style="text-align:center"> Roll </th>
                                <th style="text-align:center"> In Time</th>
                                <th style="text-align:center"> Out Time</th>
                                <th style="text-align:center"> Status </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendance as $item)
                            <tr>
                                <td style="text-align:center">{{$loop->iteration}}</td>
                                <td style="text-align:center">{{$item->date}}</td>
                                <td style="text-align:center">{{@$item->source->id_no}}</td>
                                <td style="">{{@$item->source->name}}</td>
                                <td style="text-align:center">{{@$item->source->roll_no}}</td>
                                <td style="text-align:center">{{date('h:i A', strtotime($item->in_time))}}</td>
                                <td style="text-align:center">{{date('h:i A', strtotime($item->out_time))}}</td>
                                <td style="text-align:center">
                                    @if ($item->status == 'present')
                                        <div class="badge badge-success">Present</div>
                                    @elseif($item->status == 'absent')
                                        <div class="badge badge-danger">Absent</div>
                                    @else
                                        <div class="badge badge-info">Leave</div>
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
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });

        $('#manual_attendance').removeClass('d-none');
        $('.attendance').closest('li').addClass('custom_nav');


        $("#student-form").submit(function(e){
                e.preventDefault();
                var form = $(this);
                var url = "{{ route('attendance.student.get-student')}}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success : (data) => {
                        $("#table").empty();
                        var html = '';
                        $.each(data.attendances,function(i,v){
                            console.log(v);
                            var status = '';
                            if(v.status == 'present'){
                                status = `<span class='badge badge-sm badge-success'>Present</span>` ;
                            }else if(v.status == 'leave'){
                                status = `<span class='badge badge-sm badge-warning'>Leave</span>`;
                            }else{
                                status = `<span class='badge badge-sm badge-danger'>Absent</span>`;
                            }

                            // var dateString = v.date;
                            // var momentObj = moment(dateString);
                            // var formattedDate = momentObj.format('YYYY-M-D');

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
                                    <td style="text-align:center">${i+1}</td>
                                    <td style="text-align:center">${v.date}</td>
                                    <td style="text-align:center">${v.source.id_no}</td>
                                    <td>${v.source.name}</td>
                                    <td style="text-align:center">${v.source.roll_no}</td>
                                    <td style="text-align:center">${intime}</td>
                                    <td style="text-align:center">${outtime}</td>
                                    <td style="text-align:center">${status}</td>
                                </tr>
                            `;
                        });

                        $("#table").html(
                            `<table id="customTable" class="table table-striped table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center"> #SL </th>
                                        <th style="text-align:center"> Date </th>
                                        <th style="text-align:center"> ST ID </th>
                                        <th style="text-align:center"> Student Name </th>
                                        <th style="text-align:center"> Roll </th>
                                        <th style="text-align:center"> In Time</th>
                                        <th style="text-align:center"> Out Time</th>
                                        <th style="text-align:center"> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${html}
                                </tbody>
                            </table>
                        `);
                        $('#customTable').DataTable();
                    }
                });
            });


    </script>
@endpush
