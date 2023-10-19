@extends('admin.layouts.app')

@section('content')
<div class="main-panel" id="group-id">
    @include($adminTemplate.'.academic.academicnav')
    @include($adminTemplate.'.class_config.class-nav')


    <div class="card new-table">
        <div class="card-header">
            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5);">Class:{{$class_name->name}} (Group List) </h4>
            <button type="button" class="btn btn-primary"
                    data-toggle="modal" data-target="#groupModal"><i class="fa fa-plus"></i>Add Group
            </button>
        </div>
        <div class="card-body p-0 px-2">
            <table id="customTable" class="table  table-bordered">
                <thead>
                <tr>
                    <th>SL </th>
                    <th>Name </th>
                    <th>Shift</th>
                    <th>Section</th>
                    <th>Students</th>
                    <th> Action </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td> {{ $group->name }} </td>
                        <td> {{ $group->shift->name }}</td>
                        <td> {{ @$group->section->name }}</td>
                        <td> {{ $group->students->count() }}</td>
                        <td>
                            <a href="javascript:void(0)" onclick="groupeditBtn({{$group->id}})"class="btn btn-primary p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                            @if($group->students->count() < 1)
                                <a href="{{route('group.destroy',$group->id)}}" class="btn btn-danger p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- group modal start --}}
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create New Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('group.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Shift</label>
                        <select class="form-control" name="shift_id" id="shift_id" required>
                            <option value="">Select Shift</option>
                            @forelse ($shifts as $shift)
                                <option value="{{$shift->id}}">{{$shift->name}}</option>
                                @endforeach
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Section</label>
                        <select class="form-control section_id" name="section_id" id="section_id" required>
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <input type="hidden" name="ins_class_id" value="{{$id}}">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" name="name" class="form-control" required>
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
{{-- group modal end --}}


{{-- group edit modal start --}}
<div class="modal fade" id="editgroupModal" tabindex="-1" aria-labelledby="groupModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Update Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('group.update',1) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Shift</label>
                        <select class="form-control" name="shift_id" id="groupshift_id">
                            <option value="">select</option>
                            @forelse ($shifts as $shift)
                                <option value="{{$shift->id}}">{{$shift->name}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <select class="form-control edit_section_id" name="section_id" id="edit_section_id" required>
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" name="name" id="groupName" class="form-control">
                        <input type="hidden" name="group_id" id="group_id">
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

{{-- group edit modal end --}}
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('#customTable').DataTable();
    });
</script>

<script>

    function getEditSections(shiftId,secId=null){

        const url = "{{route('category.get-section')}}";

        $.get(url,{shiftId},function (data){

            let html = `<option value="">Select Section</option>`;

            if(secId != null){
                $.each(data,function (i,val){
                    html += `<option value="${val.id}" ${val.id === secId? 'selected':''} >${val.name}</option>`;
                });
            }
            else{
                $.each(data,function (i,val){
                    html += `<option value="${val.id}">${val.name}</option>`;
                });
            }

            $('.edit_section_id').html(html);
        });
    }



    function groupeditBtn(id) {
        $.ajax({
            url: '/academic/class/group/edit/' + id,
            type: 'Get',
            success: function (data) {
                console.log(data);
                $("#group_id").val(data.id);
                $("#groupshift_id").append(`<option hidden selected value='${data.shift.id}'>${data.shift.name}</option>`);
                getEditSections(data.shift.id,data.section.id);
                $("#groupName").val(data.name);
                $("#editgroupModal").modal('show');
            }
        });
    }


    $('#groupshift_id').change(function (){
        let shiftId = $(this).val();
        getEditSections(shiftId,null);
    });

    function groupdeleteBtn() {
        $("#groupdeleteForm").submit();
    }

    $('#shift_id').change(function (){
        let shiftId = $(this).val();
        const url = "{{route('category.get-section')}}";

        $.get(url,{shiftId},function (data){

            let html = `<option value="">Select Section</option>`;
            $.each(data,function (i,val){
                html += `<option value="${val.id}">${val.name}</option>`;
            });

            $('.section_id').html(html);
        });
    });
</script>
@endpush
