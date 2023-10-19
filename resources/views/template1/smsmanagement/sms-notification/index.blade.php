@extends('admin.layouts.app')

@push('css')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
    rel="stylesheet">
@endpush

@section('content')
<div class="main-panel">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')
    <div>
        <div class="card new-table">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">SMS Notifications</h4>
                    </div>
                </div>
                <div class="content-wrapper text-primary">
                    <form class="" id="online-admission" method="GET">
                        <div class="row mt-2">
                            <div class="col-4">
                                <h5 class="p-2 text-white" style="background-color: #154568">Online Admission Notification</h5>
                            </div>

                            <div class="col-1">
                                <div class="input-group mb-2">
                                    <input type="checkbox" data-toggle="toggle" {{@$admission->status == 1 ? 'checked' : ''}} data-onstyle="primary" value="1" name="status">
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="inlineFormInputGroup" value="{{@$admission->content}}" name="content" placeholder="Enter SMS Content Here">
                                </div>
                            </div>

                            <div class="col-2">
                                <button type="submit" class="btn text-white" style="background-color: #10405d">Update</button>
                                <a class="btn btn-success" href="{{route('sms.sms-notification.reset','admission')}}"><i class="fa-solid fa-window-restore"></i></a>
                            </div>
                        </div>
                    </form>
                    
                    <form class="" id="online-admission" method="GET">
                        <div class="row mt-2">
                            <div class="col-4">
                                <h5 class="p-2 text-white" style="background-color: #156823">Confirm Admission Notification</h5>
                            </div>

                            <div class="col-1">
                                <div class="input-group mb-2">
                                    <input type="checkbox" data-toggle="toggle" {{@$confirmAdmission->status == 1 ? 'checked' : ''}} data-onstyle="primary" value="1" name="status">
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="inlineFormInputGroup" value="{{@$confirmAdmission->content}}" name="content" placeholder="Enter SMS Content Here">
                                </div>
                            </div>

                            <div class="col-2">
                                <button type="submit" class="btn text-white" style="background-color: #10405d">Update</button>
                                <a class="btn btn-success" href="{{route('sms.sms-notification.reset','confirm-admission')}}"><i class="fa-solid fa-window-restore"></i></a>
                            </div>
                        </div>
                    </form>

                    <form class="" id="payemnt-collection" method="GET">
                        <div class="row mt-2">
                            <div class="col-4">
                                <h5 class="p-2 text-white" style="background-color: #156823">Payment Collection Notification</h5>
                            </div>

                            <div class="col-1">
                                <div class="input-group mb-2">
                                    <input type="checkbox" data-toggle="toggle" {{@$payment->status == 1 ? 'checked' : ''}} data-onstyle="primary" value="1" name="status">
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="inlineFormInputGroup" value="{{@$payment->content}}" name="content" placeholder="Enter SMS Content Here">
                                </div>
                            </div>

                            <div class="col-2">
                                <button type="submit" class="btn text-white" style="background-color: #10405d">Update</button>
                                <a class="btn btn-success" href="{{route('sms.sms-notification.reset','payment-collection')}}"><i class="fa-solid fa-window-restore"></i></a>
                            </div>
                        </div>
                    </form>

                    <form class="" id="dues-notification" method="GET">
                        <div class="row mt-2">
                            <div class="col-4">
                                <h5 class="p-2 text-white" style="background-color: #156823">Due's Notification</h5>
                            </div>

                            <div class="col-1">
                                <div class="input-group mb-2">
                                    <input type="checkbox" data-toggle="toggle" {{@$dues->status == 1 ? 'checked' : ''}} data-onstyle="primary" value="1" name="status">
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="inlineFormInputGroup" value="{{@$dues->content}}" name="content" placeholder="Enter SMS Content Here">
                                </div>
                            </div>

                            <div class="col-2">
                                <button type="submit" class="btn text-white" style="background-color: #10405d">Update</button>
                                <a class="btn btn-success" href="{{route('sms.sms-notification.reset','dues')}}"><i class="fa-solid fa-window-restore"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
</script>

<script>
    
    $(document).ready(function(){


        $("#online-admission").submit(function(e){
            e.preventDefault();

            var form = $(this);
            var url  = "{{route('sms.sms-notification.admission-template')}}";

            $.ajax({
                type: 'GET',
                url: url,
                data: form.serialize(),
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    if (data.status == '200') {
                            Toast.fire({
                                icon: 'success',
                                title: data['message']
                            });
                        }
                }
            });
        });


        $("#payemnt-collection").submit(function(e){
            e.preventDefault();

            var form = $(this);
            var url  = "{{route('sms.sms-notification.payment-template')}}";

            $.ajax({
                type: 'GET',
                url: url,
                data: form.serialize(),
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    if (data.status == '200') {
                            Toast.fire({
                                icon: 'success',
                                title: data['message']
                            });
                        }
                }
            });
        });

        $("#dues-notification").submit(function(e){
            e.preventDefault();

            var form = $(this);
            var url  = "{{route('sms.sms-notification.dues-template')}}";

            $.ajax({
                type: 'GET',
                url: url,
                data: form.serialize(),
                contentType: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    if (data.status == '200') {
                            Toast.fire({
                                icon: 'success',
                                title: data['message']
                            });
                        }
                }
            });
        });
        
    });

</script>
@endpush
