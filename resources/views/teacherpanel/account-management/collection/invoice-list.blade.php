@extends('teacherpanel.layout.app')
@push('css')

@endpush
@section('content')
    <div class="main-panel">
        @include('teacherpanel.account-management.nav-bar')
        <div class="card new-table">
            <div class="card-header">
                <h5>Student Invoice List ({{$student->name}}-{{\App\Helper\Helper::getMonthFromNumber($month)}})</h5>
                <a href="{{route('teacherpanel.account-management.collection.view-payments',[$student->id,$month])}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
            </div>
            <div class="card-body" style="margin-bottom:-20px;">
                <table id="invoice-list" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Invoice No</th>
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
                        @foreach($studentPayments as $payment)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>#INV{{$payment->invoice_no}}</td>
                                <td>{{$payment->date}}</td>
                                <td>{{\App\Helper\Helper::getMonthFromNumber($payment->month)}}</td>
                                <td>{{$payment->invoice_total}}</td>
                                <td>{{$payment->discount}}</td>
                                <td>{{$payment->advance}}</td>
                                <td>{{$payment->due_amount}}</td>
                                <td>{{$payment->total_payable}}</td>
                                <td>{{$payment->paid_amount}}</td>
                                <td>{{$payment->status}}</td>
                                <td>
                                    <a href="{{route('teacherpanel.account-management.collection.download-invoice',$payment->id)}}" class="btn btn-success"><i class="fa fa-download"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('#invoice-list').DataTable();
    });
</script>
@endpush
