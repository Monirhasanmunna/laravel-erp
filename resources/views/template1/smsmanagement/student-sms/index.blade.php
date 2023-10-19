@extends('admin.layouts.app')
@push('css')

@endpush
@section('content')

<div class="main-panel" id="marks-id" style="overflow: scroll">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')

    <div class="card new-table">
        <div class="card-header">
            <div class="card-title float-left">
                <h6 style="color: #009FFF">Student Send Message</h6>
            </div>
        </div>
        <div class="card-body">
            <form action="#" method="Get">
                @csrf
                <div class="row py-2">
                    <div class="col-md-3">
                        <label for="sms_template">Sms Template</label>
                        <select class="form-control" name="sms_template" id="sms_template">
                            <option value="all">All</option>
                            @foreach ($templates as $template)
                                <option value="{{$template->id}}" data="{{$template->message}}">{{$template->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card new-table mt-2">
        <div class="card-header">
            <div class="form-row">
                <div class="col">
                    <select class="form-control" style="width: 140px" name="session_id" id="session_id">
                        <option>Select Session</option>
                        @foreach (@$sessions as $session)
                            <option value="{{@$session->id}}" {{@$currentYear == $session->title? 'selected':'' }}>{{@$session->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <select class="form-control ml-2 " name="class_id" id="class_id">
                        <option>Select class</option>
                    </select>
                </div>
            </div>

            <p class="float-right" id="std-count"></p>
        </div>

        <div class="card-body pt-2 d-none" id="student_table">
            <form action="{{route('sms.student.sendsms')}}" method="POST">
                <button type="submit" class="btn btn-primary mt-2 mb-2 float-right"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Message 
                </button>
                <br>
                <br>
                @csrf
                <input type="number" hidden name="template_id" class="template_id">
                <input type="hidden" name="sms_content" class="sms_content">
                <div style="overflow-x:scroll;">
                    <table id="dtHorizontalExample" class="table">
                        <thead>
                            <tr>
                                <th width='1%' scope="col">
                                    <div class="form-check py-0 my-0">
                                        <input type="checkbox" class="form-check-input" checked id="checkAll">
                                        <label class="form-check-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th width='15%'>Student ID</th>
                                <th width='15%'>Name</th>
                                <th>Class</th>
                                <th>Roll No</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody class="student">

                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div id="preload"></div>
    </div>
</div>



@endsection

@push('js')
<script>

    $(document).ready(function () {
        $('#sms_template').change(function () {
            var sms_template_id = $("#sms_template").val();
            let sms_template = $('#sms_template option:selected').attr('data');
            $('#message').text(sms_template);
            $('.template_id').val(sms_template_id);
            $(".sms_content").val(sms_template);
        })

        $("#message").keyup(function(){
            var smsContent = $(this).val();
            $(".sms_content").val(smsContent);
        });

    })




    var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                                    <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                                    </circle>
                                    </svg>`;

    $('#session_id').change(function (e) {
        e.preventDefault();

        let session_id = $(this).val();
        getClass(session_id);
        getStudent('session_id',session_id);

    });


    function getClass(session_id){
        $.ajax({
            url : '/sms/student-sms/get-class/'+session_id,
            type: 'Get',
            success: (data)=>{
                $("#class_id").html(`<option>Select class</option>`);
                $.each(data,function(i,v){
                    $("#class_id").append(`<option value='${v.id}'>${v.name}</option>`);
                });
            }
        });
    }
    

    $("#class_id").change(function(){
       let class_id = $(this).val();
        getStudent('class_id',class_id);
    });


    function getStudent(type,id){
        $('#preload').html(loader);
        $.ajax({
            type: 'GET',
            url: `/sms/student-sms/get-student/${type}/${id}`,

            success: (data) => {
                
                $("#std-count").html(`Found (${data.length}) Student`);

                let html = '';
                $('#preload').html('');
                $.each(data, function (i, v) {
                    if (v) {
                        $('#student_table').removeClass('d-none');
                        html += '<tr>'
                        html +='<td><div class="form-check"><input type="checkbox" class="form-check-input"  checked name="check[]" id="exampleCheck1" value="'+v.id+'"><label class="form-check-label" for="exampleCheck1"></label></div></td>'
                        html +='<td><input type="text" class="form-control" id="name" value="'+v.id_no+'" readonly></td>'
                        html +='<td><input type="text" class="form-control" id="name" value="'+v.name+'" readonly></td>'
                        html +='<td><input type="text" class="form-control" id="name" value="'+v.ins_class.name+'" readonly></td>'
                        html +='<td><input type="text" class="form-control" id="roll_no" value="'+v.roll_no+'" readonly></td>'
                        html +='<td><input type="text" class="form-control" id="mobile_number" value="'+v.mobile_number+'" readonly></td>'
                        html +='</tr>'
                        html +='</br>'
                    }
                });

                $('.student').html(html);
            }
        });
    }

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("#checkAllteacher").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("#checkAllstaff").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    }); 

    $("#checkAllcomittee").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    }); 

</script>
@endpush
