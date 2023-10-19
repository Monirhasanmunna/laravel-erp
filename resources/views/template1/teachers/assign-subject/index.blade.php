@extends('admin.layouts.app')
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-results__option{
            background: white;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.teachers.teachernav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6>Assign Teacher Subjects</h6>
                        <a href="{{route('assign_teacher.subject-assign')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Assign Subject</a>
                    </div>
                    <div class="card-body">
                        <table class="table" id="customTable"> 
                            <thead>
                                <tr>
                                    <td>SL</td>
                                    <td>Teacher</td>
                                    <td>Subjects</td>
                                </tr>
                            </thead>
                            <tbody>
                               
                               
                                @foreach($teachers as $key => $teacher)
                                    <tr> 
                                        <td rowspan="{{count($teacher['subjects']) + 1}}">{{$loop->iteration}}</td>
                                        <td rowspan="{{count($teacher['subjects']) + 1}}">{{$teacher['name']}}</td>
                                        <td></td> 
                                    </tr>
                                    @foreach ($teacher['subjects'] as $key => $subject)
                                        <tr> 
                                            <td style="padding: 7px!important;">{{$key}} ({{$subject}})</td> 
                                        </tr> 
                                    @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            // $('#customTable').DataTable();
          


            //GET SUBJECTS
            $('#session_id').change(function(){

                let session_id = $(this).val();

                $.get("{{route('student.get-classes')}}",
                {
                    session_id
                },
                function(data){
                    let html = '<option>Select Class</option>';
                    data.classes.map(function(item){
                    html += `<option value="${item.id}">${item.name}</option>`;
                    });

                    $('.class_id').html(html);
                });
            });

                          

         


        });
    </script>
@endpush
