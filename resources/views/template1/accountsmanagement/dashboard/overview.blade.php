@extends('admin.layouts.app')
@push('css')
    <style>

        .order-card {
            color: #fff;
        }

        .bg-c-blue {
            background: linear-gradient(45deg,#4099ff,#73b4ff);
        }

        .bg-c-green {
            background: linear-gradient(45deg,#2ed8b6,#59e0c5);
        }

        .bg-c-yellow {
            background: linear-gradient(45deg,#FFB64D,#ffcb80);
        }

        .bg-c-pink {
            background: linear-gradient(45deg,#FF5370,#ff869a);
        }


        .card {
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
            box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
            border: none;
            margin-bottom: 30px;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .card .card-block {
            padding: 25px;
        }

        .order-card i {
            font-size: 26px;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
        <div>
            <div class="card new-table">
                    <div class="card-header">
                        <div class="card-title float-left">
                            <h6 style="color: #000000">Accounts Overview</h6>
                        </div>
                        <a href="{{route('accountsmanagement.dashboard')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                    <div class="card-body">
                        <form action="" id="search-form" method="GET">
                            @include('custom-blade.search-with-month')
                        </form>
                        <div class="row mt-4 d-none" id="accounts-card">
                            <div class="col-md-2">
                                <div class="card bg-c-blue order-card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="d-flex align-items-center align-self-start">
                                                    <h3 class="mb-0 text-white" id="total_payable"></h3>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="icon icon-box-light ">
                                                    <i class="fa fa-credit-card f-left"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="text-white font-weight-normal">Total Payable
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card bg-c-pink order-card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="d-flex align-items-center align-self-start">
                                                    <h3 class="mb-0 text-white" id="total_dues"></h3>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="icon icon-box-light ">
                                                    <i class="fa fa-credit-card f-left"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="text-white font-weight-normal">Total Dues
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card bg-c-yellow order-card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="d-flex align-items-center align-self-start">
                                                    <h3 class="mb-0 text-white" id="total_scholarship"></h3>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="icon icon-box-light ">
                                                    <i class="fa fa-credit-card f-left"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="text-white font-weight-normal">Total Scholarship</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card bg-c-green order-card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="d-flex align-items-center align-self-start">
                                                    <h3 class="mb-0 text-white" id="total_online_collection"></h3>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="icon icon-box-light ">
                                                    <i class="fa fa-credit-card f-left"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="text-white font-weight-normal">Total Online Collection</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card bg-c-green order-card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="d-flex align-items-center align-self-start">
                                                    <h3 class="mb-0 text-white" id="total_offline_collection"></h3>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="icon icon-box-light ">
                                                    <i class="fa fa-credit-card f-left"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="text-white font-weight-normal">Total Offline Collection</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card bg-c-green order-card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="d-flex align-items-center align-self-start">
                                                    <h3 class="mb-0 text-white" id="total_collection"></h3>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="icon icon-box-light ">
                                                    <i class="fa fa-credit-card f-left"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="text-white font-weight-normal">Total  Collection</h6>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <table class="table table-bordered d-none" id="fees-type-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Fees Type</th>
                                    <th>Payable</th>
                                    <th>Collection</th>
                                    <th>Dues</th>
                                    <th>Scholarship</th>
                                    <th>Discount</th>
                                    <th>Fine</th>
                                    <th>Student</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
$(document).ready(function() {

    $('h3').css('font-size','16px');

    $('#search-form').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let link = "{{route('get-account-overview-data')}}";
        $.ajax({
            url: link,
            type: "GET",
            data: form.serialize(),
            success:function (data){
                $('#fees-type-table').removeClass('d-none');
                $('#accounts-card').removeClass('d-none');
                $('#total_payable').html(`${data.totalPayable} (tk)`);
                $('#total_dues').html(`${data.totalDue} (tk)`);
                $('#total_scholarship').html(`${data.totalScholarship} (tk)`);
                $('#total_online_collection').html(`${data.totalOnlineCollection} (tk)`);
                $('#total_offline_collection').html(`${data.totalOfflineCollection} (tk)`);
                $('#total_collection').html(`${data.totalCollection} (tk)`);

                let html = '';
                $.each(data.feesTypesData,function (idx,val){
                    html += `<tr>
                                <td>${idx+1}</td>
                                <td>${val.name}</td>
                                <td>${val.total_payable}</td>
                                <td>${val.total_collection}</td>
                                <td>${val.total_dues}</td>
                                <td>${val.scholarship}</td>
                                <td>${val.discount}</td>
                                <td>${val.fine}</td>
                                <td>${val.student_count}</td>
                             </tr>`;
                });
                $('tbody').html(html);
            }
        });
    });

});
</script>
@endpush
