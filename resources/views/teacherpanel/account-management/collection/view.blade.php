@extends('teacherpanel.layout.app')
@push('css')
    <style>
        .input-group-prepend span {
            -webkit-box-shadow: 0 .125rem .25rem 0 rgba(58,59,69,.2)!important;
            box-shadow: 0 .125rem .25rem 0 rgba(58,59,69,.2)!important;
            color: #fff!important;
            background-color: #3f51b5!important;
            border-color: #3f51b5!important;
        }
        .input-group-text{
            width: 98px!important;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include('teacherpanel.account-management.nav-bar')
        <div class="card new-table">
            <div class="card-header">
                <h5 class="text-primary">Fees Collection </h5>
                <p>Month:{{\App\Helper\Helper::getMonthFromNumber($month)}} ({{$student->name}}-{{$student->id_no}})</p>
            </div>
            <div class="card-body">
                <div class="float-right">
                    <a href="{{route('teacherpanel.account-management.collection.view-invoice',[$student->id,$month])}}" class="btn btn-warning "><i class="fa fa-list"></i>Invoice List</a>
                    <a href="{{route('teacherpanel.account-management.collection.view-invoice',[$student->id,$month])}}" class="btn btn-info "><i class="fa fa-history"></i>Invoice History</a>
                </div>

                <form action="{{route('teacherpanel.account-management.collection.store')}}" method="POST" onsubmit="return submitForm(this);">
                    @csrf
                    <input type="hidden" name="month" value="{{$month}}">
                    <input type="hidden" name="student_id" value="{{$student->id}}">

                    <div class="col-md-3">
                        <label for="">Select Fees Type</label>
                        <select name="fees_type" class="form-control" id="fees_type">
                            <option value="regular_fees">Regular Fees</option>
                            <option value="student_fees">Student Fees</option>
                        </select>
                    </div>


                    <div class="card mt-3">
                            <div class="card-header">
                                <b><p >Month: <span id="month"></span> </p></b>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Fees Type</th>
                                                <th width="20%">Amount</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><small>Invoice Total</small></span>
                                            </div>
                                            <input type="text" id="invoice_total" name="invoice_total" class="form-control text-right" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><small>Discount</small></span>
                                            </div>
                                            <input  name="discount" type="number" value="0" step="any" id="discount" class="form-control text-right">
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><small>Advance</small></span>
                                            </div>
                                            <input  name="advance" type="number" value="{{$advanceAmount->amount ?? 0}}" step="any" id="advance" class="form-control text-right" readonly>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><small>Previous Due</small></span>
                                            </div>
                                            <input  name="due_amount" type="number" value="{{$dueAmount->amount ?? 0}}" step="any" id="due_amount" class="form-control text-right" readonly>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><small>Total Payable</small></span>
                                            </div>
                                            <input type="number" name="total_payable" step="0.01" id="total_payable" class="form-control text-right" readonly>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><small>Paid Amount</small></span>
                                            </div>
                                            <input type="text" name="paid_amount" class="form-control text-right" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <button type="submit" class="btn btn-sm btn-primary save-btn">
                                                    <i class="fa fa-save"></i>
                                                    Confirm Payment
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                </form>
            </div>
        </div>


    </div>
@endsection
@push('js')
    <script>

        function submitForm(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Confirm it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
            return false;
        }


        $(document).ready(function () {





            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#fees_type').change(function (){
               let feeType = $(this).val();
                getPaymentsData(feeType);
            });

            let fees_type  = $('#fees_type').val();

            getPaymentsData(fees_type);

            function getPaymentsData(fees_type){

                let student_id = "{{$student->id}}";
                let month      = "{{$month}}";
                let url        = "{{ route('teacherpanel.account-management.collection.get-student-payment-list') }}";

                $('#month').html(getMonthName(month));

                $.get(url,{
                    fees_type,
                    student_id,
                    month
                },function (data){
                    console.log(data)
                    let html = '';

                    if(data.length > 0){
                        $.each(data,function (idx,val){

                            let feesHead = '';
                            let rowLen = val.fees_head.length;
                            val.fees_head.map((item,i) => {

                                feesHead += `${item.head}:${item.amount}${rowLen === i+1?"":', '}`;
                            });

                            html += `<tr>
                                        <input type="hidden" name="fees_details_id[]" value="${val.id}" >
                                        <input type="hidden" name="amount[]" value="${val.amount}" >
                                        <input type="hidden" name="fees_type_id[]" value="${val.fees_type_id}" >
                                        <td>${val.fees_type} (${feesHead})</td>
                                        <td>
                                            <input  type="number" value="${val.amount}" class="form-control amount" readonly>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-danger delete-btn"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>`;
                        });
                    }
                    else{
                        html += `<tr>
                                    <td class="text-center text-danger" colspan="3">No Payment Found!</td>
                                </tr>`;
                    }


                    $('tbody').html(html);
                    calculateTotal();
                });
            }

            function getMonthName(monthNumber) {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('en-US', {month: 'long'});
            }


            const calculateTotal = () => {
                let invoiceTotal = 0;
                $('.amount').each(function(index,item){
                    let subtotal = $(item).val();
                    invoiceTotal += Number(subtotal);
                });
                $('#invoice_total').val(invoiceTotal.toFixed(2));

                let discount   = $('#discount').val();
                let advance    = $('#advance').val();
                let dueAmount = $('#due_amount').val();

                let totalPayable = (parseFloat(invoiceTotal) + parseFloat(dueAmount)) - (parseFloat(discount) + parseFloat(advance));
                $('#total_payable').val(totalPayable.toFixed(2));
            }

            $('#discount').on('keyup',function (){
                calculateTotal();
            });

            $(document).on('click','.delete-btn',function (e){
               e.preventDefault();
               let $this = $(this);
               $this.closest('tr').remove();
               calculateTotal();
            });



        });






    </script>

@endpush
