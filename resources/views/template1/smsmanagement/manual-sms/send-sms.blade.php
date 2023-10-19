@extends('admin.layouts.app')
@push('css')
<style>
    tr {
        height: 40px;
        padding-top: 0px;
        padding-bottom: 0px;
    }

</style>
@endpush
@section('content')

<div class="main-panel" id="marks-id" style="overflow: scroll">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')

    <div class="card new-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 style="color:rgba(0, 0, 0, 0.5)">Send SMS</h4>
            <a href="{{route('sms.contact.index')}}" class="btn btn-sm btn-primary "><i class="fa-solid fa-arrow-left"></i>Back</a>
        </div>
        <form action="{{route('sms.contact.sms-send')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7" style="border-right: 1px solid rgba(0, 0, 0, 0.158)">
                    <div class="card-body">
                        <div class="row py-2">
                            <div class="col-md-6">
                                <label for="sms_template">Sms Template</label>
                                <select class="form-control" name="sms_template" id="sms_template">
                                    <option hidden value="">select once</option>
                                    @foreach ($templates as $template)
                                    <option value="{{$template->id}}" data="{{$template->message}}">{{$template->title}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sms_template">Contacts</label>
                                <select class="form-control @error('contact_id') is-invalid @enderror" name="contact_id"
                                    id="contact_id">
                                    <option hidden value="">select once</option>
                                    @foreach ($contacts as $contact)
                                    <option value="{{$contact->id}}">{{$contact->file_name}}</option>
                                    @endforeach
                                </select>

                                @error('title')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12 mt-3">
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="message"
                                        rows="3" name="content"></textarea>
                                    @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i>Send SMS</button>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card-body" style="padding-top: 44px;">
                        <h4 style="color:rgba(0, 0, 0, 0.5)">Number List <small class="text-danger" style="font-size: 11px"> (Please select contacts first)</small></h4>
                        <table id="customTable" class="table  table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th width='10%' scope="col" class="text-center pl-2">
                                        <div class="form-check py-0 my-0">
                                            <input type="checkbox" class="form-check-input" checked id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>Number</th>
                                </tr>
                            </thead>
                            <tbody id="t_body">
                                <tr><td class='text-center' colspan='2'>Select Contact First</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
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

        $("#message").keyup(function () {
            var smsContent = $(this).val();
            console.log(smsContent);
            $(".sms_content").val(smsContent);
        });


        $("#contact_id").change(function () {
            let id = $(this).val();
            $.ajax({
                url: '/sms/contact/get-number/' + id,
                type: 'GET',
                success: (data) => {
                    console.log(data);
                    let html = '';
                    $.each(data.numbers, function (i, v) {
                        if(v){
                            html += `
                            <tr>
                                <td class='text-center pl-2'><div class="form-check"><input type="checkbox" class="form-check-input"  checked name="check[]" id="exampleCheck1" value="${v.number}"><label class="form-check-label" for="exampleCheck1"></label></div></td>
                                <td>${v.number}</td>
                            </tr>
                        `;
                        }else{
                            html += `<td class='text-center' colspan='2'>No Data Found</td>`;
                        }
                    });

                    $("#t_body").html(html);
                }
            });
        });

    })

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

</script>
@endpush
