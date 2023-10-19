@extends('admin.layouts.app')
@push('css')
    <style>
        tr { height: 40px;padding-top: 0px;padding-bottom: 0px; }
    </style>
@endpush
@section('content')
<div class="main-panel" id="shift-id">

    @include($adminTemplate.'.academic.academicnav')
    @include($adminTemplate.'.class_config.class-nav')

    <div class="card new-table">
        <div class="card-header">
            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5);">Class:{{$class_name->name}} (Shift List) </h4>
            <button type="button" class="btn btn-primary mr-2"
                    data-toggle="modal" data-target="#shiftModal"><i class="fa fa-plus"></i> Add
                Shifts
            </button>
        </div>
        <div class="card-body p-0 px-2">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-shift" role="tabpanel"
                    aria-labelledby="pills-shift-tab">

                    <div class="">
                        <table id="customTable3" class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th> Name </th>
                                    <th> Students </th>
                                    <th width='9%' class="text-center"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shifts as $shift)
                                <tr>
                                    <td> {{ $shift->name }} </td>
                                    <td> {{$shift->students->count()}} </td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="shifteditBtn({{$shift->id}})"
                                            class="btn btn-primary p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                                        @if($shift->students->count() < 1)
                                            <a href="{{route('shift.destroy',$shift->id)}}"
                                               class="btn btn-danger p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- shift end --}}



                {{-- shift modal start --}}
                <div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="shiftModalLabel">Create New Shift</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('shift.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Sift:</label>
                                        <input type="text" class="form-control" name="name" id="sessionTitle">
                                        <input type="hidden" name="ins_class_id" value="{{$id}}">
                                    </div>
                                    <div class="modal-footer" style="border-top: none;">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- shift modal end --}}

                {{-- shift edit modal start --}}
                <div class="modal fade" id="editshiftModal" tabindex="-1" aria-labelledby="shifteditModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="shifteditModalLabel">Update Shift</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('shift.update',1) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Sift:</label>
                                        <input type="text" class="form-control" name="name" id="shiftName">
                                        <input type="hidden" name="shift_id" id="shift_id">
                                    </div>
                                    <div class="modal-footer" style="border-top: none;">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    </div>
                {{-- shift edit modal end --}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('#customTable3').DataTable();
    });

</script>


<script>
    function shifteditBtn(id){
        $.ajax({
            url: '/academic/shift/edit/'+id,
            type: 'Get',
            success: function (data) {
                console.log(data);
                $("#shift_id").val(data.id);
                $("#shiftName").val(data.name);
                 $("#editshiftModal").modal('show');
               // new bootstrap.Modal('#editshiftModal');
            }
        });
    }

    function shiftdeleteBtn() {
        $("#shiftdeleteForm").submit();
    }

</script>
@endpush
