@extends('admin.layouts.app')
@section('content')

<div class="main-panel" id="marks-id">
    @include($adminTemplate.'.student.studentnav')
    <div class="card new-table">
        <div class="card-header">
            <h6 style="color: #000000">Student's Overview Report</h6>
            <div class="d-flex">
                <div id="pdf-btn" class="pr-3 pt-1">
                    
                </div>
                <select class="form-control" name="session_id" id="session_id3">
                    @foreach (@$sessions as $session)
                        <option value="{{@$session->id}}" {{@$currentYear == $session->title? 'selected':'' }}>{{@$session->title}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        
        <div class="card-body">
            <div class="row p-2">
                <table class="table table-bordered" id="fees-type-table">
                    <thead>
                    <tr>
                        <th class="text-center">SL</th>
                        <th class="text-center">Class</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Group</th>
                        <th class="text-center">Male</th>
                        <th class="text-center">Female</th>
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>

                    <tbody id="overview-tbody">

                    </tbody>
                    
                </table>
                
            </div>
            <div id="preload" style="margin-top: 10px">

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

    var loader = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="31px" height="31px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <circle cx="50" cy="50" fill="none" stroke="#e15b64" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                        </circle>
                        </svg>`;


       let session = $('#session_id3').val();
       let url = `/student/report/get-report/${session}`;
       $('#preload').html(loader);
       $.ajax({
            type : 'Get',
            url   : url,
            success : (data) => {
                console.log(data);
                getStudentInfoBySession(data.classes,session,data);
            }
       });

    $("#session_id3").change(function(){
       let session = $(this).val();
       let url = `/student/report/get-report/${session}`;
       $.ajax({
            type : 'Get',
            url   : url,
            success : (data) => {
                getStudentInfoBySession(data.classes,session,data);
            }
       });
    });

    
    function getStudentInfoBySession(classes,session,data){
        
        $("#overview-tbody").empty();
        $("#pdf-btn").empty();
        let html = '';
        if(classes.length != 0){

            $.each(classes,function(index,value){
                $.each(value.shifts,function(i,shift){
                    $.each(shift.sections,function(i,section){
                        $.each(shift.groups,function(i,v){
                                html += `
                                    <tr>
                                        <td class="text-center">${i+1}</td>
                                        <td class="text-center">${value.name}-${shift.name}-${section.name}</td>
                                        <td class="text-center">${categoryList(value).category}</td>
                                        <td class="text-center">${v.name}</td>
                                        <td class="text-center">${maleStudentCount(v.students).male}</td>
                                        <td class="text-center">${maleStudentCount(v.students).female}</td>
                                        <td class="text-center">${v.students.length}</td>
                                    </tr>
                                    `;

                                maleStudentCount(v.students);
                            });
                    });
                });
            });

            html += `
            <tr>
                <td colspan="4" class='text-center'>Total =</td>
                <td class='text-center' style='font-size:16px !important;font-weight:bold'>${data.totalMale}</td>
                <td class='text-center' style='font-size:16px !important;font-weight:bold'>${data.totalFemale}</td>
                <td class='text-center' style='font-size:16px !important;font-weight:bold'>${data.total}</td>
            </tr>
            
        `;
        $('#preload').html('');
        $("#pdf-btn").html(`<a style="width: 150px;" href="/student/report/pdf-export/${session}" id="downloadBtn" class="btn btn-sm btn-primary"><i class="fa-solid fa-file-pdf"></i>Print</a>`);
        }else{
            html = '<tr><td colspan = "7" class="text-center">No data found</td></tr>'
        }


        $("#overview-tbody").html(html);
    }


    function categoryList(value){
        var cat = '';
        $.each(value.categories,function(i,category){
            cat += `<span>${category.name}</span>, `;
        });

        return {category : cat};
    }


    function maleStudentCount(students){
        let male = [];
        $.each(students,function(i,v){
            if(v.gender == 'Male'){
                male.push(v);
            }
        });

        let female = [];
        $.each(students,function(i,v){
            if(v.gender == 'Female'){
                female.push(v);
            }
        });

        return {male:male.length,female:female.length}
    }

    
</script>
@endpush
