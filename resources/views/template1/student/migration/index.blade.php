@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
@endpush
@section('content')
    <div class="main-panel" style="overflow-y: scroll;">

        @include($adminTemplate.'.student.studentnav')

        <div class="card new-table mb-3">
            <h5 class="p-4">Student Migration</h5>
            <hr class="mt-0">
            <div class="row">
                <div class="col-md-3" id="migration">
                    <div class="card-body" style="padding-top:2px;">
                        <h6>Migration From</h6>
                        <hr>
                        <form action="" id="migration-from-form">
                             @include('custom-blade.search-student')
                        </form>
                    </div>
                </div>
                <div class="col-md-6 d-none student-list" id="migration">
                    <form action="{{route('student.migration.store')}}" method="POST">
                        @csrf
     
                        <div class="card-body">
                            <h6>Student List</h6>
                            <hr>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <div class="form-check py-0 my-0">
                                                <input type="checkbox" class="form-check-input" checked id="checkAll">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th class="text-center">Id No</th>
                                        <th class="text-center">Roll No</th>
                                        <th class="text-center">New Roll No</th>
                                        <th class="text-center">Class</th>
                                    </tr>
                                </thead>
                            <tbody>

                            </tbody>
                            </table>
                        </div>
                </div>

                <div class="col-md-3 d-none migration-form" id="migration">

                    <div class="card">
                        <div class="card-body">
                            <h6>Migration To</h6>
                            <hr>
                            @include('custom-blade.search-student-migration')
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();

            $("#checkAll").click(function() {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                      <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                      </circle>
                    </svg>`;

            //Get Admission Students
            $('#migration-from-form').submit(function(e){
                e.preventDefault();
                var form = $(this);
                var url = "{{ route('student.get-admited-students') }}";

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        $('#migration-from-form')[0].reset();
                        let html = '';
                        $.each(data.students,function(i,value){
                            html += `<tr>
                                        <input type="hidden" name="student_id[]" value="${value.id}" />
                                        <td scope="col">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input checkbox" value="${i}" name="check[]" id="exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1"></label>
                                            </div>
                                        </td>
                                        <td>${value.name}</td>
                                        <td class="text-center">${value.id_no}</td>
                                        <td width='10%' class="text-center">${value.roll_no}</td>
                                        <td width='10%' class="text-center"><input type="number" class="form-control" name="new_roll[]"></td>
                                        <td class="text-center">${value.ins_class.name}</td>
                                    </tr>`;
                        });

                        $('tbody').html(html);
                        $(".student-list").removeClass('d-none');
                        $(".migration-form").removeClass('d-none');
                    }
                });
            });

        });
    </script>
@endpush
