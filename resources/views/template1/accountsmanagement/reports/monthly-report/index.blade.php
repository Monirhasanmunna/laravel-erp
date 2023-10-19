@extends('admin.layouts.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-results__option{
            background: white;
        }
    </style>
@endpush
@section('content')
<div class="main-panel">
    @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <div class="card new-table">
        <div class="card-header">
            <h5 class="text-primary">Monthly Paid Report</h5>
        </div>
        <div class="card-body">
            <form method="GET" id="search-form">
                @include('custom-blade.search-with-month-multiple')
            </form>
        </div>
    </div>

    <div id="loader" class="d-flex justify-content-center m-2">

    </div>

    <div class="card new-table d-none" id="list-card">
        <div class="card-header">
            <p class="float-left">Payment List</p>
            <a href="" id="print" class="btn btn-primary"><i class="fa fa-print"></i>Print</a>
        </div>
        <div class="card-body" id="table-div">
            
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        $('.select2-multiple').select2();
       

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#search-form').submit(function(e) {
            $('#loader').html('<i class="fa-solid fa-spinner fa-spin"></i>');
            e.preventDefault();
            let form = $(this);
            let link = "{{route('reports.get-monthly-report')}}";
           
            //print btn route
            let printRoute = "{{route('reports.monthly-report-print',100)}}";
            printRoute = printRoute.replace('100',form.serialize());    
            $('#print').attr("href", printRoute);

            $.ajax({
                url: link,
                type: "GET",
                data: form.serialize(),
                success: function(data) {
                    $('#loader').html("");
                    $("#list-card").removeClass('d-none');
                    tBodyHtml = '';

                    $.each(data.students,function(idx,val){

                        let monthHtml = "";
                        $.each(val.months,function(index,value){
                            monthHtml += `<td class="text-right">${value}</td>  `;
                        });

                        tBodyHtml += `<tr>
                                        <td class="text-center">${val.id_no}</td>
                                        <td class="text-center">${val.name}</td>
                                        <td class="text-center">${val.roll_no}</td>
                                        <td class="text-right">${val.payable}</td>
                                        ${monthHtml}
                                        <td class="text-right">${val.paid}</td>  
                                        <td class="text-right">${val.due}</td>  
                                        <td class="text-right">${val.advance}</td>  
                                    </tr>`;
                    });


        
                 
                    //monthly calcs
                    let tFootTd = "";
                    $.each(data.monthAmoountArray,function(idx,val){
                        tFootTd +=   `<td class="text-right">${val}</td>`;
                    });

                    let tFootHtml = `<tr>
                                        <td class="text-right" colspan="3">Total</td>
                                        <td class="text-right">${data.totalPayable}</td>
                                        ${tFootTd}
                                        <td class="text-right">${data.totalPaid}</td>
                                        <td class="text-right">${data.totalDue}</td>
                                        <td class="text-right">${data.totalAdvance}</td>
                                    </tr>`;
              
                    let monthHtml = "";
                    $.each(data.months,function(idx,val){
                        monthHtml += `<th class="text-right">${val.name}</th>`;
                    });
                    

                    let table = `<table id="customTable" class="table table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Id No</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Roll No</th>
                                            <th class="text-right">Payable</th>
                                            ${monthHtml}
                                            <th class="text-right">Paid</th>
                                            <th class="text-right">Due</th>
                                            <th class="text-right">Adv.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tBodyHtml}
                                    </tbody>
                                    <tfoot>
                                        ${tFootHtml}
                                    </tfoot>
                                </table>`;
                    $("#table-div").html(table);            
                    $('#customTable').DataTable();
                }
            });
        });

        function getMonthName(monthNumber) {
            const date = new Date();
            date.setMonth(monthNumber - 1);
            return date.toLocaleString('en-US', {
                month: 'long'
            });
        }

        $("#reports-nav").removeClass('d-none');
        $("#reports").addClass('active');
    });
</script>
@endpush