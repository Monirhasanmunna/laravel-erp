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
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Individual Report</h4>
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
                                
                                <div class="col">
                                    <label for="">Select Teacher</label>
                                    <select name="teacher_id" class="form-control" id="teacher_id">
                                        <option selected hidden disabled value="">Select Teacher</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                        @endforeach
                                    </select>
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
                    <table id="customTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class='text-center'>Date</th>
                                <th class='text-center'>Check In Time</th>
                                <th class='text-center'>Check In Status</th>
                                <th class='text-center'>Check Out Time</th>
                                <th class='text-center'>Check Out Status</th>
                                <th class='text-center'>Hours</th>
                            </tr>
                        </thead>
                        <tbody>

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
                var url = "{{ route('attendance.report.individualreport.get-IndividualReport') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function(data){

                        console.log(data);

                        if(data.attendances.length > 0){
                            let titleName = `
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Individual Report : ${data.attendances[0].source.name}</h4>
                            <p class="card-description" style="color:rgba(0, 0, 0, 0.5)">Date : ${data.form_date} to ${data.to_date}</p>
                            `;

                            $("#report_title_name").html(titleName);
                        }
                        

                        $('#table-card').removeClass('d-none');
                        let html='';
                        if(data.attendances.length > 0){
                            $.each(data.attendances,function(i,v){
                                let late = v.in_time > data.teacher_time.in_time;
                                let early = v.out_time < data.teacher_time.out_time;
                                // console.log(late);

                                let lateHtml='';
                                let earlyHtml='';
                                let absent='';

                                if(late){
                                    lateHtml = 'Late';
                                    
                                }

                                if(early){
                                    earlyHtml = 'Early';
                                }

                                if(v.status == 'absent'){
                                    absent = 'Absent';
                                }
                                


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

                                // two times
                                var startTime = intime;
                                var endTime = outtime;

                                // Convert times to Date objects
                                var startDate = new Date("01/01/2000 " + startTime);
                                var endDate = new Date("01/01/2000 " + endTime);

                                // Calculate time difference in milliseconds
                                var timeDiff = endDate.getTime() - startDate.getTime();

                                // Calculate difference in hours
                                var hoursDiff = timeDiff / (1000 * 60 * 60);
                                hoursDiff = parseInt(hoursDiff);

                                html += `<tr>
                                            <td class='text-center'>${v.date}</td>
                                            <td class='text-center ${late ? 'text-danger' : ''}'>${absent ? '' : intime}</td>
                                            <td class='text-center ${late || absent ? 'text-danger' : ''}'>${absent ? absent : lateHtml}</td>
                                            <td class='text-center ${early ? 'text-danger' : ''}'>${absent ? '' : outtime}</td>
                                            <td class='text-center ${early ? 'text-danger' : ''}'>${absent ? absent : earlyHtml}</td>
                                            <td class='text-center ${absent ? 'text-danger' : ''}'>${absent ? absent : hoursDiff + 'hours'}</td>
                                        </tr>`;
                            });
                        }
                        else{
                            html = `
                                    <tr >
                                        <td colspan="6"><p class="text-center text-danger">No Data Found!</p></td>
                                    </tr>
                                    `;
                        }

                        $('tbody').html(html);
                        if(data.attendances.length > 0){
                         $('#customTable').DataTable();   
                        }
                        
                    }
                });
            });


           
            $(".teacher_attend").closest('li').addClass('custom_nav');
            $('.setting').closest('li').removeClass('custom_nav');
            $('.manageLeave').closest('li').removeClass('custom_nav');
            $("#report-item").removeClass('d-none');
            $('.report').closest('li').addClass('custom_nav');
            $("#leave-item").addClass('d-none');
            $("#setting-item").addClass('d-none');
            $(".teacher_attend_nav").removeClass('custom_nav');

        });
    </script>
@endpush
