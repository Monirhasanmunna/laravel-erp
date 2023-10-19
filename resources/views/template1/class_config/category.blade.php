@extends('admin.layouts.app')
@push('css')

@endpush
@section('content')
<div class="main-panel">
    @include($adminTemplate.'.academic.academicnav')
    @include($adminTemplate.'.class_config.class-nav')

    <div class="card new-table">
        <div class="card-header">
            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5);">Class:{{$class_name->name}} (Category List) </h4>
            <button type="button" class="btn btn-primary"
                    data-toggle="modal" data-target="#categoryModal"><i class="fa fa-plus"></i>Add
                Category
            </button>
        </div>
        <div class="card-body">
            <table id="customTable1" class="table table-bordered">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Shift</th>
                    <th>Section</th>
                    <th>Students</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td> {{ $category->name }} </td>
                        <td> {{ ($category->shift)? $category->shift->name : '' }} </td>
                        <td>{{@$category->section->name}}</td>
                        <td> {{$category->students->count()}} </td>
                        <td>
                            <a href="javascript:void(0)" onclick="categoryeditBtn({{$category->id}})"
                               class="btn btn-primary p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                            @if($category->students->count() < 1)
                                <a href="{{route('category.destroy',$category->id)}}"
                                   class="btn btn-danger p-1"><i style="margin-left: 0.3125rem;" class="fa-solid fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- category modal start --}}
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Create New Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Shift</label>
                            <select class="form-control" name="shift_id" id="shift_id" required>
                                <option value="">Select Shift</option>
                                @foreach ($shifts as $shift)
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
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Category:</label>
                            <input type="text" class="form-control" name="name" id="sessionTitle" required>
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
    {{-- category modal end--}}


    {{-- category edit modal start --}}
    <div class="modal fade" id="editcategoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Update Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('category.update',1) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Shift</label>
                            <select class="form-control" name="shift_id" id="catshift_id">
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
                            <label for="recipient-name" class="col-form-label">Category:</label>
                            <input type="text" class="form-control" name="name" id="category_name">
                            <input type="hidden" name="category_id" id="category_id">
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
    {{-- category edit modal end --}}
</div>



{{-- group edit modal end --}}
@endsection
@push('js')
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

    function categoryeditBtn(id) {
        $.ajax({
            url: '/academic/class/category/edit/' + id,
            type: 'Get',
            success: function (data) {

                $("#category_id").val(data.id);
                $("#catshift_id").append(`<option hidden  selected value='${data.shift.id}'>${data.shift.name}</option>`);
                getEditSections(data.shift.id,data.section.id);
                $("#category_name").val(data.name);
                $("#editcategoryModal").modal('show');
            }
        });
    }

    $('#catshift_id').change(function (){
       let shiftId = $(this).val();
        getEditSections(shiftId,null);
    });

    function categorydeleteBtn() {
        $("#categorydeleteForm").submit();
    }



    $('#customTable1').DataTable();

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
<script>


</script>

@endpush
