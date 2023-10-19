@extends('admin.layouts.app')
@push('css')
    
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
        <div class="card new-table">
            <div class="card-header" style="background-color: #f3f5f9 !important">
                <div class="card-title float-left">
                    <h6 style="color: black">Device Token List</h6>
                </div>
                <div class="btn-wrapper">
                    <a  href="javascript::void(0)" id="addBtn" class="btn btn-primary mr-2 float-right"><i class="fa fa-plus"></i> Add Device Token</a> 
                </div>
            </div>
            <br>
            <div id="table" class="card-body pt-0">
                <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align:center" width='10%'> #SL </th>
                            <th> Device Token </th>
                            <th style="text-align:center" width='10%'> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($devices as $item)
                        <tr class="odd">
                            <td style="text-align:center">{{$loop->iteration}}</td>
                            <td>{{$item->device_token}}</td>
                            <td style="text-align:center">
                                <a href="javascript:void(0)" onclick="editfunction({{$item->id}})" class="btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="{{route('attendance.device-configure.destroy',$item->id)}}" class="btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="device_token_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Device Token Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="branch-form" action="{{route('attendance.device-configure.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Device Token</label>
                            <input id="name" type="text" name="device_token" class="form-control">
                            <small class="text-danger" id="nameError"></small>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="device_token_edit_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Device Token</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="branch-form" action="{{route('attendance.device-configure.update')}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group" id="form-item">
                            
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
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
            $("#addBtn").click(function(){
                $("#device_token_Modal").modal('show');
            });


            function editfunction(id)
            {
                $.ajax({
                    url : '/attendance/device-configure/edit/'+id,
                    type : 'GET',
                    success : (data) => {
                       // console.log(data);
                        $("#form-item").empty();

                        var html = `
                            <label for="name">Device Token</label>
                            <input id="name" type="text" name="device_token" value='${data.device_token}' class="form-control">
                            <small class="text-danger" id="nameError"></small>
                            <input type="hidden" name="id" value='${data.id}' class="form-control">
                        `;

                        $("#form-item").html(html);
                        $("#device_token_edit_Modal").modal('show');
                    }
                    
                });
            }



            $(".device-config").addClass('custom_nav');
            $('.setting').closest('li').addClass('custom_nav');
            $('#setting-item').removeClass('d-none');
    </script>
@endpush
