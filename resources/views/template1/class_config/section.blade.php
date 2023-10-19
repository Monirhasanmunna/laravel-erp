@extends('admin.layouts.app')
@section('content')
<div class="main-panel" id="section-id">
    @include($adminTemplate.'.academic.academicnav')
    @include($adminTemplate.'.class_config.class-nav')


    <div class="card new-table">
        <div class="card-header">
            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5);">Class:{{$class_name->name}} (Section List) </h4>
            <button type="button" class="btn btn-primary"
                    data-toggle="modal" data-target="#sectionModal"><i class="fa fa-plus"></i>Add Section
            </button>
        </div>
        <div class="card-body">
            <table id="customTable2" class="table table-bordered">
                <thead>
                <tr>
                    <th> SL </th>
                    <th> Name </th>
                    <th> Shift </th>
                    <th> Students </th>
                    <th> Action </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sections as $section)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td> {{ $section->name }} </td>
                        <td> {{ ($section->shift)? $section->shift->name :'' }} </td>
                        <td> {{$section->students->count()}} </td>
                        <td>
                            <a href="javascript:void(0)" onclick="sectioneditBtn({{$section->id}})"
                               class="btn btn-primary p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                            @if($section->students->count() < 1)
                                <a href="{{route('section.destroy',$section->id)}}" class="btn btn-danger p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- section modal start --}}
<div class="modal fade" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create New Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('section.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Shift</label>
                        <select class="form-control" name="shift_id">
                            <option value="">select</option>
                            @forelse ($shifts as $shift)
                                <option value="{{$shift->id}}">{{$shift->name}}</option>
                                @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Section:</label>
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
{{-- section modal end --}}


{{-- section edit modal start --}}
<div class="modal fade" id="editsectionModal" tabindex="-1" aria-labelledby="sectionModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Update Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('section.update',1) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Shift</label>
                        <select class="form-control" name="shift_id" id="sectionshift_id">
                            <option value="">select</option>
                            @forelse ($shifts as $shift)
                                <option value="{{$shift->id}}">{{$shift->name}}</option>
                                @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Section:</label>
                        <input type="text" class="form-control" name="name" id="section_name">
                        <input type="hidden" name="section_id" id="section_id">
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
{{-- section edit modal end --}}

{{-- group edit modal end --}}
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('#customTable2').DataTable();
    });
</script>

<script>
    function sectioneditBtn(id) {
        $.ajax({
            url: '/academic/class/section/edit/' + id,
            type: 'Get',
            success: function (data) {
                console.log(data);
                $("#section_id").val(data.id);
                $("#sectionshift_id").append(
                    `<option hidden selected value='${data.shift.id}'>${data.shift.name}</option>`);
                $("#section_name").val(data.name);
                $("#editsectionModal").modal('show');
            }
        });
    }

    function sectiondeleteBtn() {
        $("#sectiondeleteForm").submit();
    }

</script>
@endpush
