@extends('admin.layouts.app')
@push('css')
<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>

<style>
    #container {
        width: 1000px;
        margin: 20px auto;
    }
    .ck-editor__editable[role="textbox"] {
        /* editing area */
        min-height: 200px;
    }
</style>
@endpush
@section('content')
    <div class="main-panel">
        <form action="{{ route('examroutine.update',$routine->id) }}" method="post" style="none;">
        @csrf

        <nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav navbar-nav-hover mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('classroutine.index') }}" id="nav-hov">
                                Class Routine
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('examroutine.index') }}" id="nav-hov">
                                Exam Routine
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('unc_message') }}" id="nav-hov">
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card new-table mb-3">

            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="row py-2">
                            <div class="col-sm-6"> <label> Academic Year</label>
                                <select name="academic_year_id" id="session_id" class="form-control" required>
                                    <option value="">select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option value="{{ @$academic_year->id }}" @selected(@$academic_year->id == @$routine->session_id)>{{ @$academic_year->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6"> <label>Class</label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    <option value="">select</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ @$class->id }}" @selected(@$class->id == $routine->ins_class_id)>{{ @$class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row py-2">
                            <div class="col-sm-6"> <label>Exam</label>
                                <select name="exam_id" id="exam_id" class="form-control">
                                    <option value="" selected hidden>select</option>
                                    @foreach ($exams as $exam)
                                      <option value="{{ @$exam->id }}" @selected(@$exam->id == $routine->exam_id)>{{ @$exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="col-sm-6"> <label>Start Time</label>
                                <input type="time" class="form-control" value="{{@$routine->start_time}}" name="st_time" id="st_time">
                            </div>
                        </div>
                    
                        <div class="row py-2">
                            <div class="col-sm-6"> <label>End Time</label>
                                <input type="time" class="form-control" value="{{@$routine->end_time ?? ''}}" name="ed_time" id="ed_time">
                            </div>
                            <div class="col-sm-6"> <label>Start Date</label>
                                <input type="date" class="form-control" value="{{@$routine->start_date}}" name="st_date" id="st_date">
                            </div>
                        </div>
                        <div class="row py-2">
                            <div class="col-sm-6"> <label>End Date</label>
                                <input type="date" class="form-control" value="{{@$routine->end_date}}" name="ed_date" id="ed_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



            <div id="data-card">
                <div class="card new-table">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Subject List</h4>
                            <select name="" id="sub_id" class="form-control col-md-2">
                                <option value="">Select Subject</option>
                                @foreach($classSubjects as $subject)
                                    <option value="{{$subject->id}}">{{$subject->subject->sub_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="card-body">

                            {{-- table-striped --}}
                            <div class="" id="subject_table">
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Room</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach (@$routine->classSubjects as $s)
                                        <tr>
                                            <td>
                                                <input type="hidden" class="form-control" value="{{@$s->pivot->class_subject_id}}" name="subject_id[]">
                                                <input type="text" value="{{@$s->subject->sub_name}}" class="form-control" readonly>
                                            </td>
                                            <td><input type="date" class="form-control" name="date[]" value="{{@$s->pivot->date}}" required></td>
                                            <td><input type="time" class="form-control start_time" value='{{@$s->pivot->start_time}}' name="start_time[]" required></td>
                                            <td><input type="time" class="form-control end_time" value='{{@$s->pivot->end_time}}' name="end_time[]" required></td>
                                            <td><input type="text" class="form-control" name="room[]" value="{{@$s->pivot->room}}"></td>
                                            <td class="text-center"><button class="btn btn-danger btn-class deleteRow" type="button">X</button></td>

                                      </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class=" mt-4 pt-0" id="instruction_table">
                                <label style="color: #878787"><strong>Instructions:</strong></label>

                                <div id="instruction_row">
                                    <textarea class="form-control mb-3" id="editor" placeholder="Enter instruction here" name="instruction">{{$routine->instruction ?? ''}}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i>Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script>

    ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .catch( error => {
        console.error( error );
    });

        $(document).ready(function() {
            $('#customTable').DataTable();

            var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;


            $("#st_time,#ed_time").change(function(){
                var start_time = $("#st_time").val();
                var end_time = $("#ed_time").val();

                $(".start_time").val(start_time);
                $(".end_time").val(end_time);
            });

            $(document).on('click','.deleteRow',function (e){
                e.preventDefault();
                let link = $(this).attr("href");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).closest('tr').remove();
                    }
                })
            });

            $('#sub_id').change(function (){
                let sub_id  = $(this).val();
                let SubName =$( "#sub_id option:selected" ).text();

                let html =  `<tr>
                                    <td>
                                    <input type="hidden" class="form-control" readonly name="subject_id[]" value="${sub_id}">
                                    <input type="text" class="form-control" readonly value="${SubName}">
                                    </td>
                                    <td><input type="date" class="form-control" value="{{date('Y-m-d')}}" name="date[]" required></td>
                                    <td><input type="time" class="form-control" value='' name="start_time[]" required></td>
                                    <td><input type="time" class="form-control" value='' name="end_time[]" required></td>
                                    <td><input type="text" class="form-control" name="room[]"></td>
                                    <td class="text-center"><button class="btn btn-danger btn-class deleteRow" type="button">X</button></td>

                                    <input type="hidden" name="class_id" value="{{$routine->ins_class_id}}">
                              </tr>`;
                $('tbody').append(html);
            });
        });

    </script>
@endpush
