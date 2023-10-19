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
    <div class="main-panel">
        @include($adminTemplate.'.teachers.teachernav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6>Assign Subject Teacher</h6>
                        <a href="{{route('assign_teacher.subject')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                    <div class="card-body">
               
                        <form id="process-form">
                            <div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label> Select Teacher</label>
                                        <select name="teacher_id" id="teacher_id" class="form-control" required>
                                            <option>Select Teacher</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Select Session</label>
                                        <select name="session_id" id="session_id" class="form-control"  required>
                                            <option value="">Select Session</option>
                                            @foreach ($sessions as $session)
                                                <option value="{{$session->id}}">{{$session->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Select Class</label>
                                        <select name="class_id" id="class_id" class="form-control class_id" required>
                                            <option value="">Select Class</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" style="margin-top: 34px" id="processBtn" class="btn btn-primary" ><i class="fa fa-arrow-right"></i>Process</button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card d-none" id="sub-card">
                        <div class="card-header">
                            <h6>Subject List</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{route('assign_teacher.subject-store')}}" method="POST">
                                @csrf
                                <input type="hidden" name="teacher_id" id="teacher-id">
                                <div class="row" id="subject-list">

                                </div>
                                <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#customTable').DataTable();
            $('.select2-multiple').select2();
            $(".chosen-select").chosen();


            //GET SUBJECTS
            $('#session_id').change(function(){

                let session_id = $(this).val();

                $.get("{{route('student.get-classes')}}",
                {
                    session_id
                },
                function(data){
                    let html = '<option>Select Class</option>';
                    data.classes.map(function(item){
                    html += `<option value="${item.id}">${item.name}</option>`;
                    });

                    $('.class_id').html(html);
                });
            });

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;
                          

            $('#process-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = "{{ route('assign_teacher.get-class-subjects') }}";

                let teacherId = $("#teacher_id").val();
                $("#teacher-id").val(teacherId);


                $('#preload').html(loader);

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        
                        if(data.length > 0){
                            $('#sub-card').removeClass('d-none');
                        }
                        else{
                            $('#sub-card').addClass('d-none');
                            toastr.error("No Subject Found");
                        }

                        let div = "";

                        data.forEach((chunk)=> {

                            let subjectList = ``;
                            $.each(chunk,(i,v)=> {
                                subjectList += ` <div>
                                                    <input  style="margin:0.3rem" type="checkbox" id="sub-${i}" name="subjects[]" value="${v.id}">
                                                    <label for="sub-${i}">${v.subject.sub_name}</label>
                                                </div>`;
                            });

                            div += `<div class="col-md-3" style="background: aliceblue;
                                                                 margin: 0px 0px 0px 12px;
                                                                 border: 1px solid #ededed;
                                                                 padding: 12px 0px 3px 12px;">
                                        ${subjectList}
                                    </div>`;
                            
                        });

                        $("#subject-list").html(div);
                    }
                    });


            });


        });
    </script>
@endpush
