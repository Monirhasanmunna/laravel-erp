@extends('frontent.theme1.layouts.web')

@section('content')
    <section class="StudentList section_gaps" style="padding-top: 40px">

        <div class="container">

            <div class="row">

                <div class="col-lg-12">

                    <div class="HeaderPart text-center">
                        <h2>Student List</h2>
                    </div>
                    <div class="StudentListHeader" style="border: 1px solid #efefef">
                        <form id="student-form" method="GET">
                            @include('frontent.theme1.layouts.custom-blade.search-student')
                        </form>
                    </div>
                </div>

                
                <!-- StudentListHeader -->
                <div class="StudentListContent">
                    <!-- ClassRoutine -->
                    <div class="ClassRoutine">
                        <h4 class="d-none" id="title">Showing Results For <span id="showing-result"></span></h4>

                        <div class="table-responsive student-table">

                            <div id="preload"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
@endsection

@push('js')
    <script>
        var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;

            $('#student-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var class_id = $("#class_id").val();

                var url = "{{ route('web.get-admited-students') }}";
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

                        let photo = '';

                        if(data.search){
                            $.each(data.search,function(i,v){
                                $(`#${i}`).html(v);
                            })
                        }
                        //Loop
                        $.each(data.students, function(i, v){

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
                                                <th class='text-center'> F.Name </th>
                                                <th class='text-center'> M.Name </th>
                                                <th class='text-center'> B/G </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${html}
                                        </tbody>
                                    </table>`;

                        $('.student-table').html(table);
                        $("#title").removeClass('d-none');
                    },

                    error: function(data) {
                        $('#image-input-error').text(data.responseJSON.message);
                    }
                });
            });
    </script>
@endpush
