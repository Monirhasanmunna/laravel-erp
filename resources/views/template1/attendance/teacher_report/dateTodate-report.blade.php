@extends('admin.layouts.app')

@push('css')
    <style>
        .boldText{
            font-weight: 400;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">

        @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Date To Date Report</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="content-wrapper text-primary" >
                        <h6>Select Date Range</h6>
                        <hr>
                        <form action="" id="teacher-atten-form" method="GET">
                            <div class="form-row">
                                <div class="col">
                                    <label for="">From Date</label>
                                    <input type="date" value="{{date('Y-m-d')}}" class="form-control" name="from_date" id="">
                                </div>
                                <div class="col">
                                    <label for="">To Date</label>
                                    <input type="date" value="{{date('Y-m-d')}}" class="form-control" name="to_date" id="">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Process</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <br>
            <div class="card d-none new-table" style="border: 1px solid #efefef;" id="table-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div id="report_title_name">
                            
                        </div>
                    </div>
                    <table id="customTable" class="table table-bordered dt-responsive nowrap" width="100%">
                        <thead id="t_head">
                            
                        </thead>

                        <tbody id="t_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            
            $('#teacher-atten-form').submit(function(e) {
                e.preventDefault();
                var form= $(this);
                var url = "{{ route('attendance.report.datetodatereport.get-datetodateReport') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function(data){

                        console.log(data);

                        

                       // $("#report_title_name").html(`<p class="card-description" style="color:rgba(0, 0, 0, 0.5)">Date : ${data.form_date} to ${data.to_date}</p>`);

                        let tbody='';
                        let table_th='';
                        let inAndOutTime='';

                        if(data.teachers.length > 0){
                            $.each(data.teachers,function(i,v){


                                var teacher_in_time = v.teacher_time.in_time;
                                var teacher_out_time = v.teacher_time.out_time;

                                var attend = '';
                                
                                $.each(v.attendance,function(index,value){
                                    //console.log(value);
                                    var now = value.in_time;
                                    var date = new Date();
                                    date.setHours(now.split(":")[0]); 
                                    date.setMinutes(now.split(":")[1]); 
                                    var intime = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                                    var now2 = value.out_time;
                                    var date2 = new Date();
                                    date2.setHours(now2.split(":")[0]); 
                                    date2.setMinutes(now2.split(":")[1]);
                                    var outtime = date2.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                                    let late = teacher_in_time < value.in_time;
                                    let early = teacher_out_time > value.out_time;


                                    attend += `<td class='text-center'><span class='${late ? 'text-danger' : ''}'>${intime}</span> - <span class='${early ? 'text-danger' : ''}'>${outtime}</span></td>`; 
                                });

                                tbody += `<tr>
                                            <td class='text-center'>${v.name}</td>
                                            <td class='text-center'>${v.id_no}</td>
                                            <td class='text-center'>${v.designation.title}</td>
                                            ${attend}
                                        </tr>`;
                            });

                        }else{
                            tbody = `<tr >
                                        <td colspan="6"><p class="text-center text-danger">No Data Found!</p></td>
                                    </tr>`;
                        }


                        var date = '';
                        $.each(dates,function(i,v){
                            date += `<th class='text-center'>${v}</th>`;
                        });

                       var tablehead = `<tr>
                                            <th class='text-center'>Employ Name</th>
                                            <th class='text-center'>ID</th>
                                            <th class='text-center'>Designation</th>
                                            ${date}
                                        </tr>`;

                        $('#t_head').html(tablehead);
                        $('#t_body').html(tbody);
                        $('#table-card').removeClass('d-none');

                        
                    }
                });
            });

           

           
            $(".dateTodate-report").closest('li').addClass('custom_nav');
            $('.setting').closest('li').removeClass('custom_nav');
            $('.manageLeave').closest('li').removeClass('custom_nav');
            $("#report-item").removeClass('d-none');
            $('.report').closest('li').addClass('custom_nav');

        });
    </script>
@endpush
