@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">

        @include($adminTemplate.'.attendance.partials.attendancenav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <div class="card-title float-left">
                        <h6 style="color: black">Off Day List</h6>
                    </div>
                    <div class="btn-wrapper">
                        <a  href="javascript:void(0)" id="addBtn" class="btn btn-primary mr-2 float-right"><i class="fa fa-plus"></i> Add New</a> 
                    </div>
                   
                </div>
                <div class="card-body">
                    <table id="customTable" class="table table-striped table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align:center"> #SL </th>
                                <th style="text-align:center"> Title </th>
                                <th style="text-align:center"> Start Date </th>
                                <th style="text-align:center"> End Date</th>
                                <th style="text-align:center"> Total</th>
                                <th style="text-align:center"> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offdays as $offday)
                            <tr>
                                <td style="text-align:center">{{$loop->iteration}}</td>
                                <td style="text-align:center">{{$offday->title}}</td>
                                <td style="text-align:center">{{$offday->start_date}}</td>
                                <td style="text-align:center">{{$offday->end_date}}</td>
                                <td style="text-align:center">{{$offday->total_days}} Days</td>
                                <td style="text-align:center">
                                    <a class="btn-sm btn-primary" onclick="editFunction({{$offday->id}})" href="javascript:void(0)"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a class="btn-sm btn-danger" href="{{route('attendance.setoffday.destroy',$offday->id)}}"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="groupModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="padding-bottom:4px;">
                    <form action="{{route('attendance.setoffday.store')}}" method="POST">
                        @csrf
                        <div class="tab-pane fade show active" id="pills-general-grade" role="tabpanel"
                        aria-labelledby="pills-general-grade-tab">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Add Set Off Day</h4>
                                <p class="card-description"></p>
                            </div>
                        </div>
                        <div class="content-wrapper text-primary">
                            <table id="customTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <tr>
                                        <td><input type="text" name="title" class="form-control" placeholder="Enter Title Here"></td>
                                        <td><input type="date" value="{{date('Y-m-d')}}" name="start_date" class="form-control"></td>
                                        <td><input type="date" value="{{date('Y-m-d')}}" name="end_date" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="modal-footer" style="border-top: none;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
    
            </div>
        </div>
    </div>


    {{-- edit modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="groupModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="padding-bottom:4px;">
                    <form action="{{route('attendance.setoffday.update',1)}}" method="POST">
                        @csrf
                        <div class="tab-pane fade show active" id="pills-general-grade" role="tabpanel"
                        aria-labelledby="pills-general-grade-tab">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Add Set Off Day</h4>
                                <p class="card-description"></p>
                            </div>
                        </div>
                        <div class="content-wrapper text-primary">
                            <table id="customTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody id="t_body">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                        <div class="modal-footer" style="border-top: none;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
    
            </div>
        </div>
    </div>



@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });

        $("#addBtn").click(function(){
            $("#addModal").modal('show');
        });

        function editFunction(id){
            $.ajax({
                url     : '/attendance/set-off-day/edit/'+id,
                type    : 'GET',
                success : function(data){
                    console.log(data);
                    $("#t_body").empty();
                    var html = `
                            <tr>
                                <td><input type="text" name="title" value='${data.title}' class="form-control" placeholder="Enter Title Here"></td>
                                <td><input type="date" value="${data.start_date}" name="start_date" class="form-control"></td>
                                <td><input type="date" value="${data.end_date}" name="end_date" class="form-control"></td>

                                <input type="hidden" value="${data.id}" name="id" class="form-control">
                            </tr>
                    `;

                    $("#t_body").html(html);
                    $("#editModal").modal('show');
                }
            });
        }



        $('.setting').closest('li').addClass('custom_nav');
        $("#setting-item").removeClass('d-none');
        $('.offdays').addClass('custom_nav');
    </script>
@endpush
