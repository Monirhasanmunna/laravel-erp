@extends('admin.layouts.app')
@push('css')

@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
        <div class="card new-table">
            <div class="card-header">
                <h5 class="text-primary">Student Payslips</h5>
            </div>
            <div class="card-body">
                <form id="student-form">
                    @include('custom-blade.student-payslip-search')
                </form>
            </div>
        </div>

        <div class="card new-table d-none " id="student-list-card">
            <div class="card-header">
                <p id="search-query"></p>
                <form id="print-all-form" action="{{route('student-payslip.download-invoice-all')}}" method="GET">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i>Print All</button>
                </form>
            </div>
            <div class="card-body" >
                <table id="student-table" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Id No</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Class</th>
                        <th>Regular Fee</th>
                        <th>Student Fee</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function searchQuery(){
                let session  = $('#session_id option:selected').text();
                let section  = $('#section_id option:selected').text();
                let category = $('#category_id option:selected').text();
                let group    = $('#group_id option:selected').text();
                let month    = $('#month option:selected').text();
                $('#search-query').html(`${session}-${section}-${category}-${group}-${month}`);
            }

            $('#student-form').submit(function (e) {

                e.preventDefault();
                let form = $(this);
                let link = "{{route('student-payslip.get-student-payments-by-month')}}";



                searchQuery();
                $.ajax({
                    url: link,
                    type: "GET",
                    data: form.serialize(),
                    success:function (data){

                        let requests = data.requests;

                        $.each(requests,function (i,val){
                            $('<input>').attr({
                                type: 'hidden',
                                name: i,
                                value:val
                            }).appendTo('#print-all-form');
                        });

                        $('#student-list-card').removeClass('d-none');
                        $("#student-list-card").removeClass('d-none');
                        let html = '';
                        $.each(data.students,function (idx,val){

                            let btnLink = "/accountsmanagement/student-payslip/download-invoice/"+ val.id + '/' + val.months;

                            html += `<tr>
                                    <td>${val.id_no}</td>
                                    <td>${val.name}</td>
                                    <td>${val.roll_no}</td>
                                    <td>${val.class}</td>
                                    <td>${val.regular_fees}</td>
                                    <td>${val.student_fees}</td>
                                    <td>${val.total}</td>
                                    <td>
                                        <a href="${btnLink}" class="btn btn-info" target="_blank"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>`;

                        });
                        $('tbody').html(html);
                        $("#student-table").DataTable();
                    }
                });

            });

            function getMonthName(monthNumber) {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('en-US', {month: 'long'});
            }




        });



    </script>

@endpush
