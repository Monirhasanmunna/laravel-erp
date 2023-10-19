@extends('teacherpanel.layout.app')

@section('content')
    <div class="main-panel mt-3" id="marks-id">
        @include('teacherpanel.exam-management.nav-bar')
        <style type="text/css">
            .sub-table th,
            .sub-table td {
                padding: 3px !important;
                font-size: 16px;
            }
        </style>
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)"> Input Marks Create</h4>
                    </div>

                    <div class="card-body">

                        <form id="student-form" action="" method="post">

                            @include('custom-blade.search-student3')
                            <div class="preload1"></div>

                            <div class="card d-none" id="subject-list">
                                <div class="card-header" id="search-query">

                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <p style="padding:10px 10px 10px 0">Pending Subjects</p>
                                            <div id="pending-subjects">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <p style="padding:10px 10px 10px 0">Complete Subjects</p>
                                            <div id="done-subjects">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button id="process-btn" type="submit" class="btn btn-primary mt-3 d-none"><i
                                    class="fa fa-arrow-down"></i>Process</button>
                        </form>


                        <a id="up-excel" href="{{ route('teacherpanel.exam-management.marks-input.upload-excel') }}" class="btn btn-dark float-right mb-2 ml-2 d-none" ><i class="fa fa-file-excel"></i>Upload Excel</a>

                        <form action="{{ route('marks-input-download-excel') }}" method="POST">
                            @csrf
                            <input type="hidden" name="class_id" id="class_ids">
                            <input type="hidden" name="section_id" id="section_ids">
                            <input type="hidden" name="category_id" id="category_ids">
                            <input type="hidden" name="group_id" id="group_ids">
                            <input type="hidden" name="exam_id" id="exam_ids">
                            <input type="hidden" name="class_subject_id" id="subject_ids">
                            <button id="dwn-excel" class="btn btn-success float-right mb-2 d-none" type="submit"><i
                                    class="fa fa-file-excel"></i>Download Excel</button>
                        </form>

                        <form action="{{ route('marks-input-store') }}" method="POST">
                            @csrf
                            <div class="student-list mt-3"></div>
                            <button type="submit" class="btn btn-primary mt-3 d-none" id="save-btn"><i
                                    class="fa fa-save"></i>Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;

            $('#search-btn').click(function(e) {
                e.preventDefault();
                $('.preload1').html(loader);
                let session_id = $('#session_id').val();
                let class_id = $('.section_id').find(':selected').data('class');
                let section_id = $('.section_id').val();
                let category_id = $('.category_id').val();
                let group_id = $('.group_id').val();
                let exam_id = $('.exam_id').val();

                if (!session_id) {
                    toastr.error("Please Select Session");
                }
                if (!section_id) {
                    toastr.error("Please Select Section");
                }
                if (!category_id) {
                    toastr.error("Please Select Category");
                }
                if (!group_id) {
                    toastr.error("Please Select Group");
                }
                if (!exam_id) {
                    toastr.error("Please Select Exam");
                }

                //show search query
                if (session_id && class_id && category_id && group_id && exam_id) {
                    let session   = $("#session_id option:selected").text();
                    let className = $(".section_id option:selected").text();
                    let category  = $(".category_id option:selected").text();
                    let group     = $(".group_id option:selected").text();
                    let exam      = $(".exam_id option:selected").text();

                    $.get("{{route('marks-input-student-count')}}",
                        {class_id,section_id,category_id,group_id},
                        function (data){
                            $('.preload1').html('');
                            $('#search-query').html(`${session}-${className}-${category}-${group}-${exam} <p class="text-primary">(Students:${data})</p>`);
                            getSubjects(class_id,section_id,category_id,group_id,exam_id);
                        });
                }

            });


            //student form submit
            $('#student-form').submit(function(e) {
                e.preventDefault();
                var form        = $(this);
                var url         = "{{ route('marks-input-get-students') }}";

                let class_id    = $('.section_id').find(':selected').data('class');
                let section_id  = $('.section_id').val();
                var subject_id  = $("input[name='class_subject_id']:checked").val();
                let category_id = $('.category_id').val();
                let group_id    = $('.group_id').val();
                let exam_id     = $('.exam_id').val();

                $('#class_ids').val(class_id);
                $('#section_ids').val(section_id);
                $('#category_ids').val(category_id);
                $('#group_ids').val(group_id);
                $('#exam_ids').val(exam_id);
                $('#subject_ids').val(subject_id);




                if(!subject_id){
                    toastr.error("Please Select Subject");
                }
                else{
                    $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        $('#save-btn').removeClass('d-none');

                        let thList = '';
                        $.each(data.mark_dists, function(i, v) {
                            thList +=
                                `<th>${v.sub_mark_dist_type.title} (${v.mark}-${v.pass_mark})</th>`;
                        });

                        //students loop
                        let tRow = '';
                        $.each(data.students, function(idx, val) {
                            let tbodyRow = '';

                            if (data.mark_dists != null) {
                                $('#dwn-excel').removeClass('d-none');
                                $('#up-excel').removeClass('d-none');
                                $.each(val.mark_dists, function(index, value) {

                                    tbodyRow += `<td>
                                                    <input type="hidden" name="mark_dist_details_id-${val.id}[]" value="${value.id}" >
                                                    <input type="number" step="0.01"  name="mark_dist_detail-${val.id}[]"  class="form-control mark_dist_details_id-${value.id}" >
                                                </td>`;
                                });
                            } else {
                                $('#dwn-excel').addClass('d-none');
                                $('#up-excel').addClass('d-none');
                                tbodyRow += `<td>
                                                <h4 class="text-danger">Mark Dist Not Found</h4>
                                            </td>`;
                            }


                            tRow += `<tr class="tbody">
                                        <td>${idx+1}</td>
                                        <td>${val.id_no}</td>
                                        <td>${val.name}</td>
                                        <td>${val.roll_no}</td>

                                        <input type="hidden" class="student_id" name="student_id[]" value="${val.id}" >
                                        <input type="hidden" name="class_id" value="${class_id}">
                                        <input type="hidden" name="section_id" value="${section_id}">
                                        <input type="hidden" name="subject_id" value="${subject_id}">
                                        <input type="hidden" name="category_id" value="${category_id}">
                                        <input type="hidden" name="group_id" value="${group_id}">
                                        <input type="hidden" name="exam_id" value="${exam_id}">
                                        ${tbodyRow}
                                    </tr>`;
                        });

                        let table = `<p class="p-2">Subject: <span id="sub-name"></span></p> <table class="table table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Student Id</th>
                                                <th>Student Name</th>
                                                <th>Roll No</th>
                                                ${thList}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tRow}
                                        </tbody>
                                    </table>`;
                        $('.student-list').removeClass('d-none');
                        $('.student-list').html(table);
                        getMarksInput(class_id,section_id,category_id,group_id,exam_id, subject_id);
                        //get subject Name
                        let subName = $("input[name='class_subject_id']:checked").data('name');
                        $('#sub-name').html(subName);

                    }
                });
                }





            });

            //get marks input for edit and update
            function getMarksInput(class_id,section_id,category_id,group_id,exam_id, subject_id) {

                $.get("{{ route('marks-input-get-marks') }}", {
                    class_id,
                    section_id,
                    category_id,
                    group_id,
                    exam_id,
                    subject_id
                }, function(data) {

                    if (data) {
                        $('input[name="student_id[]"]').map(function(idx, elem) {
                            let studentId = $(elem).val();
                            let result = data.filter((item) => {
                                return item.student_id == studentId;
                            });
                            $.each(result, function(i, v) {
                                $(elem).closest('tr').find(
                                    `.mark_dist_details_id-${v.sub_marks_dist_details_id}`
                                ).val(v.marks);
                            });
                        }).get();
                        $('#save-btn').html("<i class='fa fa-save'></i>Update");
                    } else {
                        $('#save-btn').html("<i class='fa fa-save'></i>Save");
                    }

                });
            }

            //get Subjects
            function getSubjects(class_id,section_id,category_id,group_id,exam_id) {

                $.get("{{ route('teacherpanel.exam-management.marks-input.get-subjects') }}", {
                        class_id,
                        section_id,
                        category_id,
                        group_id,
                        exam_id
                    },
                    function(data) {
                        console.log(data);
                        if (data.length == 0) {
                            $('#subject-list').addClass('d-none');
                            $('#process-btn').addClass('d-none');
                            $('.student-list').addClass('d-none');
                            $('#up-excel').addClass('d-none');
                            $('#dwn-excel').addClass('d-none');
                            $('#save-btn').addClass('d-none');

                            toastr.error("No Subject Found");
                        } else {
                            $('#subject-list').removeClass('d-none');
                            $('#process-btn').removeClass('d-none');


                            let donSubjects = [];
                            let pendingSubjects = [];

                            data.map(function(item) {

                                if (item.status === 'pending') {
                                    pendingSubjects.push(item);
                                } else {
                                    donSubjects.push(item);
                                }

                            });




                            if (donSubjects.length !== 0) {
                                let doneHtml =
                                    `<table class="table table-bordered" style="width:70%;">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Subject</th>
                                                <th>Students</th>
                                                <th>Excel</th>
                                            </tr>
                                        </thead>
                                    <tbody>`;

                                $.each(donSubjects, function(idx, val) {
                                    doneHtml +=
                                                `<tr>
                                                    <td>
                                                        <input type="radio" name="class_subject_id" data-name="${val.sub_name}" id="inlineRadio1${val.id}" value="${val.id}">
                                                    </td>
                                                    <td>${val.sub_name}</td>
                                                    <td>${val.student_count}</td>
                                                    <td>
                                                        <a href="#"><i class="fa  fa-file-excel"></i></a>
                                                    </td>
                                                 </tr>`;
                                });
                                doneHtml += '</tbody></table>';
                                $('#done-subjects').html(doneHtml);
                            }


                            if (pendingSubjects.length != 0) {
                                let pendingHtml =
                                    `<table class="table table-bordered sub-table" style="width:70%;">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Subject</th>
                                                <th>Students</th>
                                                <th> Excel</th>
                                            </tr>
                                        </thead>
                                    <tbody>`;

                                $.each(pendingSubjects, function(idx, val) {
                                    pendingHtml +=
                                        `<tr>
                                            <td align="center">
                                                <input type="radio" name="class_subject_id" data-name="${val.sub_name}" id="inlineRadio1${val.id}" value="${val.id}">
                                            </td>
                                            <td>${val.sub_name}</td>
                                            <td>${val.student_count}</td>
                                            <td>
                                                <a href="#"><i class="fa  fa-file-excel"></i></a>
                                            </td>
                                        </tr>`;
                                });
                                pendingHtml += '</tbody></table>';
                                $('#pending-subjects').html(pendingHtml);
                            }
                        }
                    });
            }

            //exam id change
            $('#exam_id').change(function (){
                $('#subject-list').addClass('d-none');
                //$('#subject-list').html('');
                $('#done-subjects').html('');
                $('#pending-subjects').html('');
                $('.student-list').addClass('d-none');
                $('#up-excel').addClass('d-none');
                $('#dwn-excel').addClass('d-none');
                $('#save-btn').addClass('d-none');
                $('#process-btn').addClass('d-none');
            });
            $('.section_id').change(function (){
                $('#subject-list').addClass('d-none');
                //$('#subject-list').html('');
                $('#done-subjects').html('');
                $('#pending-subjects').html('');
                $('.student-list').addClass('d-none');
                $('#up-excel').addClass('d-none');
                $('#dwn-excel').addClass('d-none');
                $('#save-btn').addClass('d-none');
                $('#process-btn').addClass('d-none');
            });

        });
    </script>
@endpush
