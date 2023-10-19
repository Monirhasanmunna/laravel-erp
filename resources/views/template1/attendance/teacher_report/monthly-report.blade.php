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
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Monthly Report (Teacher)</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="text-right">
                            <a href="{{route('attendance.report.teacher_monthlyreport.index')}}" id="teacherDailyReportBtn" class="btn btn-sm btn-secondary">Teacher Monthly Report</a>
                            <a href="#" id="studentDailyReportBtn" class="btn btn-sm btn-secondary">Student Monthly Report</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="content-wrapper text-primary" >
                        <form action="" id="teacher-atten-form" method="GET">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="">Designation</label>
                                    <select class="form-control" id="desig_type" name="desig_type">
                                        <option selected hidden>Select Designation</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="staff">Staff</option>
                                        <option value="committee">Committee</option>
                                    </select>
                                </div>
    
                                <div class="col-4">
                                    <label for="">Select Month</label>
                                    <input type="month" value="" class="form-control" name="month" id="">
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
            <div class="card d-none  new-table mb-5" style="border: 1px solid #efefef;" id="table-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between" id="tabe-header-title">
                        <div id="report_title_name">
                            
                        </div>
                        {{-- <div id="total_present">

                        </div> --}}
                    </div>
                    <div id="pdf-btn" class="text-right mb-2">
                        {{-- pdf btn --}}
                     </div>
                    <table id="customTable" class="table table-bordered">
                        <thead>
                            
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
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    
    <script>
        
        $(document).ready(function() {
            $('#teacher-atten-form').submit(function(e) {
                e.preventDefault();
                var form= $(this);
                var url = "{{ route('attendance.report.teacher_monthlyreport.get-Teacher-MonthlyReport') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        console.log(data);
                        ImplementData(data);
                    }
                });
            });


            function ImplementData({monthLength,teachers,month,design_type}){
                
                let day = 1;
                let table_head = `
                        <tr>
                            <th>Name</th>
                            ${(() => {
                                let header = '';
                                for (let i = 0; i < monthLength; i++) {
                                    header += `<th class='text-center'>${day+i}</th>`;
                                }
                                return header;
                            })()}
                            <th class='text-center'>T.A</th>
                            <th class='text-center'>T.P</th>
                        </tr>
                        `;

                let table_body = '';
                for(const teacher of teachers){
                    table_body +=`
                        <tr>
                            <td>${teacher.name}</td>
                            ${(() => {
                                let body = '';
                                for (let i = 0; i < monthLength; i++) {
                                    body += `<td class='text-center'></td>`;
                                }
                                return body;
                            })()}
                            <td></td>
                            <td></td>
                        </tr>
                    `;
                }
                
                $('thead').html(table_head);
                $('tbody').html(table_body);
                $("#pdf-btn").html(` <a href="/attendance/teacher/monthly-report/pdf-report/${month}/${design_type}" id="" class="btn btn-sm btn-primary"><i class="fa fa-arrow-down"></i> Pdf Generate</a>`);
                $('#table-card').removeClass('d-none');
                        
            }

           
            $(".monthly-report").closest('li').addClass('custom_nav');
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
