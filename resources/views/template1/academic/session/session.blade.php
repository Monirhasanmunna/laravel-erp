@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">

        @include($adminTemplate.'.academic.academicnav')
        <div>
            <div class="card new-table">
                <div class="card-header">

                    <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Sessions List</h4>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i>Add
                        Session
                    </button>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <div class="">
                        <table id="customTable" class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th> SL </th>
                                    <th> Session </th>
                                    <th> Assign Classes </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sessions as $key=>$session)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{ $session->title }} </td>
                                        <td>
                                            @foreach ($session->classes as $c)
                                                <span class="badge badge-primary">{{$c->name}}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($session->is_current == 0)
                                                <a href="{{route('session.set-current',$session->id)}}" class="btn btn-info"><i class="fa fa-check"></i>Set Current</a>
                                            @else
                                                <a href="" class="btn btn-success"><i class="fa fa-check"></i>Current</a>
                                            @endif
                                        </td>
                                        <td>

                                            <a class="btn btn-primary" href="javascript:void(0)" onclick="editBtn({{$session->id}})"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No Session Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content"  style="border: 1px solid #7c9ff5;">
                    <div class="modal-header" style="border:1px solid #dfdfdf">
                        <h5 class="modal-title text-primary" id="exampleModalLabel">Create New Session</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('session.store') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{Helper::getInstituteId()}}" name="intitute_id">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Session:</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="sessionTitle">
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="modal-footer"  style="border-top: none;">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        @if(isset($session))
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border: 1px solid #7c9ff5;">
                    <div class="modal-header" style="border:1px solid #dfdfdf">
                        <h5 class="modal-title" id="exampleModalLabel">Update Session</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('session.update',1)}}" method="POST">
                            @csrf
                            <input name="session_id" type="number" id="session_id" hidden>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Session:</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="titles" name="title" id="sessionTitle">
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div style="border-top: none;" class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#customTable').DataTable();
    });

    function deleteBtn(){
        $("#deleteForm").submit();
    }
</script>

<script>
    function editBtn(id){
        $.ajax({
            url     : '/academic/session/edit/'+id,
            type    : 'Get',
            success : function(data){
                $("#titles").val(data.title);
                $("#session_id").val(data.id);
                $("#updateModal").modal('show');
            }
        });
    }
</script>
@endpush
