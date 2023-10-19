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
                        <div class="card-title float-left">
                            <h6 style="color: black">Teacher List (All Teacher)</h6>
                        </div>
                        <div class="teachers">
                        <a href="{{route('teacher.order')}}" class="btn btn-primary mr-2">Set Order</a>    
                            <a href="{{ route('teacher.export') }}" class="btn btn-success float-right"><i class="fa fa-file-excel"></i>
                        Export Excell</a>
                        <a href="javascript:void(0)" type="submit" class="btn btn-primary float-right mb-3 mr-2 printBtn"><i class="fa-solid fa-file-pdf"></i>Print</a>
                        {{-- <a href="javascript:void(0)" type="submit" class="btn btn-primary float-right mb-3 mr-2 pdfBtn"><i class="fa-solid fa-file-pdf"></i>Export Pdf</a> --}}
                        <a href="{{ route('teacher.upload.create') }}" class="btn btn-secondary mr-2 float-right"><i class="fa fa-arrow-circle-up"></i>Upload Teacher</a>
                        <a href="{{ route('teacher.create') }}" class="btn btn-primary mr-2 float-right" ><i class="fa fa-plus"></i> Add New</a>
                        </div>

                        <form style="display:none;" id="printForm" action="{{route('teacher.exportprint')}}" method="POST">
                            @csrf
                        </form>

                        <form style="display:none;" id="pdfForm" action="{{route('teacher.exportpdf')}}" method="POST">
                            @csrf
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="customTable" class="table table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center;" width='10%'>SL</th>
                                    <th style="text-align:center;">Image</th>
                                    <th style="text-align:center;" width='15%'>Id No</th>
                                    <th style="text-align:center;">Name</th>
                                    <th style="text-align:center;">Gender</th>
                                    <th style="text-align:center;">Branch</th>
                                    <th style="text-align:center;">Designation</th>
                                    <th style="text-align:center;" width='15%'>Unique ID</th>
                                    <th style="text-align:center;">JOINING</th>
                                    <th style="text-align:center;">Mobile number</th>
                                    <th style="text-align:center;">B/G</th>
                                    <th style="text-align:center;">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $i=0; 
                                @endphp
                                @foreach ($teachers as $teacher)
                                    <tr>
                                        <td class="text-center" width='10%'>{{ ++$i }}</td>
                                        <td style="width:10%; text-align:center;">
                                            <img style="width: 40px!important;height: 40px!important;" src="{{@$teacher->photo ?Config::get('app.s3_url').$teacher->photo:Helper::default_image()}}" alt="">
                                        </td>
                                        <td style="text-align:center;" width='15%'>{{ $teacher->id_no }}</td>
                                        <td style="width:80%">{{ $teacher->name }}</td>
                                        <td style="text-align:center;">{{ $teacher->gender }}</td>
                                        <td style="text-align:center;">{{ @$teacher->branch->title }}</td>
                                        <td style="text-align:center;">{{ @$teacher->designation->title }}</td>
                                        <td style="text-align:center;" width='15%'>{{ $teacher->uuid }}</td>
                                        <td style="text-align:center;">{{ @$teacher->joining_date }}</td>
                                        <td>{{ $teacher->mobile_number }}</td>
                                        <td>{{ $teacher->blood_group }}</td>
                                        <td>
                                            <a href="{{ route('teacher.edit',['id' => $teacher->id]) }}" class="btn-sm btn-primary p-2"><i style="margin-left: 0.3125rem;" class="fa-solid fa-pen-to-square"></i></a>
                                            @if (!$teacher->teacherUser)
                                                <a href="{{route('teacher.create.user',$teacher->id)}}" class="btn-sm btn-warning p-2"><i style="margin-left: 0.3125rem;" class="fa fa-user-plus"></i></a>
                                            @else
                                                <a class="btn-sm btn-success p-2" href="javascript:void(0)"><i style="margin-left: 0.3125rem;" class="fa-solid fa-square-check"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        $(".printBtn").click(function(){
             $("#printForm").submit();
        });

        $(".pdfBtn").click(function(){
             $("#pdfForm").submit();
        });
    </script>
@endpush
