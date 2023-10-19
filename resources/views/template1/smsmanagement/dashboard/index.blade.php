@extends('admin.layouts.app')
@push('css')
<style>
    tr {
        height: 40px;
        padding-top: 0px;
        padding-bottom: 0px;
    }

</style>
@endpush
@section('content')

<div class="main-panel" id="marks-id" style="overflow: scroll">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')
    <div class="new-table">
        <div class="row">
            <div class="col-md-2  grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0 text-black">{{Helper::smsBalance()->currentBalance ?? 0}}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Current Balance</h6>
                    </div>
                </div>
                <div class="card ml-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0 text-black">{{Helper::smsBalance()->total_balance ?? 0}}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Sms</h6>
                    </div>
                </div>

                <div class="card ml-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0 text-black">{{Helper::smsBalance()->total_spend ?? 0}}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Spend</h6>
                    </div>
                </div>
                <div class="card ml-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0 text-black">{{Helper::smsBalance()->alertBalance ?? 0}}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Alert Balance</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
