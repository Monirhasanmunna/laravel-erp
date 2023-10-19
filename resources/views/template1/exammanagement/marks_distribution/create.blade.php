@extends('admin.layouts.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-results__option{
            background: white;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate . '.exammanagement.topmenu_exammanagement')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <p>Setup Mark Distribution</p>
                    <a href="{{ route('exam-management.setting.marks-dist.index') }}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                </div>
                <form action="{{ route('exam-management.setting.marks-dist.store') }}" method="post">
                    @csrf
                <div class="card-body">
                    <div class="mb-2">
                        @include('custom-blade.search-student4')
                    </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Marks Type</th>
                                        <th>Total Mark</th>
                                        <th>Pass Mark</th>
                                        <th>Take</th>
                                        <th>Grace</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>
                                            <select name="subject_id[]" id="subject_id"
                                                    class="form-control col-md-12 subject_id-1">
                                                <option value="">Select Subject</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="total_mark[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <input type="number" name="pass_mark[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <input type="number" value="100" name="take[]" class="form-control"
                                                   required>
                                        </td>
                                        <td>
                                            <input type="number" name="grace[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-info add-row"><i
                                                    class=" fa fa-plus"></i> </a>
                                        </td>
                                    </tr>
                                    <tr data-child="1">
                                        <td>
                                            <select id="sub_marks_dist_type_id"
                                                    class="form-control sub_marks_dist_type_id" required>
                                                <option value="">Select Type</option>
                                                @foreach ($types as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" placeholder="Mark" class="form-control mark"
                                                   required>
                                        </td>
                                        <td>
                                            <input type="number" placeholder="Passmark"
                                                   class="form-control pass-mark" required>
                                        </td>
                                        <td colspan="3">
                                            <a href="" class="btn btn-dark marks-plus"><i
                                                    class=" fa fa-plus"></i> </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mt-3"><i
                                            class="fa fa-save"></i>Submit</button>
                                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2();

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;
            //change class id



            //get class
            $('#year_id').change(function() {
                let session_id = $(this).val();
                $.get("{{ route('student.get-classes') }}", {
                        session_id
                    },
                    function(data) {
                        console.log(data);
                        let html = '<option>Select Class</option>';
                        let examHtml = '<option>Select Exam</option>';
                        data.classes.map(function(item) {
                            html += `<option value="${item.id}">${item.name}</option>`;
                        });
                        data.exams.map(function(item) {
                            examHtml += `<option value="${item.id}">${item.name}</option>`;
                        });
                        $('.ins_class_id').html(html);
                        $('.exam_id').html(examHtml);
                    });
            });
            //student form submit


            $('#customTable').DataTable();
            $("#setting").addClass('active');
            $("#marks-dist").addClass('active');
            $("#setting_menu").show();
            localStorage.removeItem("index");



            //deselect select option
            function deSelectOption(dataChild){
                //for option deselect
                let childTr = $("tbody").find(`[data-child='${dataChild}']`);

                let  selectedElems = [];
                $.each(childTr,function (i,el){
                    let selectElem = $(this).find('.sub_marks_dist_type_id');

                    let optSelectedVal = selectElem.children("option:selected").val();
                    if(optSelectedVal !== ''){
                        selectedElems.push(optSelectedVal);
                    }
                });

                $.each(childTr,function (i,el){
                    let selectElem = $(this).find('.sub_marks_dist_type_id');

                    selectElem.children().each(function (i,elem){
                        if(selectedElems.includes($(this).val())){
                            $(this).attr('disabled',true);
                        }
                        else{
                            $(this).attr('disabled',false);
                        }
                    });

                    $(this).find('.sub_marks_dist_type_id').find(":selected").attr('disabled',false);
                });

            }



            $(document).on('change','.sub_marks_dist_type_id',function (){
                let dataChild = $(this).parents('tr').data('child');
                deSelectOption(dataChild);

            });


            //add child tr
            $(document).on('click', '.marks-plus', function(e) {
                e.preventDefault();

                let dataChild = $(this).parents('tr').data('child');
                let subject_id = $(this).closest('tr').prev().find("[name='subject_id[]']").val();

                //for option deselect
                let childTr = $("tbody").find(`[data-child='${dataChild}']`);

                let html = `<tr data-child="${dataChild}">
                                <td>
                                    <select name="dist_type_id_${subject_id}[]" id="sub_marks_dist_type_id" class="form-control sub_marks_dist_type_id" required>
                                        <option value="">Select Type</option>
                                        @foreach ($types as $item)
                                             <option value="{{ $item->id }}" >{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="mark-${subject_id}[]" placeholder="Mark" class="form-control mark" required>
                                </td>
                                <td>
                                    <input type="text" name="pass_mark-${subject_id}[]"  placeholder="Passmark" class="form-control pass-mark" required>
                                </td>
                                <td colspan="3">
                                    <a href=""  class="btn btn-danger marks-minus"><i class=" fa fa-minus"></i> </a>
                                </td>
                            </tr>`;

                childTr.last().after(html);
                deSelectOption(dataChild);
            });




            //type on change
            /*
            $(document).on('change','.sub_marks_dist_type_id',function(){
                let type_id = $(this).val();
                $(this).closest('tr').find('.mark').attr('name', `mark-${type_id}[]`);
                $(this).closest('tr').find('.pass-mark').attr('name', `pass-mark-${type_id}[]`);
            });*/




            //new row add
            let index = 1;

            $(document).on('click', '.add-row', function(e) {
                e.preventDefault();

                index++;
                let html = `     <tr>
                                    <td>
                                        <select name="subject_id[]" id="subject_id" class="form-control col-md-12 subject_id-${index}">
                                            <option value="">Select Subject</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="total_mark[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="pass_mark[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" value="100" name="take[]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="grace[]" class="form-control" required>
                                    </td>
                                    <td>
                                    <a href="" class="btn btn-danger delete-row"><i class=" fa fa-trash"></i> </a>
                                    </td>
                                </tr>
                                <tr data-child="${index}">
                                    <td>
                                        <select id="sub_marks_dist_type_id" class="form-control sub_marks_dist_type_id">
                                            <option value="">Select</option>
                                            @foreach ($types as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" placeholder="mark" class="form-control mark" required>
                                    </td>
                                    <td>
                                        <input type="text"  placeholder="passmark" class="form-control pass-mark" required>
                                    </td>
                                    <td colspan="3">
                                        <a href=""  class="btn btn-dark marks-plus"><i class=" fa fa-plus"></i> </a>
                                    </td>
                                </tr>`;
                $('tbody').append(html);

                //set index
                localStorage.setItem("index", index);

                let class_id = $('.class_id').val();
                let exam_id = $('.exam_id').val();


                $.get("{{ route('get-subjects-by-class') }}", {
                        class_id,
                        exam_id
                    },
                    function(data) {
                        let html = '<option value="">Select Subject</option>';
                        data.map(function(item) {
                            html +=
                                `<option value="${item.id}">${item.subject.sub_name}</option>`;
                        });
                        $(`.subject_id-${index}`).html(html);
                    });

                $(document).on('change', `.subject_id-${index}`, function() {
                    let subject_id = $(this).val();
                    $(this).closest('tr').next().find('.sub_marks_dist_type_id').attr('name',
                        `dist_type_id_${subject_id}[]`);

                    $(this).closest('tr').next().find('.mark').attr('name', `mark-${subject_id}[]`);
                    $(this).closest('tr').next().find('.pass-mark').attr('name',
                        `pass_mark-${subject_id}[]`);
                });


            });


            $(document).on('change', '.subject_id-1', function() {
                let subject_id = $(this).val();
                $(this).closest('tr').next().find('.sub_marks_dist_type_id').attr('name',
                    `dist_type_id_${subject_id}[]`);

                $(this).closest('tr').next().find('.mark').attr('name', `mark-${subject_id}[]`);
                $(this).closest('tr').next().find('.pass-mark').attr('name', `pass_mark-${subject_id}[]`);
            });



            //get subjects by class

            $('.exam_id').change(function() {

                let class_id = $('.class_id').val();
                let exam_id = $(this).val();

                $.get("{{ route('get-subjects-by-class') }}", {
                        class_id,
                        exam_id
                    },
                    function(data) {
                        console.log(data);
                        if (data.length === 0) {
                            toastr.error("Subject Not Found");
                        }

                        let html = '<option value="">Select Subject</option>';
                        data.map(function(item) {
                            html +=
                                `<option value="${item.id}">${item.subject.sub_name}</option>`;
                        });
                        $('.subject_id-1').html(html);

                    });
            });



            $(document).on('click', '.delete-row', function(e) {
                e.preventDefault();
                $(this).closest('tr').next().remove();
                $(this).closest('tr').remove();
            });
            $(document).on('click', '.marks-minus', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });


        });
    </script>
@endpush
