@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.student.studentnav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6 class="float-left">Student Subject Assign List</h6>
                        <div class="float-right">
                            <a href="{{route('student.subject-assign.bulk')}}" class="btn btn-info"><i class="fa fa-tasks"></i>Bulk Assign</a>
                            <a href="{{route('student.subject-assign.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Reassign Subject</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <form id="student-form" method="GET">
                            @include('custom-blade.search-student')
                        </form>
                    </div>
                </div>

                <div class="mt-2 mb-3 d-none" id="preload"></div>

                <div class="card d-none" id="data-card">
                    <div class="card-header">
                        <p>Student List</p>
                        <a href="" target="_blank" class="btn btn-success" id="printBtn"><i class="fa fa-print"></i>Print</a>
                    </div>
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
                                        <th>{{$type->name}} Subject</th>
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

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                            <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                            </circle>
                          </svg>`;
            $('#student-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var class_id = $("#class_id").val();

                var url = "{{ route('student.get-students-with-assign-subjects')}}";
                $('#preload').removeClass('d-none');
                $('#preload').html(loader);

                //print btn route
                let printRoute = "{{route('student.subject-assign.print',100)}}";
                printRoute = printRoute.replace('100',form.serialize());    
                $('#printBtn').attr("href", printRoute);

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function (data){
                        $('#preload').html("");
                        $('#data-card').removeClass('d-none');
                        let html = '';

                        $.each(data,function (i,v){

                            let otherSubjects = '';
                           
                            $.each(v.otherSubjects,function (idx,val){
                            
                                
                                let subList = '';
                                $.each(val,function (index,chunk){
                                    let child = '';
                                    $.each(chunk,function(idx,val){
                                        if (idx === chunk.length - 1){ 
                                            child += `<td style="border: none!important;">${val.class_subjects.subject.sub_name}</td>`;
                                        }
                                        else{
                                            child += `<td style="border: none!important;">${val.class_subjects.subject.sub_name},</td>`;
                                        }
                                        
                                    });
                                    subList += `<table><tr>${child}</tr></table>`;
                                });

                                otherSubjects += `<td>${subList}</td>`;
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
        });
    </script>
@endpush
