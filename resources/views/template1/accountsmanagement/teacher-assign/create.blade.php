@extends('admin.layouts.app')

@push('css')
    <style>
        .list-group-item {
            padding: 1px 1.25rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate . '.accountsmanagement.topmenu_accountsmanagement')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4 style="color:rgba(0, 0, 0, 0.5)">Teacher Assign</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ Route('accounts.teacher-assign.index') }}" class="btn btn-dark mr-2"><i
                                    class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="teacher-form">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="">Select Teacher</label>
                                    <select name="teacher_id" class="form-control" id="teacher_id" required>
                                        <option value="">Select Teacher</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Select Session</label>
                                    <select name="session_id" class="form-control" id="session_id" required>
                                        <option value="">Select Session</option>
                                        @foreach ($sessions as $session)
                                            <option value="{{ $session->id }}">{{ $session->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" style="margin-top: 35px" class="btn btn-primary"><i
                                            class="fa fa-arrow-right"></i>Process</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="loader" class="text-center"></div>

                <div class="card d-none" id="sec-card">
                    <div class="card-header">
                        Section List
                    </div>
                    <div class="card-body">
                        <form action="{{ route('accounts.teacher-assign.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="teacher_id">
                            <input type="hidden" name="session_id">
                            <div class="row" id="section-list">
                                {{-- get element from js --}}
                            </div>
                            <button type="submit" id="submit-btn" class="btn btn-primary mt-3"><i
                                    class="fa fa-save"></i>Submit</button>
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
            $('#customTable').DataTable();
            $("#accounts_setting").addClass('active');
            $('#settings-nav').removeClass('d-none');

            $('#teacher-form').submit(function(e) {
                e.preventDefault();
                $('#loader').html(`<i class="fa fa-spinner fa-spin"></i>`);
                let form = $(this);
                let url = "{{ route('accounts.teacher-assign.get-sections') }}";
                let formData = form.serialize();

                appendData(url, formData);

            });


            

            async function appendData(url, formData) {

                $.get(url, formData, function(data) {
                    $('#loader').html("");
                    if (data.length > 0) {
                        $('#sec-card').removeClass('d-none');
                        let classHtml = "";

                        $.each(data, function(idx, chunk) {
                            let childHtml = "";

                            $.each(chunk, function(index, value) {

                                let listHtml = "";
                                $.each(value.sections, function(ix, val) {
                                    listHtml += `<li class="list-group-item">
                                                        <div class="form-check">
                                                        <input class="form-check-input section-check-${val.id}" name="section_id[]" type="checkbox" value="${val.id}" id="sec-check-${val.id}">
                                                            <label for="sec-check-${val.id}">${val.class.name}-${val.shift.name}-${val.name}</label>
                                                        </div>
                                                    </li>`;
                                });

                                childHtml += `<div class="col-md-3 mt-4">
                                                    <div class="card">
                                                        <div class="card-header">${value.name}</div>
                                                        <div class="card-body">
                                                            <ul class="list-group">
                                                                ${listHtml}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>`;
                            });
                            classHtml += childHtml;
                        });
                        $('#section-list').html(classHtml);
                        //set teacher & session id
                        let teacherId = $('#teacher_id').val();
                        let sessionId = $('#session_id').val();
                        $("input[name='teacher_id']").val(teacherId);
                        $("input[name='session_id']").val(sessionId);
                        //select session
                        selectSections(teacherId, sessionId); 

                    } else {
                        $('#sec-card').addClass('d-none');
                        toastr.error("No Data Found!");
                    }
                });

            }



            function selectSections(teacherId, sessionId) {

                let url = "{{ route('accounts.teacher-assign.get-selected-sections') }}";

                $.get(url, {
                    teacher_id: teacherId,
                    session_id: sessionId
                }, function(data) {

                    if (data.length > 0) {
                        $('#submit-btn').html(`<i class="fa fa-save"></i>Update`);
                        $.each(data, function(idx, val) {
                            $(`.section-check-${val}`).prop('checked', true);
                        });

                    } else {
                        $('#submit-btn').html(`<i class="fa fa-save"></i>Submit`);
                    }
                });
            }


        });
    </script>
@endpush
