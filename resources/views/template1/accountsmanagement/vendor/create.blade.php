@extends('admin.layouts.app')account

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
        <div class="card new-table">
            <div class="card-header">
                <p class="text-primary">Create Vendor</p>
                <div class="card-action">
                    <a href="{{Route('vendor.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form
                            action="{{ isset($vendor)? route('vendor.update',$vendor->id) : route('vendor.store') }}"
                            method="post">
                            @csrf
                            <div class="form-row">
                                <div class="col">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" id="" required>
                                </div>
                                <div class="col">
                                    <label for="">Phone</label>
                                    <input type="text" name="phone" class="form-control" id="" required>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col">
                                    <label for="">Email</label>
                                    <input type="text" name="email" class="form-control" id="">
                                </div>
                                <div class="col">
                                    <label for="">Address</label>
                                    <input type="text" name="address" class="form-control" id="">
                                </div>
                            </div>
                            @if(isset($vendor))
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
