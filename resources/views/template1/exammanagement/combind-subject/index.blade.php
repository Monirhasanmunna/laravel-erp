@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.exammanagement.topmenu_exammanagement')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Combind Subjects</p>
                        <div class="float-right">
                            <a href="{{route('exam-management.setting.combind-subject.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Create New</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="customTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Class</th>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($combinedSubjects as $key => $subject)
                                  <tr>
                                      <td>{{$loop->iteration}}</td>
                                      <td>{{$subject->class->name}}</td>
                                      <td>{{$subject->exam->name}}</td>
                                      <td>{{$subject->subject1->subject->sub_name}} + {{$subject->subject2->subject->sub_name}}</td>

                                      <td>
                                          <a href="{{route('exam-management.setting.combind-subject.edit',$subject->id)}}" class="btn btn-info"><i class="fa fa-edit"></i></a>
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
            $('#setting_menu').css('display','block')
        });
    </script>
@endpush
