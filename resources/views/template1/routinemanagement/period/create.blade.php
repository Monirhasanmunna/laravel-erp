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
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Create Period</h4>
                        <a href="{{ route('routine.period.index') }}" class="btn btn-dark "><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                    <div class="card-body">
                       
                        <form action="{{isset($period)? route('routine.period.update',$period->id): route('routine.period.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control col-md-4  @error('name') is-invalid @enderror" value="{{@$period->name ?? old('name')}}" name="name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                @if (@$period)
                                    Update
                                @else
                                    Submit
                                @endif
                            </button>
                        </form>
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

        $(".deleteBtn").click(function() {
            $(".deleteForm").submit();
        });
    </script>
@endpush
