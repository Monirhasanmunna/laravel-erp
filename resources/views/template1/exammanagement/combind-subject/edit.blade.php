@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.exammanagement.topmenu_exammanagement')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Combind Subject Edit</p>
                        <div class="float-right">
                            <a href="{{ route('exam-management.setting.combind-subject.index') }}" class="btn btn-dark"><i
                                    class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('exam-management.setting.combind-subject.update',$combindSubject->id) }}" method="POST">
                            @csrf
                                <div class="form-row mt-2">
                                    <div class="col-md-3">
                                        <label for="">Select Subject</label>
                                        <select name="subject_1" class="form-control" id="">
                                            @foreach($classSubjects as $sub)
                                                <option value="{{$sub->id}}" {{$combindSubject->subject_1 == $sub->id? 'selected':''}}>{{$sub->subject->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Select Subject</label>
                                        <select name="subject_2" class="form-control" id="">
                                            @foreach($classSubjects as $sub)
                                                <option value="{{$sub->id}}" {{$combindSubject->subject_2 == $sub->id? 'selected':''}}>{{$sub->subject->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Update</button>
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
            $('#setting_menu').css('display', 'block');

        });
    </script>
@endpush
