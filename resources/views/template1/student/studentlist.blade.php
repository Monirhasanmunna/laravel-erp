@extends('admin.layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css"/>

@endpush
@section('content')
    <div class="main-panel">

        @include($adminTemplate.'.student.studentnav')

        <div class="card new-table">
            <div class="card-header">
                <h6>Search Student</h6>
            </div>
            <div class="card-body">
                <form id="student-form" method="GET">
                    @include('custom-blade.search-student')
                </form>
            </div>
        </div>

        <div id="preload" style="margin-top: 10px">

        </div>

        <div>
            <div class="card new-table" id="table-card" style="display: none">
                <div class="card-body">
                    <div class="d-flex justify-content-between float-left">
                        <div class="mb-3">
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Student List</h4>
                            <p class="card-title" style="color:rgba(0, 0, 0, 0.5)"> Result Of :<strong  id="session"></strong> , Class- <strong  id="class"></strong> , Shift- <strong  id="shift"></strong> , Category- <strong  id="category"></strong> , Section- <strong  id="section"></strong> , Group- <strong  id="group"></strong></p>
                        </div>
                    </div>

                    <a href="{{ route('student.export') }}" type="submit" class="btn btn-success float-right mb-3"><i class="fa fa-file-excel" aria-hidden="true"></i>
                        Export Excell</a>

                    <a href="javascript:void(0)" type="submit" class="btn btn-primary float-right mb-3 mr-2 printBtn"><i class="fa-solid fa-file-pdf"></i>Print</a>
                    {{-- <a href="javascript:void(0)" type="submit" class="btn btn-primary float-right mb-3 mr-2 pdfBtn"><i class="fa-solid fa-file-pdf"></i>Export Pdf</a> --}}

                    <div class="student-table">

                    </div>

                    <form style="display:none;" class="printForm" action="{{route('student.exportprint')}}" method="POST">
                        @csrf
                        <div class="student_list">

                        </div>
                    </form>

                    <form style="display:none;" class="pdfForm" action="{{route('student.exportpdf')}}" method="POST">
                        @csrf
                        <div class="student_list">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;

            $('#student-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var class_id = $("#class_id").val();

                var url = "{{ route('student.get-admited-students') }}";
                $('#preload').html(loader);

                let img = "https://png.pngtree.com/png-vector/20210129/ourlarge/pngtree-boys-default-avatar-png-image_2854357.jpg";
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: form.serialize(),
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        console.log(data);
                        let html = '';
                        $('#preload').html('');
                        $('#table-card').css('display', 'block');

                        let photo = '';


                        if(data.search){
                            $.each(data.search,function(i,v){
                                $(`#${i}`).html(v);
                            })
                        }
                        //Loop
                        $.each(data.students, function(i, v){

                            $(".student_list").append(`<input type="hidden" name="student_ids[]" value="${v.id}">`);
                            $(".student_list").append(`<input type="hidden" name="class_id" value="${class_id}">`);


                            let link = "/student/create-user/"+v.id;

                            if(v.photo){
                                photoDiv = `<img src="{{Config::get('app.s3_url')}}<@@PHOTO@@>" style='width:40px;height:40px;border-radius:50%;border:1px solid #aeacac;padding:3px;'/>`;
                                photo = photoDiv.replace("<@@PHOTO@@>",v.photo);
                            }
                            else{
                                if (v.gender == 'Male') {

                                    photo = '<img src="{{asset('male.png')}}" style="width:40px;height:40px;border-radius:50%;border:1px solid #aeacac;padding:3px;"/>';
                                } else {
                                    photo = '<img src="{{asset('female.jpeg')}}" style="width:40px;height:40px;border-radius:50%;border:1px solid #aeacac;padding:3px;"/>';
                                }
                            }

                            let editBtn = `<a class='btn-sm btn-primary' target='__blank' href="/student/edit/${v.id}"><i class="fa-solid fa-pen-to-square"></i></a>`;

                            if (v) {
                                html += `<tr>
                                            <td class='text-center'>
                                                ${photo}
                                            </td>

                                            <td class='text-center'>
                                                ${v.id_no}
                                            </td>
                                            <td class='text-center'>${v.name}</td>
                                            <td class='text-center'>${v.roll_no}</td>
                                            <td class='text-center'>${v.gender}</td>
                                            <td class='text-center'>${v.religion}</td>
                                            <td class='text-center'>${v.mobile_number}</td>
                                            <td class='text-center'>${v.father_name}</td>
                                            <td class='text-center'>${v.mother_name}</td>
                                            <td class='text-center'>${v.blood_group != null ? v.blood_group : ''}</td>
                                            <td class='text-center'>
                                                ${v.student_user != null ? `<a href="javascript:void(0)" class="btn-sm btn-success"><i class="fa-solid fa-square-check"></i></a>`:`<a href="${link}" class="btn-sm btn-info"><i class="fa fa-user-plus"></i></a>`}
                                                ${editBtn}
                                            </td>
                                        </tr>`;
                            } else {
                                html += `<tr>
                                            <td colspan="6" class="text-center">
                                                <h5 style="color:red">No Student Found!</h5>
                                            </td>

                                        </tr>`;
                            }

                        });

                        let table = `<table id="student-table" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class='text-center'> Image </th>
                                                <th class='text-center'>ID</th>
                                                <th class='text-center'> Name </th>
                                                <th class='text-center'> Roll </th>
                                                <th class='text-center'> Gendar </th>
                                                <th class='text-center'> Religion </th>
                                                <th class='text-center'> Number </th>
                                                <th class='text-center'> F. Name </th>
                                                <th class='text-center'> M. Name </th>
                                                <th class='text-center'> B/G </th>
                                                <th width='10%' class='text-center'> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${html}
                                        </tbody>
                                    </table>`;

                        $('.student-table').html(table);
                        $('#student-table').DataTable();

                    },
                    error: function(data) {
                        $('#image-input-error').text(data.responseJSON.message);
                    }
                });
            });
        });


        $(".printBtn").click(function(){
            $(".printForm").submit();
        });

        $(".pdfBtn").click(function(){
            $(".pdfForm").submit();
        });
    </script>
@endpush
