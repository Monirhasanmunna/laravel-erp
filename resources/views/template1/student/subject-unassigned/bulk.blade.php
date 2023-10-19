@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.student.studentnav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h6 class="float-left">Bulk Subject Unassigned</h6>
                        <a href="{{route('student.subject-unassigned.index')}}" class="btn btn-dark float-right"><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                    <div class="card-body" id="search-inputs">
                        <form id="student-form" method="GET">
                            @include('custom-blade.search-student')
                        </form>
                    </div>
                </div>

                <div id="preload" class="mb-3 mt-3 d-none"  style="text-align: center"></div>

                <div class="card d-none" id="process-card">
                    <div class="card-body">
                        <form action="{{route('student.subject-unassigned.bulk-action')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6" >
                                <div class="card">
                                    <div class="card-header" style="height: 45px">
                                        <p>Student List
                                            <span class="search-q"></span>
                                            </p>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-responsive" id="student-list-table">
                                            {{-- Data Load From JQ --}}
                                        </table>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header" style="height: 45px">
                                            <p>Select Subject Type</p>
                                            <div class="col-md-3">
                                                <select name="" id="subject_type" class="form-control">
                                                    @foreach($subjectTypes as $type)
                                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                           
                                            <table class="table table-bordered" id="subject-list-table">
                                                {{-- Load Data From JQ --}}
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger float-right mt-4"><i class="fa fa-arrow-down"></i>Unassigned</button>
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



            $(document).on("click","#checkAllStd",function() {
             
                let div = $('#student-list-table');
                let checkBoxs = div.find('input:checkbox');

                checkBoxs.each(function(index) {
                    let parent = $("#checkAllStd");
                    if(parent.prop('checked')){
                        $(this).prop('checked',true);
                    }
                    else{
                        $(this).prop('checked',false);
                    }
                });
            });

            $(document).on("click","#checkAllSub",function() {
                let div = $('#subject-list-table');
                let checkBoxs = div.find('input:checkbox');

                checkBoxs.each(function(index) {
                    let parent = $("#checkAllSub");
                    if(parent.prop('checked')){
                        $(this).prop('checked',true);
                    }
                    else{
                        $(this).prop('checked',false);
                    }
                });
            });

            function showSearchQuery(){

                    var div = $('#search-inputs');
                    var selectElements = div.find('select');

                    let searchText = "";
                    selectElements.each(function(index) {
                        var selectedOption = $(this).find('option:selected');
                        var isLastElement = index == selectElements.length -1;
                        if (isLastElement) {
                            searchText += `${selectedOption.text()}`;
                        }
                        else{
                            searchText += `${selectedOption.text()}-`;
                        }
                    });
                    $('.search-q').html(`(${searchText})`);
            }

            $('#student-form').submit(function(e) {
                e.preventDefault();
                showSearchQuery();
                var form = $(this);
                var url  = "{{ route('student.subject-unassigned.get-students') }}";
                $('#preload').removeClass('d-none');
                $('#preload').html(`<i class="fa fa-spinner fa-spin"></i>`);

                $.get(url,form.serialize(),function(data){
                    $('#preload').html("");
                    $('#process-card').removeClass('d-none');
                    
                    let mainDiv = "";
                    $.each(data,function(idx,chunk){
                        let childDiv = "";
                        $.each(chunk,function(index,student){
                            childDiv += `<td>
                                            <div class="form-check ml-3">
                                                <input class="form-check-input" type="checkbox" name="student_id[]" checked value="${student.id}" id="flexCheckDefault">
                                                <label class="form-check-label">${student.name} (${student.roll_no})</label>
                                            </div>
                                         </td>`;
                        });
                        mainDiv +=  `<tr>${childDiv}</tr>`;
                    });

                    let trHtml = `<tr>
                                    <td colspan="4">
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="checkAllStd" checked>
                                            <label class="form-check-label" for="checkAllStd">
                                                Select All
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                ${mainDiv}`;

                    $('#student-list-table').html(trHtml);

                });

                //get subjects
                let type_id = $("#subject_type").val();
                getSubjects(type_id);
            });

            //type id change
            $('#subject_type').change(function(){
                let type_id = $(this).val();
                getSubjects(type_id);
            });

            //get subjects
        

            function getSubjects(type_id){
                let class_id = $('#class_id').val(); 
                var url  = "{{ route('student.subject-unassigned.get-subjects') }}";

                $.get(url,{class_id,type_id},function(data){

                    let mainDiv = "";
                    $.each(data,function(idx,chunk){
                        let childDiv = "";
                        $.each(chunk,function(index,classSub){
                            childDiv += `<td>
                                            <div class="form-check ml-3">
                                                <input class="form-check-input" name="class_sub_id[]" type="checkbox" value="${classSub.id}" id="flexCheckDefault">
                                                <label class="form-check-label">${classSub.subject.sub_name} (${classSub.subject.sub_code})</label>
                                            </div>
                                         </td>`;
                        });
                        mainDiv +=  `<tr>${childDiv}</tr>`;
                    });

                    let trHtml = `  <tr>
                                        <td colspan="4">
                                            <div class="form-check ml-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkAllSub">
                                                <label class="form-check-label" for="checkAllSub">
                                                    Select All
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    ${mainDiv}`;

                    $('#subject-list-table').html(trHtml);
                });
            }
        });
    </script>
@endpush
