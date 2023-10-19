@extends('parentportal.layout.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card new-table">
            <div class="card-header">
                <div class="card-title float-left">
                    <h6 style="color: black">Leave Applications</h6>
                </div>
                <div class="btn-wrapper">
                    <a href="{{route('studentleaveapplication.create')}}" class="btn btn-primary mr-2 float-right"><i
                            class="fa fa-plus"></i>Add New</a>
                </div>

            </div>
            <div class="card-body">
                <table id="customTable" class="table table-responsive" style="width:100%;">
                    <thead>
                        <tr>
                            <th> Date </th>
                            <th> Students ID </th>
                            <th> Students Name </th>
                            <th> Roll </th>
                            <th> Class/Section/Group</th>
                            <th> Application </th>
                            <th> Leave Date </th>
                            <th> Total Day </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $key => $application)
                            <tr>
                                <td>{{date('d-M-y',strtotime($application->created_at))}}</td>
                                <td>{{$application->source->student->id_no}}</td>
                                <td>{{$application->source->student->name}}</td>
                                <td>{{$application->source->student->roll_no}}</td>
                                <td>{{@$application->source->student->ins_class->name}} - {{$application->source->student->section->name}} - {{@$application->source->student->group->name}}</td>
                                <td>{{Str::words($application->application,5,'..')}}</td>
                                <td>
                                    {{date('d-M-y',strtotime($application->to_date)).' to '.date('d-M-y',strtotime($application->from_date))}}
                                </td>
                                <td>{{$application->total_day}}</td>
                                <td>
                                    @if ($application->status == 'approve')
                                        <span class="badge badge-sm badge-primary">Approved</span>
                                    @elseif($application->status == 'pending')
                                        <span class="badge badge-sm badge-info">Pending</span>
                                    @else
                                        <span class="badge badge-sm badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->status == 'pending')
                                        <a class=" btn-sm btn-primary" href="{{route('studentleaveapplication.edit',$application->id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a class=" btn-sm btn-danger" href="{{route('studentleaveapplication.destory',$application->id)}}"><i class="fa-solid fa-trash"></i></a>
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
@endsection

@push('javascript')
<script>
    $(document).ready(function () {
        $('#customTable').DataTable({
            "scrollX": true
        });
    });
</script>
@endpush
