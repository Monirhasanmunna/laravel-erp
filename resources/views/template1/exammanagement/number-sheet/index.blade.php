@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.exammanagement.topmenu_exammanagement')

        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Number Sheet</p>
                    </div>
                    <div class="card-body">
                        <form id="sheet-form">
                            @include('custom-blade.search-student3')
                        </form>
                    </div>
                </div>
            </div>

            <div class="card new-table d-none" id="sub-card">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Subject List</p>
                    </div>
                    <div class="card-body">
                        <form id="sub-form">
                            <input type="hidden" name="student_info" id="student_info">
                            <table class="table table-bordered" id="subject-list">
                                {{-- Load Data From JQ --}}
                            </table>
                        </form>
                    </div>
                </div>
            </div>

         
            <div id="loader" class="d-flex justify-content-center m-2">

            </div>

            <div class="card new-table d-none" id="std-card">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Student List</p>
                        <a href="#" class="btn btn-success"><i class="fa fa-print"></i>Print</a>
                    </div>
                    <div class="card-body student-list">
                       
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

            //sheet form submit
            $('#sheet-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = "{{ route('exam-management.number-sheet.get-subjects')}}";

                $('#student_info').val(form.serialize());

                $.get(url,form.serialize(),function(data){

                    if(data.length > 0){
                        $('#sub-card').removeClass('d-none');
                        let mainDiv = "";
                        $.each(data,function(idx,chunk){
                            let childDiv = "";
                            $.each(chunk,function(index,value){
                                childDiv += `<td>
                                                <div class="form-check ml-3">
                                                    <input class="form-check-input subject_id" value="${value.id}" type="radio" name="subject_id" id="sub-radio-${value.id}">
                                                    <label class="form-check-label" for="sub-radio-${value.id}">
                                                        ${value.sub_name}
                                                    </label>
                                                </div>
                                            </td>`;
                            });
                            mainDiv +=  `<tr>${childDiv}</tr>`;
                        });
                        $('#subject-list').html(mainDiv);
                    }else{
                        $('#sub-card').addClass('d-none');
                        toastr.error("No Subject Found");
                    }
           
                });
            });

            //on change radio
            $(document).on("change",".subject_id",function(){

                let student_info = $("#student_info").val();
                let subject_id   = $(this).val();
                var url = "{{ route('exam-management.number-sheet.get-students')}}";
                $('#loader').html('<i class="fa-solid fa-spinner fa-spin"></i>');

                $.get(url,{student_info,subject_id},function(data){
                    $('#loader').html('');
                    if(data.students.length > 0){
                        $("#std-card").removeClass('d-none');

                        let thList = '';
                        $.each(data.mark_dists, function(i, v) {
                            thList +=
                                `<th>${v.sub_mark_dist_type.title} (${v.mark}-${v.pass_mark})</th>`;
                        });

                       //students loop
                       let tRow = '';
                        $.each(data.students, function(idx, val) {
                            let tbodyRow = '';

                            if (data.mark_dists != null) {
                                $.each(val.mark_dists, function(index, value) {

                                    tbodyRow += `<td>
                                                    <input type="hidden" name="mark_dist_details_id-${val.id}[]" value="${value.id}" >
                                                    <div style="height:38px"></div>
                                                </td>`;
                                });
                            } else {
                                tbodyRow += `<td>
                                                <h4 class="text-danger">Mark Dist Not Found</h4>
                                            </td>`;
                            }


                            tRow += `<tr class="tbody">
                                        <td>${idx+1}</td>
                                        <td>${val.id_no}</td>
                                        <td>${val.name}</td>
                                        <td>${val.roll_no}</td>
                                        ${tbodyRow}
                                    </tr>`;
                        });

                        let table = `<table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Student Id</th>
                                                <th>Student Name</th>
                                                <th>Roll No</th>
                                                ${thList}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tRow}
                                        </tbody>
                                    </table>`;
                        $('.student-list').removeClass('d-none');
                        $('.student-list').html(table);
                    }
                    else{
                        $("#std-card").addClass('d-none');
                        toastr.error("No Student Found");

                    }
                });
            });
        });
    </script>
@endpush
