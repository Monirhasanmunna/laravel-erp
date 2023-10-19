@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
    @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')

    <div>
        <div class="card new-table">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h4 style="color:rgba(0, 0, 0, 0.5)">Teacher Assign</h4>
                    </div>
                    <div class="card-action">
                        <a href="{{Route('accounts.teacher-assign.create')}}" class="btn btn-primary mr-2"><i class="fa fa-plus"></i> Assign</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="customTable" class="table table-bordered table-responsive" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                   

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

 

</script>
@endpush

