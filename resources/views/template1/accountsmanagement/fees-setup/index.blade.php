@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
        <div>
            <form action="{{route('fees-setup.store')}}" method="post">
                @csrf
                <div class="card new-table">
                    <div class="card-header">
                        <div class="card-title float-left">
                            <h6 style="color: #000000">Bulk Fees Setup</h6>
                        </div>
                        <a href="{{route('fees-setup.create')}}" class="btn btn-primary float-right"><i class="fa-solid fa-circle-up"></i>Setup Fees</a>
                    </div>
                    <div class="card-body">
                        <table id="customTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Fees Type</th>
                                <th>Session</th>
                                <th>Class</th>
                                <th>Category</th>
                                <th>Group</th>
                                <th>Total</th>
                                <th>Students</th>
                                <th>Total Payable</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($fees as $fee)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$fee['fees_type']}}</td>
                                    <td>{{$fee['session']}}</td>
                                    <td>{{$fee['class']}}</td>
                                    <td>{{$fee['category']}}</td>
                                    <td>{{$fee['group']}}</td>
                                    <td>{{number_format($fee['total'],2)}}</td>
                                    <td>{{$fee['students']}}</td>
                                    <td>{{number_format($fee['total_payable'],2)}}</td>
                                    <td>
                                        <a href="{{route('fees-setup.edit',$fee['id'])}}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('#customTable').DataTable();
            $("#accounts_setting").addClass('active');
            $('#settings-nav').removeClass('d-none');


        });
    </script>
@endpush
