@extends('admin.layouts.app')
@push('css')
    <style>
        .table th {
            vertical-align: center !important;
            border-bottom: 2px solid #dee2e6 !important;
            padding: 0.75rem !important;
            border-top: 1px solid #dee2e6 !important;
            display: table-cell !important;
            text-align: center !important;
        }

        .table td {
            vertical-align: center !important;
            border-bottom: 2px solid #dee2e6 !important;
            padding: 0.75rem !important;
            border-top: 1px solid #dee2e6 !important;
            display: table-cell !important;
        }

        select .form-control {
            width: 100px !important;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.routinemanagement.routineNav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <div class="card-title float-left">
                        <h6 style="color: black"> Create Class Routine</h6>
                    </div>
                    <a href="{{ route('classroutine.index') }}" class="btn btn-dark  float-right"> <i
                            class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="card-body">
                    <form action="" id="search-form">
                        @include('custom-blade.search-class-routine')
                    </form>
                </div>
            </div>
        </div>
        <div id="loader" class="text-center"></div>
        <form action="{{ route('classroutine.store') }}" method="post">
            @csrf
            <div class="card new-table d-none" id="routine-card">
                <div class="card-header">Class Routine</div>
                <div class="card-body">
                    <div id="table-card"></div>
                    <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i>Submit</button>
                </div>
            </div>
        </form>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="teacherModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assigned Period List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="font-weight: bold" class="text-center mb-2"><b> <span id="teacher-name"></span> <code > (Already Assigned)</code></b></p>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Category</th>
                                <th>Group</th>
                                <th>Period</th>
                            </tr>
                        </thead>
                        <tbody id="teacher-modal-tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#search-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                $('#loader').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#routine-card').removeClass('d-none');
                $.get("{{ route('classroutine.get-time-settings') }}", form.serialize(), function(data) {

                    $('#loader').html('');


                    let thead = "";
                    let periadName = "";
                    let periadTime = "";
                    let inputFields = "";

                    if (data.timeSettings) {

                        $.each(data.timeSettings.details, function(i, v) {

                            periadName += `<th data-id="${v.id}">
                                                ${v.period.name} 

                                            </th>
                                           <input type="hidden" name="details_id[]" value="${v.id}">`;

                            periadTime +=
                                `<td class="text-center">${v.start_time} - ${v.end_time}</td>`;

                            thead = `        
                                    <tr>
                                        <th rowspan="2" class="text-center" width="10%">Time & Day</th>
                                        ${periadName}
                                    </tr>
                                    <tr>
                                        ${periadTime}
                                    </tr>`;

                            //start tbody
                            let subOptions = "";
                            $.each(data.subjects, function(i, v) {
                                subOptions +=
                                    `<option value="${v.id}">${v.subject.sub_name}</option>`;
                            });

                            let tecOptions = "";
                            $.each(data.teachers, function(i, v) {
                                tecOptions +=
                                    `<option value="${v.id}">${v.name}</option>`;
                            });

                            inputFields += `<td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <select name="subject_id[]" id="" class="form-control" required>
                                                        <option value="">Subject</option>
                                                        ${subOptions}
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <select name="teacher_id[]" id="" data-info="${form.serialize()}" class="form-control teacher_id" required>
                                                        <option value="">Teacher</option>
                                                        ${tecOptions}
                                                    </select>
                                                </div>
                                            </div>
                                        </td>`
                            tbody = `  <tr>
                                       <td class="text-center" >${data.timeSettings.day}</td>
                                        ${inputFields}
                                    </tr>`;
                        });


                        let table = ` <input type="hidden" name="search" value="${form.serialize()}">
                        <table class="table table-responsive" id="routine-table">
                                ${thead}
                                <tbody>
                                    ${tbody}
                                </tbody>
                      </table>`;
                        $('#table-card').html(table);
                    } else {
                        toastr.error("Please Set Time Settings");
                        $('#table-card').html("");
                        $('#routine-card').addClass('d-none');
                    }

                });
            });

            //change teacher
            $(document).on('change', '.teacher_id', function() {

                let teacherId = $(this).val();
                let searchInfo = $(this).data('info');

                let myIndex = $(this).closest('td').index();
                let periodId = $(`#routine-table tr:nth-child(1) th:eq(${myIndex})`).data("id");

                let $this = $(this);

                $.get("{{ route('classroutine.get-teacher-info') }}", {
                    'teacher_id': teacherId,
                    'search_info': searchInfo,
                    'period_id': periodId
                }, function(data) {
                    if (data.length > 0) {
                        $this.val("");
                        $('#teacherModal').modal('show');

                        let html = "";

                        $.each(data,function(i, val) {
                            html += `<tr>
                                        <td>${val.class}</td>
                                        <td>${val.category}</td>
                                        <td>${val.group}</td>
                                        <td>${val.period}</td>
                                     </tr>`;
                        });
                        $('#teacher-name').html(`${data[0].teacher}`);
                        $('#teacher-modal-tbody').html(html);
                    }
                });





                // myIndex = myIndex - 1;
                // let time = $(`#routine-table tr:nth-child(2) td:eq(${myIndex})`).html();



            });

        });
    </script>
@endpush
