@extends('admin.layouts.app')
@section('content')
<div class="main-panel mt-3">
    <div>
        <div class="card new-table" style="margin-top: 20px;">
            <div class="card-header">
                <div class="float-left">
                    <p>Create/Edit User</p>
                </div>
                <div class="float-right">
                    <a href="{{route('role-management.users.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                </div>
            </div>
            <form action="{{isset($user)? route('role-management.users.update',$user->id): route('role-management.users.store')}}" method="POST">
                @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" value="{{@$user->name ?? old('name')}}" class="form-control  @error('name') is-invalid @enderror" id="">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Select Branch</label>
                            <select name="institute_branch_id" class="form-control @error('institute_branch_id') is-invalid @enderror"  id="">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{$branch->id}}" {{@$user->institute_branch_id == $branch->id? "selected":""}}>{{$branch->name}}</option>
                                @endforeach
                            </select>
                            @error('institute_branch_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @if(!@$user)
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" value="{{ old('password')}}" class="form-control @error('password') is-invalid @enderror" id="">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Select Role</label>
                            <select name="role_id" class="form-control  @error('role_id') is-invalid @enderror" id="">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" {{@$user->role_id == $role->id? "selected":""}}>{{$role->name}}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" name="email" value="{{@$user->email ?? old('email')}}" class="form-control  @error('email') is-invalid @enderror" id="">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        @if(!@$user)
                            <div class="form-group">
                                <label for="">Confirm Password</label>
                                <input type="password" name="password_confirmation" value="{{ old('password_confirmation')}}" class="form-control @error('password_confirmation') is-invalid @enderror" id="">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                    @if(@$user)
                    Update
                    @else
                    Submit
                    @endif
                </button>

                @if(@$user)
                @if (Auth::user()->id != $user->id && Auth::user()->role_name == "Admin")
                    <a href="{{route('role-management.users.delete',$user->id)}}" id="delete" class="btn btn-danger float-right"><i class="fa fa-trash"></i></a>
                @endif
                @endif
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
