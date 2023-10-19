@extends('admin.layouts.app')

@push('css')
    <style>
        .table thead th {
            vertical-align: bottom!important;
            border-bottom: 2px solid #dee2e6!important;
         
            border-top: 1px solid #dee2e6!important;
            display: table-cell!important;
        }
    </style>
@endpush

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.routinemanagement.routineNav')
        <form action="{{route('routine.time-setting.store')}}" method="POST">
            @csrf
            <div>
                <div class="card new-table">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Period Time Setting</h4>
                            <a href="{{route('routine.time-setting.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                        <div class="card-body">
                            @include('custom-blade.time-setting-search')
                        </div>
                    </div>
                </div>

                <div class="card new-table">
                    <div class="card-header">
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Setup Period Time</h4>
                        {{--                    <a href="{{route('routine.time-setting.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>--}}
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="30%">Period Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select name="period_id[]" id="" class="form-control" required>
                                        <option value="" disabled selected>Select Period</option>
                                        @foreach ($periods as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="time" name="start_time[]" value="10:30" class="form-control" id="" required>
                                </td>
                                <td>
                                    <input type="time" name="end_time[]" value="12:30" class="form-control" id="" required>
                                </td>
                                <td style="text-align: center">
                                    <a href="" id="plus-btn" class="btn btn-dark"><i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
<script src="{{ asset('assets/vendors/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#customTable').DataTable();
        $(".chosen-select").chosen();
    });

    $(".deleteBtn").click(function(){
        $(".deleteForm").submit();
    });

    $(document).on("click",".minus-btn", function(e){
        e.preventDefault()
        let $this = $(this);

        $this.closest('tr').remove();
    });

    $('#plus-btn').click(function (e){
        e.preventDefault()
        let html = `   <tr>
                            <td>
                                <select name="period_id[]" id="" class="form-control" required>
                                    <option value="" disabled>Select Period</option>
                                    @foreach ($periods as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="time" name="start_time[]" value="10:30" class="form-control" id="" required>
                            </td>
                            <td>
                                <input type="time" name="end_time[]" value="12:30" class="form-control" id="" required>
                            </td>
                            <td style="text-align: center">
                                <a href="" id="minus-btn" class="btn btn-danger minus-btn"><i class="fa fa-minus"></i></a>
                            </td>
                        </tr>`;
        $('tbody').append(html);

    });



</script>
@endpush
