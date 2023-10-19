@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.student.studentnav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6 class="float-left">Student Subject Assign List</h6>
                        <a href="{{route('student.subject-unassigned.bulk')}}" class="btn btn-info float-right"><i class="fa fa-tasks"></i>Bulk Unassigned</a>
                    </div>
                    <div class="card-body">
                        <form id="student-form" method="GET">
                            @include('custom-blade.search-student')
                        </form>
                    </div>
                </div>

                <div id="preload" style="margin-top: 10px">

                </div>

                <div class="card d-none" id="std-list">
                    <div class="card-body">
                        <table id="customDatatable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Id No</th>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>Class</th>
                                @foreach($subjectTypes as $type)
                                    <th>{{$type->name}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody id="student-list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $('#session_id').attr("required", "true");
            $('#section_id').attr("required", "true");

            const loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                            <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                            </circle>
                          </svg>`;
            $('#student-form').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const class_id = $("#class_id").val();

                const url = "{{ route('student.get-students-with-assign-subjects') }}";
                $('#preload').html(loader);

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function (data){
                        $('#std-list').removeClass('d-none');
                        $('#preload').html('');
                        let html = '';

                        $.each(data,function (i,v){

                            let otherSubjects = '';
                            $.each(v.otherSubjects,function (idx,val){
                                let subList = '';
                                $.each(val,function (index,value){
                                    
                                    subList += `<li class="list-group-item">
                                                     <p class="float-left">${value.class_subjects.subject.sub_name}</p>
                                                     <a href="javascript:void(0)" data-student="${v.id}" data-id="${value.id}"  class="btn btn-danger btn-sm ml-2 delete-btn float-right">x</a>
                                                </li>`;


                                });

                                otherSubjects += `<td><ul class="list-group">${subList}</ul></td>`;
                            });

                            html += `<tr>
                                        <input type="hidden" name="student_id[]" value="${v.id}" >
                                        <td>${i+1}</td>
                                        <td>${v.id_no}</td>
                                        <td>${v.student_name}</td>
                                        <td>${v.roll_no}</td>
                                        <td>${v.class}-${v.shift}-${v.section}</td>
                                        ${otherSubjects}
                                    </tr>`;
                        });

                        $('#student-list').html(html);
                        $('#customDatatable').DataTable();

                    }
                });
            });

            $(document).on('click','.delete-btn',function (){

                let id = $(this).data('id');
                let student_id = $(this).data('student');
                let $this = $(this);
                $.get("{{route('student.delete-student-assign-subject')}}",{id,student_id},function (data){
                    console.log(data);
                    if(data.status == 200){
                        $this.closest('.list-group-item').remove();
                        toastr.success('Delete Successfully');
                    }
                    else{
                        toastr.error(data.message);
                    }
                });
            });
        });
    </script>
@endpush
