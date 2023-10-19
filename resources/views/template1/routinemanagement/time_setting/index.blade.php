@extends('admin.layouts.app')

@push('css')
   
@endpush

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.routinemanagement.routineNav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Time Setting</h4>
                        </div>
                        <a href="{{route('routine.time-setting.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Add New</a>
                    </div>
                    <div class="card-body">

                            <table id="customTable" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Session</th>
                                        <th>Class</th>
                                        <th>Category</th>
                                        <th>Group</th>
                                        <th>Day</th>
                                        <th width='15px' class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timeSettings as $timeSetting)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$timeSetting['session']}}</td>
                                            <td>{{$timeSetting['class']}}</td>
                                            <td>{{$timeSetting['category']}}</td>
                                            <td>{{$timeSetting['group']}}</td>
                                            <td>{{$timeSetting['day']}}</td>
                                            <td>
                                                <a href="{{route('routine.time-setting.edit',$timeSetting['id'])}}" class="btn btn-info"><i class="fa fa-edit"></i></a>
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

    $(".deleteBtn").click(function(){
        $(".deleteForm").submit();
    });
</script>
@endpush
