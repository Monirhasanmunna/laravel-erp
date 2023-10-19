@extends('admin.layouts.app')

@push('css')
    <style>
        tr {
            height: 30px;
        }
    </style>
@endpush

@section('content')
    <div class="main-panel" id="exam-routine">
        @include($adminTemplate . '.routinemanagement.routineNav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                     
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Period List</h4>
                     
                        <a href="{{ route('routine.period.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Create</a>
                    </div>
                    <div class="card-body">
                        <table id="customTable" class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($periods as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href="{{route('routine.period.edit',$item->id)}}" class="btn btn-info"><i class="fa fa-edit"></i></a>
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
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });
    </script>
@endpush
