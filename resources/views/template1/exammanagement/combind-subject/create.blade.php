@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.exammanagement.topmenu_exammanagement')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Combind Subjects (<code>Subjects for which marks are distributed will appear</code>)</p>
                        <div class="float-right">
                            <a href="{{ route('exam-management.setting.combind-subject.index') }}" class="btn btn-dark"><i
                                    class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="combind-form" action="">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label for="">Select Section</label>
                                    <select name="academic_year_id" id="session_id" class="form-control">
                                        <option value="">Select Session</option>
                                        @foreach ($academic_years as $academic_year)
                                            <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="class_id">Class</label>
                                    <select name="class_id" id="class_id" class="form-control class_id">
                                        <option value="">Select Class</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="exam_id">Exam</label>
                                    <select name="exam_id" id="exam_id" class="form-control exam_id">
                                        <option value="">Select Exam</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary" style="margin-top: 35px"><i class="fa fa-arrow-circle-right"></i> Process</button>
                                </div>
                            </div>

                        </form>
                        <div class="sub-div d-none" id="sub-div">
                            <form action="{{route('exam-management.setting.combind-subject.store')}}" method="post">
                                @csrf
                                <input type="hidden" name="ins_class_id" id="ins_class_id">
                                <input type="hidden" name="exam_id" id="exams_id">
                                <table class="table mt-4">
                                    <tbody>
                                    <tr>
                                        <td width="25%">
                                            <select name="subject_id1[]" id="subject_id" class="form-control subject_id-1" required>
                                                <option value="">Select Subject</option>
                                            </select>
                                        </td>
                                        <td width="25%">
                                            <select name="subject_id2[]" id="subject_id" class="form-control subject_id-1" required>
                                                <option value="">Select Subject</option>
                                            </select>
                                        </td>
                                        <td>
                                            <a href="" id="add-btn" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i>Submit</button>
                            </form>
                        </div>
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

            $('#session_id').change(function() {
                let session_id = $(this).val();
                $.get("{{ route('exam-management.get-class-exam') }}", {
                        session_id
                    },
                    function(data) {
                        let Classhtml = '<option>Select Class</option>';
                        let Examhtml = '<option>Select Exam</option>';

                        $.each(data.classes,function (idx,val){
                            Classhtml += `<option value="${val.id}">${val.name}</option>`;
                        });

                        $.each(data.exams,function (idx,val){
                            Examhtml += `<option value="${val.id}">${val.name}</option>`;
                        });


                        $('.class_id').html(Classhtml);
                        $('.exam_id').html(Examhtml);
                    });
            });

            //get subjects
            $('#combind-form').submit(function (e){
               e.preventDefault();
               let formData = $(this);
               let path = "{{route('get-subject-combined')}}";

               let class_id = $('#class_id').val();
               let exam_id = $('.exam_id').val();
                console.log(exam_id);
               $('#ins_class_id').val(class_id);
               $('#exams_id').val(exam_id);

                $.ajax({
                    type: 'GET',
                    url: path,
                    data: formData.serialize(),
                    contentType: false,
                    processData: false,
                    success:function (data){

                        if(data.length != 0){
                            $('#sub-div').removeClass('d-none');

                            let html = `<option value="">Select Subject</option>`;

                            $.each(data,function (idx,val){
                                html += `<option value="${val.id}">${val.subject.sub_name}</option>`;
                            });

                            $('.subject_id-1').html(html);
                        }
                        else{
                            $('#sub-div').addClass('d-none');
                            toastr.error("No Subject Found");
                        }

                    }
                });
            });

            let index = 1;
            //increment row
            $('#add-btn').click(function (e){
                e.preventDefault();
                index++;
                let html = ` <tr>
                                <td width="25%">
                                    <select name="subject_id1[]" id="subject_id" class="form-control subject_id-${index}" required>
                                        <option value="">Select Subject</option>
                                    </select>
                                </td>
                                <td width="25%">
                                    <select name="subject_id2[]" id="subject_id" class="form-control subject_id-${index}" required>
                                        <option value="">Select Subject</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="" id="minus-btn" class="btn btn-danger minus-btn"><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>`;
                $('tbody').append(html);
                appendSubject(index);
            });


            function appendSubject(index){
                let class_id = $('#class_id').val();
                let path = "{{route('get-subject-combined')}}";

                let html = `<option value="">Select Subject</option>`;
                $.get(path,{class_id},function (data){
                    $.each(data,function (idx,val){
                        html += `<option value="${val.id}">${val.subject.sub_name}</option>`;
                    });
                    $(`.subject_id-${index}`).html(html);
                });
            }

            $(document).on('click','.minus-btn',function (e){
                e.preventDefault();
                let $this = $(this);
                $this.closest('tr').remove();
            });


        });
    </script>
@endpush
