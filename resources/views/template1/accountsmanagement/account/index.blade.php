@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')

        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <p style="color:black">Account List</p>
                        </div>
                        <div class="card-action">
                            <a href="{{Route('account.create')}}" class="btn btn-primary mr-2"><i class="fa fa-plus"></i> Add Account</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="customTable" class="table table-bordered table-responsive" >
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th>Name</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts as $account)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$account->name}}</td>
                                    <td>
                                        <a href="" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                        <a href="{{route('account.delete',$account->id)}}" id="delete" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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

        $(document).ready(function () {
            $('#customTable').DataTable();
            $("#accounts_setting").addClass('active');
            $('#settings-nav').removeClass('d-none');
        });

        $(".deleteBtn").click(function () {
            $(".deleteForm").submit();
        });

    </script>
@endpush

