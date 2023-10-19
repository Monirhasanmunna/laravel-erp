@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
    @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <div>
        <div class="card new-table">
            <div class="card">
                <div class="card-header">
                    <div class="card-title float-left">
                        <h6 style="color: #000000">Expense Create</h6>
                    </div>
                    <a href="{{ route('expense.index') }}" class="btn btn-dark float-right"> <i
                        class="fa fa-arrow-left"></i> Back</a>
                </div>
                <form action="{{ isset($expense)? route('expense.update',$expense->id) : route('expense.store') }}" onsubmit="return submitForm(this);" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 ">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label for="">Date</label>
                                        <input type="date" name="date" value="{{@$expense->date ?? date('Y-m-d')}}" class="form-control" id="" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Amount</label>
                                        <input type="number" name="amount" value="{{@$expense->amount}}"  class="form-control" id="" required>
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col-md-6">
                                        <label for="account_id">Account</label>
                                        <select class="form-control" name="account_id" id="account_id" required>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}" {{@$expense->account_id == $account->id? 'selected':''}}>{{$account->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="vendor_id">Pay To</label>
                                        <input type="text" name="pay_to" class="form-control" value="{{@$expense->pay_to}}">
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <label for="">Details</label>
                                        <textarea class="form-control"  name="details" id="" cols="8" rows="3">{{@$expense->details}}</textarea>
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <label for="">Ref No</label>
                                        <input type="text" class="form-control" value="{{@$expense->reference_no}}"  name="reference_no" id="" >
                                    </div>
                                </div>
                                @if(isset($expense))
                                    <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-arrow-circle-up"></i>Update</button>
                                @else
                                    <button type="submit" id="expense-pay" class="btn btn-primary mt-2"><i class="fa fa-credit-card"></i>Pay Now</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
            confirmButtonText: 'Yes, Pay it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
        return false;
    }
    $(document).ready(function () {
        $('#customTable').DataTable();
    });
</script>
@endpush

