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
            <p class="float-right" id="std-count"></p>
        </div>

        <div class="card-body pt-2" id="student_table">
            <form action="{{route('sms.contact.excel-sms-send')}}" method="POST">
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
                                <th width='10%' scope="col">
                                    <div class="form-check py-0 my-0">
                                        <input type="checkbox" class="form-check-input" checked id="checkAll">
                                        <label class="form-check-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th>Number</th>
                                <th width='10%' class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="student">
                            @foreach ($numbers as $number)
                                <tr>
                                    <td><div class="form-check"><input type="checkbox" class="form-check-input" value="0{{$number}}" checked name="check[]" id="exampleCheck1" value=""><label class="form-check-label" for="exampleCheck1"></label></div></td>
                                    <td>0{{$number}}</td>
                                    <td width='10%' class="text-center"><a href="javascript:void(0)" class="btn-sm btn-danger removeBtn">X</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
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


    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });


    $(".removeBtn").click(function(){
        $(this).closest('tr').remove();
    });
</script>
@endpush
