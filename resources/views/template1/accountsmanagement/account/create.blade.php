@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
        <div class="card new-table">
            <div class="card-header">
                <p class="text-primary">Create Account</p>
                <div class="card-action">
                    <a href="{{Route('account.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form
                            action="{{ isset($account)? route('account.update',$account->id) : route('account.store') }}"
                            method="post">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" id="" required>
                                </div>
                            </div>
                            @if(isset($account))
                                <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-arrow-circle-up"></i>Update</button>
                            @else
                                <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Submit</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $("#accounts_setting").addClass('active');
        $('#settings-nav').removeClass('d-none');
    </script>
@endpush
