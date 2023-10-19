@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')



        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title float-left">
                            <h4 style="color:rgba(0, 0, 0, 0.5)">Student Wise Fees</h4>
                        </div>
                        <div class="card-action float-right">
                            <a href="{{ route('fees.create') }}" class="btn btn-primary mr-2"><i class="fa fa-plus"></i> Add Fees</a>
                        </div>
                    </div>
                    <div class="card-body">



                        <table id="customTable" class="table table-bordered "
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">SL</th>
                                    <th style="width: 40px;">Student Id</th>
                                    <th style="width: 40px;">Name</th>
                                    <th style="width: 40px;">Fees Type</th>
                                    <th style="width: 40px;">Date</th>
                                    <th style="width: 40px;">Month</th>
                                    <th style="width: 40px;">Due Date</th>
                                    <th style="width: 40px;">Total Amount</th>
                                    <th class="text-center" style="width: 20px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fees as $fees)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ @$fees->student->id_no }}</td>
                                        <td>{{ @$fees->student->name }}</td>
                                        <td>{{ $fees->feestype->type }}</td>
                                        <td>{{ $fees->date }}</td>
                                        <td>{{ Helper::getMonthFromNumber($fees->month) }}</td>
                                        <td>{{ $fees->due_date }}</td>
                                        <td>{{ $fees->total_amount }}</td>
                                        <td>
                                            <a href="#" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                        </td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@push('js')
    <script>
        $('#customTable').dataTable();
        $("#accounts_setting").addClass('active');
        $('#settings-nav').removeClass('d-none');
    </script>
@endpush
