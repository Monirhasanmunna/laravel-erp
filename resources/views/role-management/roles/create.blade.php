@extends('admin.layouts.app')
@section('content')
    <div class="main-panel mt-3">
        <div>
            <div class="card new-table" style="margin-top: 20px;">
                <div class="card-header">
                    <div class="float-left">
                        <p>Create Role</p>
                    </div>
                    <div class="float-right">
                        <a href="{{ route('role-management.roles.index') }}" class="btn btn-dark"><i
                                class="fa fa-arrow-left"></i>Back</a>
                    </div>
                </div>
                <form
                    action="{{ isset($role) ? route('role-management.roles.update', $role->id) : route('role-management.roles.store') }}"
                    method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" value="{{ @$role->name ?? old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" id="">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="text-center my-3">
                            <h4 class="pb-2"><b>Manage Permission for Role</b></h4>
                            @error('modules')
                                <p class="p-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                </p>
                            @enderror
                        </div>

                   

                        <div class="card mb-3">
                            <div class="card-body" style="padding:0.75rem 1.5625rem!important">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="checkAll">
                                    <label class="form-check-label" for="checkAll">
                                        Select All
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach ($permissions->chunk(4) as $chunk)
                                @foreach ($chunk as $key => $permission)
                                    <div class="col-md-3">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="modules[]"
                                                        value="{{ $permission->id }}" id="defaultCheck1-{{ $key }}"
                                                        @isset($role)
                                                        @foreach ($role->modules as $rPermission)
                                                            {{ $permission->id == $rPermission->id ? 'checked' : '' }}
                                                        @endforeach
                                                    @endisset>
                                                    <label class="form-check-label" for="defaultCheck1-{{ $key }}">
                                                        <p>{{ strtoupper($permission->name) }}</p>
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $("#checkAll").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
            console.log('ok');
        });
    </script>
@endpush
