@extends('admin.layouts.app')

@section('content')
<div class="main-panel">
    @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <div class="card new-table">
        <div class="card-header">
            <h5 class="text-primary">Student Paid Report</h5>
        </div>
        <div class="card-body">
            <form action="" id="payment-form" method="GET">
                <div class="form-row">
                    <div class="col-md-3">
                        <label>From Date</label>
                        <input type="date" value="{{date('Y-m-d')}}" name="from_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>To Date</label>
                        <input type="date" value="{{date('Y-m-d')}}" name="to_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" style="margin-top: 35px" class="btn btn-primary"><i class="fa fa-arrow-circle-right"></i>Process</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="loader" class="d-flex justify-content-center m-2">

    </div>

    <div class="card new-table d-none" id="list-card">
        <div class="card-header">
            <div class="catd-title">

            </div>
            <form action="{{route('payment-report.paid-report-print')}}" method="POST">
                @csrf
                <input type="hidden"  name="from_date" id="from_date" class="form-control">
                <input type="hidden"  name="to_date" id="to_date" class="form-control">

                <button type="submit" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i>
                Print</button>
            </form>
        </div>
        <div class="card-body">
            <table id="customTable" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Id No</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Class</th>
                        <th>Details</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right" colspan="6">Total</td>
                        <td class="text-right" id="total-amount"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {

       

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#payment-form').submit(function(e) {
            $('#loader').html('<i class="fa-solid fa-spinner fa-spin"></i>');
            e.preventDefault();
            let form = $(this);
            let link = "{{route('payment-report.paid-report')}}";

            let from_date = $('input[name="from_date"]').val(); 
            let to_date = $('input[name="to_date"]').val(); 
            $('#from_date').val(from_date);
            $('#to_date').val(to_date);
            $('.catd-title').html(`<b>${from_date} - ${to_date}</b>`);

            $.ajax({
                url: link,
                type: "GET",
                data: form.serialize(),
                success: function(data) {
                    $('#loader').html("");
                    $("#list-card").removeClass('d-none');

                    let html = '';
                    let totalAmount = 0;

                    $.each(data, function(i, value) {
                        let imgSrc = "";
                        totalAmount += value.total_amount;
                        if (value.image == null) {
                            imgSrc = "{{asset('male.png')}}";
                        } else {
                            imgSrc = "{{Config::get('app.s3_url')}}" + value.image;
                        }

                        html += `<tr>
                                        <td>
                                            <img src="${imgSrc}" width="120px" alt="">
                                        </td>
                                        <td>${value.id_no}</td>
                                        <td>${value.name}</td>
                                        <td>${value.roll_no}</td>
                                        <td>${value.class}</td>
                                        <td>${value.details}</td>
                                        <td class="text-right">${value.total_amount.toFixed(2)}</td>
                                    </tr>`;
                    });
                    $('tbody').html(html);
                    $('#total-amount').html(totalAmount.toFixed(2));
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