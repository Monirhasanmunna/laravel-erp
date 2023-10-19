@extends('admin.layouts.app')
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css"/>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.teachers.teachernav')
        <div>
            <div class="card new-table">
                <div class="card">

                    <div class="card-header">
                        <h6 class="float-left" style="color: black">Teacher List (All Teacher)</h6>
                        <a href="{{route('teacher.index')}}" class="btn btn-dark float-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{route('teacher.update-order')}}" method="POST">
                            @csrf
                            <table class="table table-bordered table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width='10%'>SL</th>
                                        <th>Image</th>
                                        <th width='15%'>Id No</th>
                                        <th>Name</th>
                                        <th>Branch</th>
                                        <th>Designation</th>
                                        <th>Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teachers as $teacher)
                                        <input type="hidden" name="teacher_ids[]" value="{{$teacher->id}}">
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td style="width:10%;">
                                                @if($teacher->photo == 'default.png')
                                                    <img style="width: 40px!important;height: 40px!important;" src="{{Helper::default_image()}}" alt="">
                                                @else
                                                    <img style="width: 40px!important;height: 40px!important;" src="{{@$teacher->photo ?Config::get('app.s3_url').$teacher->photo:Helper::default_image()}}" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $teacher->id_no }}</td>
                                            <td>{{ $teacher->name }}</td>
                                            <td>{{ @$teacher->branch->title }}</td>
                                            <td>{{ @$teacher->designation->title }}</td>
                                            <td>
                                                <input type="number" value="{{$teacher->order}}" name="order[]" class="form-control" id="">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2 float-right"><i class="fa fa-save"></i>Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });

     
    </script>
@endpush
