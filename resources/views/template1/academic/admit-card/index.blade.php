@extends('admin.layouts.app')
@push('css')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"rel="stylesheet">

<style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20rem; }
    .toggle.ios .toggle-handle { border-radius: 20rem; }

    .toggle-on.btn {
        padding-top: 9px;
    }
  </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.academic.academicnav')

        <div class="card new-table">
            <div class="card-body">
                <h6>Student Admit Card</h6>
                <hr>
                <form id="student-form" method="GET">
                    @include('custom-blade.admit-card-search')
                </form>
            </div>
        </div>

        <div id="preload" style="margin-top: 10px">

        </div>

        <div>
            <div class="card new-table" id="table-card" style="display: none">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Student Admit Card List</h4>
                            <p class="card-title" style="color:rgba(0, 0, 0, 0.5)"> Result Of : Academic Year- <span id="year"></span> , Class- <span id="class"></span> , Shift- <span id="shift"></span> , Category- <span id="category"></span> , Section- <span id="section"></span> , Group- <span id="group"></span> , Exam- <span id="exam"></span></p>
                        </div>
                    </div>


                    <a href="javascript:void(0)" id="downloadBtn" class="btn btn-sm btn-primary float-right mr-1 mb-2 mt-2 d-none"><i class="fa-solid fa-print"></i>Print</a>

                    <div class="">
                        <table id="customTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th> Name </th>
                                    <th> Roll No </th>
                                    <th> Class </th>
                                    <th class="text-center"> Action </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <form action="{{route('academic.admit-card.alldownload')}}" id="download-form" hidden method="Post">
                        @csrf
                        <div id="admit-card-download-form" hidden>
                            {{-- data form ajax --}}

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                    <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                    </circle>
                                    </svg>`;



            $('#student-form').submit(function(e){
                e.preventDefault();
                var form= $(this);

                var exam_id = $('#exam_id').val();
                var session = $("#session_id").val();
                var class_id = $("#class_id").val();
                var section = $("#section_id").val();
                var url = "{{ route('student.get-admited-students') }}";
                $('#preload').html(loader);

                if(session && exam_id && section){
                    $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        console.log(data);
                        $("#admit-card-download-form").empty();
                        if(data.students.length > 0){ $("#downloadBtn").removeClass('d-none');}

                        let html = '';
                        $('#preload').html('');
                        $('#table-card').css('display','block');
                        var isRoutineSelected = data.searchItems.isRoutineSelected ? data.searchItems.isRoutineSelected : 0;

                        $.each(data.students,function(i,v){
                            let viewBtn = `<a class="btn-sm btn-info" target="_blank" href="/academic/admit-card/view/${v.id}/${exam_id}/${session}/${isRoutineSelected}"><i class="fa fa-eye"></i></a>`;
                            let dwnBtn = `<a class="btn-sm btn-primary" href="/academic/admit-card/download-admit/${v.id}/${exam_id}/${session}/${isRoutineSelected}"><i class="fa fa-arrow-down"></i></a>`;

                            $value = `
                                    <input type="number" name="student_id[]" hidden value='${v.id}'>
                                    <input type="number" name="exam_id" hidden value='${exam_id}'>
                                    <input type="number" name="session_id" hidden value='${session}'>
                                    <input type="number" name="class_id[]" hidden value='${v.ins_class.id}'>
                                    <input type="number" name="is_routine_selected" hidden value='${isRoutineSelected}'>
                                    `;

                                $("#admit-card-download-form").append($value);

                            if(v){
                                html += `<tr>
                                            <td>
                                                ${v.id_no}
                                            </td>
                                            <td>${v.name}</td>
                                            <td>${v.roll_no}</td>
                                            <td>${v.ins_class.name}</td>
                                            <td class='text-center' width='10%'>${viewBtn}  ${dwnBtn}</td>
                                        </tr>`;
                            }else{
                                html += `<tr>
                                            <td colspan="6" class="text-center">
                                                <h5 style="color:red">No Student Found!</h5>
                                            </td>
                                        </tr>`;
                            }
                        });

                        $('tbody').html(html);
                        $('#customTable').DataTable();
                    },
                    error: function(data) {
                        $('#image-input-error').text(data.responseJSON.message);
                    }
                });
                }


                var searchUrl = "{{route('search-result')}}";
                $.ajax({
                    type    : 'GET',
                    url     : searchUrl,
                    data    : form.serialize(),
                    contentType: false,
                    processData: false,
                    success     : function(response){
                        if(response.academic_year.title){
                            $("#year").html(response.academic_year.title);
                        }
                        if(response.class.name){
                           $("#class").html(response.class.name);
                        }
                        if(response.category.name){
                            $("#category").html(response.category.name);
                        }
                        if(response.exam.name){
                            $("#exam").html(response.exam.name);
                        }
                        if(response.group.name){
                            $("#group").html(response.group.name);
                        }
                        if(response.section.name){
                            $("#section").html(response.section.name);
                        }
                        if(response.shifts.name){
                            $("#shift").html(response.shifts.name);
                        }
                    }
                });
            });


            $("#downloadBtn").click(function(){
               $("#download-form").submit();
            });

</script>
@endpush
