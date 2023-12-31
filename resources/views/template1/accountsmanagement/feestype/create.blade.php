@extends('admin.layouts.app')

@section('content')
<div class="main-panel">
@include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <div class="card new-table">
        <div class="card-header">
            <h5 class="text-primary">Fees Type Add</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <form
                        action="{{ isset($feesType)? route('feestype.update',$feesType->id) : route('feestype.store') }}"
                        method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="type">Fees Type</label>
                                    <input type="type" class="form-control" id="type" required name="type"
                                        value="{{$feesType->type ?? @old('type')}}">
                                </div>
                            </div>
                        </div>
                        @if(isset($feesType))
                        <button type="submit" class="btn btn-primary">Update</button>
                        @else
                        <button type="submit" class="btn btn-primary">Submit</button>
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
