@extends('admin.layouts.app')
@push('css')
    <style>
        #td-email{
            text-transform: lowercase!important;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel mt-3">
        @include('role-management.nav')
        <div class="card new-table">
            <div class="card-header">
                <div class="float-left">
                    <p>User List</p>
                </div>
                <div class="float-right">
                    <a href="{{route('role-management.users.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Create New</a>
                </div>
            </div>
            <div class="card-body p-0 px-2">
                <table id="customTable" class="table  table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Branch</th>
                        <th>Email</th>
                        <th>Action</th>
                        <th>Change Password</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{ucfirst($user->name)}}</td>
                            <td>{{ucfirst($user->role->name)}}</td>
                            <td>{{ucfirst($user->branch->name)}}</td>
                          
                            <td id="td-email">{{$user->email}}</td>
                            <td>
                                <a href="{{route('role-management.users.edit',$user->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                            </td>
                            <td>
                                <a href="javascript:void(0)" data-id="{{$user->id}}" class="btn btn-warning btn-sm reset-btn"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Password Reset</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form action="{{route('role-management.users.reset')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="user_id">
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input type="text" name="password" value="22222222" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i>Update</button>
                    </div>
                </form>
            </div>
          
        </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('#customTable').DataTable();

            $('.reset-btn').click(function(e){
                e.preventDefault();
                let id = $(this).data('id');
                $('#user_id').val(id);
                $('#resetModal').modal('show'); 
            });
        });
    </script>
@endpush
