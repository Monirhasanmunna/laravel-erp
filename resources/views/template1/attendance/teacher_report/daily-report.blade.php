@extends('admin.layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <style>
        .boldText{
            font-weight: 400;
        }


        @media print{

            
            .top-navbar{
                visibility: hidden;
            }

            #search-card{
                visibility: hidden;
            }

            #sidebar{
                visibility: hidden;
            }

            #printBtn{
                visibility: hidden;
            }

            #body{
                margin: 0px;
            }

            #table-card{
                margin-top: -390px !important;
            }

            #table-card #tabe-header-title{
                text-align: center;
            }

        }

       
        
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
            <div class="card new-table" id="search-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Daily Report (Teacher)</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="text-right">
                            <a href="{{route('attendance.report.teacher_dailyreport.index')}}" id="teacherDailyReportBtn" class="btn btn-sm btn-secondary">Teacher Daily Report</a>
                            <a href="{{route('attendance.report.student_dailyreport.index')}}" id="studentDailyReportBtn" class="btn btn-sm btn-secondary">Student Daily Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="content-wrapper text-primary" >
                        <form action="" id="teacher-atten-form" method="GET">
                            <div class="form-row">
                                <div class="col-4">
                                    <label for="">Select Date</label>
                                    <input type="date" value="{{date('Y-m-d')}}" class="form-control" name="from_date" id="">
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
            <div class="card d-none  new-table" style="border: 1px solid #efefef;" id="table-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between" id="tabe-header-title">
                        <div id="report_title_name">
                            
                        </div>
                        {{-- <div id="total_present">

                        </div> --}}
                    </div>
                        <div class="text-right">
                            <a href="javascript:void(0)" id="printBtn" onclick="printData()" class="btn btn-sm btn-primary">Print</a>
                        </div>
                    <table id="customTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class='text-center'>SL</th>
                                <th class='text-center'>Employee Name</th>
                                <th class='text-center'>ID</th>
                                <th class='text-center'>Designation</th>
                                <th class='text-center'>Check In</th>
                                <th class='text-center'>Check Status</th>
                                <th class='text-center'>Check Out</th>
                                <th class='text-center'>Check Out Status</th>
                                <th class='text-center'>Hours</th>
                                <th class='text-center'>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div id="preload" style="margin-top: 10px">

                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script>
        function printData(){
                $("#tabe-header-title").removeClass('d-flex','justify-content-between');
                $("#report_title_name").prepend(`<h3 class="card-title" id='school_name' style="color:rgba(0, 0, 0, 0.5)">{{@Helper::school_info()->name}}</h3>`);
                print();
                $("#school_name").empty();
        }
        
        $(document).ready(function() {

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;

            $('#teacher-atten-form').submit(function(e) {
                e.preventDefault();

                $('#preload').html(loader);

                var form= $(this);
                var url = "{{ route('attendance.report.teacher_dailyreport.get-Teacher-DailyReport') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        console.log(data);
                        let titleName = `
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Daily Attendance Report (Teacher)</h4>
                            <h5 class="card-description" style="color:rgba(0, 0, 0, 0.5)">Date : ${data.date}</h5>
                        `;

                        $("#report_title_name").html(titleName);
                        $('#table-card').removeClass('d-none');

                        let html='';
                        let total_present = [];
                        let total_teacher = '';
                        if(data.attendances.length > 0){
                            $.each(data.attendances,function(i,v){
                                //variables
                                let checkInStatus ='-';
                                let checkInStatusColour = '';
                                let checkOutStatus ='';
                                let checkOutStatusColour = '';
                                let status ='';
                                let statusColour = '';
                                let absent ='';
                                //variables

                               // console.log(v);

                            if(v.total_pesent != null){
                              total_present.push(v.total_pesent);  
                            }
                            total_teacher = v.total_teacher;  

                            var time1       = new Date(`2023-05-16T${v.in_time}`);
                            var time2       = new Date(`2023-05-16T${v.teacher_time.in_time}`);

                            //console.log(v.in_time);

                            if(v.in_time){
                                checkInStatus = 'In time';
                            }else{
                                checkInStatus ='-';
                            }

                            //attendance early in time count start
                                if(time2 > time1 && time1){
                                    // Parse the input times into Date objects
                                    var time1 = new Date(`2023-05-16T${v.in_time}`);
                                    var time2 = new Date(`2023-05-16T${v.teacher_time.in_time}`);

                                    var timeDiffMinutes = Math.round((time2 - time1) / (1000 * 60));
                                    var hours = Math.floor(timeDiffMinutes / 60);
                                    var minutes = timeDiffMinutes % 60;

                                    checkInStatus = 'Early '+'('+hours + "h " + minutes + "min)";
                                    
                                    checkInStatusColour = 'text-success';
                                }
                            //attendance early in time count end


                            //attendance late in time count start
                                var maxDelayTime = parseInt(v.teacher_time.max_delay_time);
                                time2.setMinutes(time2.getMinutes() + maxDelayTime);

                                if(time1 > time2 && time1 != ''){
                                    var timeDiffMinutes = Math.round((time1 - time2) / (1000 * 60));
                                    var hours = Math.floor(timeDiffMinutes / 60);
                                    var minutes = timeDiffMinutes % 60;
                                    
                                    checkInStatus = 'Late '+'('+hours + "h " + minutes + "min)";
                                    checkInStatusColour = 'text-danger';
                                }
                            //attendance late in time count ends
                            

                            if(v.out_time){
                                checkOutStatus = 'In time';
                            }else{
                                checkOutStatus ='-';
                            }

                            var maxEarlyTime = v.teacher_time.max_early_time;

                            var outTime = new Date(`2023-05-16T${v.out_time}`);
                            var T_outTime = new Date(`2023-05-16T${v.teacher_time.out_time}`);

                            T_outTime.setMinutes(T_outTime.getMinutes() - parseInt(maxEarlyTime));

                            //attendance Early out time count start
                                if(T_outTime > outTime){
                                    var timeDiffMinutes = Math.round((T_outTime - outTime) / (1000 * 60));
                                    var hours = Math.floor(timeDiffMinutes / 60);
                                    var minutes = timeDiffMinutes % 60;

                                    checkOutStatus = 'Early '+'('+hours + "h " + minutes + "min)";
                                    checkOutStatusColour = 'text-danger';
                                }
                            //attendance Early out time count ends
                               
                            

                            //attendance Late out time count start
                                if(v.teacher_time.out_time < v.out_time && v.out_time != ''){
                                    // Parse the input times into Date objects
                                    var outTime = new Date(`2023-05-16T${v.out_time}`);
                                    var T_outTime = new Date(`2023-05-16T${v.teacher_time.out_time}`);

                                    var timeDiffMinutes = Math.round((outTime - T_outTime) / (1000 * 60));
                                    var hours = Math.floor(timeDiffMinutes / 60);
                                    var minutes = timeDiffMinutes % 60;

                                    checkOutStatus = 'Over Time '+'('+hours + "h " + minutes + "min)";
                                    checkOutStatusColour = 'text-success';
                                }
                            //attendance Late out time count ends
                            

                            //variables
                                var intime = '';
                                var outtime = '';
                            //variables

                            // in time Am Pm formate start
                                if(v.in_time){
                                    var now = v.in_time;
                                    var date = new Date();
                                    date.setHours(now.split(":")[0]); 
                                    date.setMinutes(now.split(":")[1]); 
                                    intime = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                }
                            // in time Am Pm formate ends

                           // console.log(intime);

                            // out time Am Pm formate start
                                if(v.out_time){
                                    var now2 = v.out_time;
                                    var date2 = new Date();
                                    date2.setHours(now2.split(":")[0]); 
                                    date2.setMinutes(now2.split(":")[1]);
                                    outtime = date2.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                }
                            // out time Am Pm formate ends
                                
                            //variables
                                var startTime = v.in_time;
                                var endTime = v.out_time;
                                var totalTime = '';
                            //variables

                            // Total time counts houre minute formate start
                                if(startTime != '' && endTime != ''){
                                    var startTime = new Date(`2023-05-16T${startTime}`);
                                    var endTime = new Date(`2023-05-16T${endTime}`);
                                    var timeDiffMinutes = Math.round((endTime - startTime) / (1000 * 60));
                                    var hours = Math.floor(timeDiffMinutes / 60);
                                    var minutes = timeDiffMinutes % 60;
                                    totalTime = hours + "h " + minutes + "min";
                                }else{
                                    totalTime = '0 hours';
                                }
                            // Total time counts houre minute formate ends

                            //status start here
                                if(v.status == 'present' || v.in_time){
                                    status = 'Present';
                                }else if(v.status == 'leave'){
                                    status = 'Leave';
                                    statusColour = 'text-info';
                                }else{
                                    status = 'Absent';
                                    statusColour = 'text-danger';
                                }
                            //status Ends here
                                

                            html += `<tr>
                                        <td class='text-center'>${i+1}</td>
                                        <td class='text-center'>${v.name}</td>
                                        <td class='text-center'>${v.id_no}</td>
                                        <td class='text-center'>${v.designation}</td>
                                        <td class='text-center ${checkInStatusColour}'>${intime != '' ? intime : '-'}</td>
                                        <td class='text-center ${checkInStatusColour}'>${checkInStatus}</td>
                                        <td class='text-center ${checkOutStatusColour}'>${outtime != '' ? outtime : '-'}</td>
                                        <td class='text-center ${checkOutStatusColour}'>${checkOutStatus}</td>
                                        <td class='text-center ${statusColour}'>${totalTime}</td>
                                        <td class='text-center ${statusColour}'>${status}</td>
                                    </tr>`;
                            });
                        }
                        else{
                            html = `
                                    <tr >
                                        <td colspan="7"><p class="text-center text-danger">No Data Found!</p></td>
                                    </tr>
                                    `;
                        }

                      //  console.log(total_present);
                        $("#report_title_name").append(`<h5 class="card-description" style="color:rgba(0, 0, 0, 0.5)">Total Present : ${total_present.length}</h5>`);
                        $("#report_title_name").append(`<h5 class="card-description" style="color:rgba(0, 0, 0, 0.5)">Total Absent : ${total_teacher - total_present.length}</h5>`);
                    
                        // {
                        //     dom: 'Bfrtip',
                        //     buttons: [
                        //         {
                        //             extend: 'pdfHtml5',
                        //             title: `Teacher's Daily Reports`,
                        //             pageSize: 'A4',
                        //         },
                        //     ],
                        //  }

                        $('tbody').html(html);
                        if(data.attendances.length > 0){
                         $('#customTable').DataTable();   
                        }
                        $('#preload').html('');
                    }
                });
            });


           
            $(".daily-report").closest('li').addClass('custom_nav');
            $('.setting').closest('li').removeClass('custom_nav');
            $('.manageLeave').closest('li').removeClass('custom_nav');
            $("#report-item").removeClass('d-none');
            $('.report').closest('li').addClass('custom_nav');
            $("#leave-item").addClass('d-none');
            $("#setting-item").addClass('d-none');
            $(".teacher_attend_nav").removeClass('custom_nav');

            $("#teacherDailyReportBtn").removeClass('btn-secondary');
            $("#teacherDailyReportBtn").addClass('btn-primary');

        });
    </script>
@endpush
