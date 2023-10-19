@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.accountsmanagement.topmenu_accountsmanagement')
        <div class="card new-table">
            <div class="card-header">
                <h5 class="text-primary">Student Invoice Report</h5>
            </div>
            <div class="card-body">
                <form action="" id="payment-form" method="GET">
                    <div class="form-row">
                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" name="from_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" value="{{ date('Y-m-d') }}" name="to_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" style="margin-top: 35px" class="btn btn-primary"><i
                                    class="fa fa-arrow-circle-right"></i>Process</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="loader" class="d-flex justify-content-center m-2">

        </div>

        <div class="card new-table d-none" id="list-card">
            <div class="card-header">
                <h5 class="float-left">Invoice List</h5>
                <form action="{{route('reports.invoice-date-to-date.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="fee_received_ids[]" id="fee_rec_ids">
                    <button type="submit" id="dwn-all" class="btn btn-primary float-right d-none"><i class="fa fa-download"></i> Download</button>
                </form>
            </div>
            <div class="card-body">
                <table id="customTable" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th style="text-align:center;">
                                <div class="form-check py-0 my-0">
                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>Invoice No</th>
                            <th>Student Name</th>
                            <th>Date</th>
                            <th>Month</th>
                            <th>Invoice Total</th>
                            <th>Discount</th>
                            <th>Advance</th>
                            <th>Due</th>
                            <th>Payable</th>
                            <th>Paid</th>
                            <th>Status</th>
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
        $(document).ready(function() {

            $("#checkAll").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
                getSelectedCheckbox();
            });

            function getSelectedCheckbox() {

                let feeRecIds = [];

                $("input[name='fee_rec_id[]']").each(function(index, item) {
                    if($(item).is(":checked")){
                        let id = $(item).val();
                        feeRecIds.push(id);
                    }
                });

                $('#fee_rec_ids').val(feeRecIds);

                if(feeRecIds.length == 0){
                    $('#dwn-all').addClass('d-none');
                }
                else{
                    $('#dwn-all').removeClass('d-none');
                }
                console.log(feeRecIds.length);
            }


            $(document).on("change","input[name='fee_rec_id[]']",function(){
                getSelectedCheckbox();
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#payment-form').submit(function(e) {
                $('#loader').html('<i class="fa-solid fa-spinner fa-spin"></i>');
                e.preventDefault();
                let form = $(this);
                let link = "{{ route('reports.get-invoice-list') }}";



                $.ajax({
                    url: link,
                    type: "GET",
                    data: form.serialize(),
                    success: function(data) {
                        $('#loader').html("");
                        $("#list-card").removeClass('d-none');
                        console.log(data);
                        let html = '';

                        $.each(data, function(i, val) {

                            let route = "{{ route('payment.download-invoice', 100) }}";
                            let btnRoute = route.replace("100", val.id);

                            html += ` <tr>
                                    <td style="text-align:center;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="fee_rec_id[]" id="exampleCheck1" value="${val.id}">
                                            <label class="form-check-label" for="exampleCheck1"></label>
                                        </div>
                                    </td>
                                    <td>${val.invoice_no}</td>
                                    <td>${val.student.name}</td>
                                    <td>${val.date}</td>
                                    <td>${val.month}</td>
                                    <td>${val.invoice_total}</td>
                                    <td>${val.discount}</td>
                                    <td>${val.advance}</td>
                                    <td>${val.due_amount}</td>
                                    <td>${val.total_payable}</td>
                                    <td>${val.paid_amount}</td>
                                    <td>${val.status}</td>
                                    <td>
                                        <a href="${btnRoute}" class="btn btn-success"><i class="fa fa-download"></i></a>
                                    </td>
                                </tr>`;
                        });
                        $('tbody').html(html);
                        $('#customTable').DataTable({
                            "ordering": false
                        });
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
