@extends('admin.layouts.app')

@push('css')
@endpush

@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.routinemanagement.routineNav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <p class="card-title text-dark">Time Setting Edit
                                <code>
                                    ({{ $timeSetting->class->name }})
                                </code>
                            </p>
                        </div>
                        <a href="{{ route('routine.time-setting.index') }}" class="btn btn-dark"><i
                                class="fa fa-arrow-left"></i>Back</a>
                    </div>

                    <form action="{{ route('routine.time-setting.update', $timeSetting->id) }}" method="POST">
                        @csrf
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Period Name</th>
                                        <th>Start TIme</th>
                                        <th>End Time</th>
                                        <th style="text-align: center">
                                            <a href="" id="plus-btn" class="btn btn-dark"><i
                                                    class="fa fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timeSetting->details as $item)
                                        <tr>
                                            <td>
                                                <select name="period_id[]" id="" class="form-control" required>
                                                    <option value="" disabled selected>Select Period</option>
                                                    @foreach ($periods as $period)
                                                        <option value="{{$period->id}}" {{@$item->period_id == $period->id? 'selected':''}}>{{$period->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="time" name="start_time[]" value="{{ $item->start_time }}"
                                                    class="form-control" id="" required>
                                            </td>
                                            <td>
                                                <input type="time" name="end_time[]" value="{{ $item->end_time }}"
                                                    class="form-control" id="" required>
                                            </td>
                                            <td style="text-align: center">
                                                <a href="" class="btn btn-danger minus-btn"><i
                                                        class="fa fa-minus"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Update</button>

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

            $('#plus-btn').click(function(e) {
                e.preventDefault()
                let html = `   <tr>
                                    <td>
                                        <select name="period_id[]" id="" class="form-control" required>
                                            <option value="" disabled selected>Select Period</option>
                                            @foreach ($periods as $period)
                                                <option value="{{$period->id}}" {{@$item->period_id == $period->id? 'selected':''}}>{{$period->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="time" name="start_time[]"  class="form-control" id="" required>
                                    </td>
                                    <td>
                                        <input type="time" name="end_time[]"  class="form-control" id="" required>
                                    </td>
                                    <td style="text-align: center">
                                        <a href="" id="minus-btn" class="btn btn-danger minus-btn"><i class="fa fa-minus"></i></a>
                                    </td>
                               </tr>`;
                $('tbody').append(html);

            });

            $(document).on("click", ".minus-btn", function(e) {
                e.preventDefault()
                let $this = $(this);

                $this.closest('tr').remove();
            });
        });
    </script>
@endpush
