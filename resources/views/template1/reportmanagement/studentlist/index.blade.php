@extends('admin.layouts.app')
@section('content')

<div class="main-panel" id="marks-id">
    @include($adminTemplate.'.reportmanagement.menu_reportmanagement')
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
                        <th class="text-center">Group</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Male</th>
                        <th class="text-center">Female</th>
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>
                    <tbody id="overview-tbody">

                    </tbody>
                </table>
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

       let session = $('#session_id3').val();
       let url = `/report-management/student-list/get-student-info/${session}`;
       $.ajax({
            type : 'Get',
            url   : url,
            data : session,
            success : (data) => {
                getStudentInfoBySession(data,session);
            }
       });

    $("#session_id3").change(function(){
       let session = $(this).val();
       let url = `/report-management/student-list/get-student-info/${session}`;
       $.ajax({
            type : 'Get',
            url   : url,
            data : session,
            success : (data) => {
                getStudentInfoBySession(data,session);
            }
       });
    });

    
    function getStudentInfoBySession(data,session){
        
        $("#overview-tbody").empty();
        $("#pdf-btn").empty();
        let html = '';
        if(data.length != 0){
            $.each(data,function(i,v){
                html += `
                    <tr>
                        <td class="text-center">${i+1}</td>
                        <td class="text-center">${v.className}</td>
                        <td class="text-center">${v.totalGroup}</td>
                        <td class="text-center">${v.totalCategory}</td>
                        <td class="text-center">${v.male}</td>
                        <td class="text-center">${v.female}</td>
                        <td class="text-center">${v.total}</td>
                    </tr>
                `;
            });
        $("#pdf-btn").html(`<a style="width: 150px;" href="/report-management/student-list/pdf-export/${session}" id="downloadBtn" class="btn btn-sm btn-primary"><i class="fa-solid fa-file-pdf"></i>Pdf Generates</a>`);
        }else{
            html = '<tr><td colspan = "7" class="text-center">No data found</td></tr>'
        }
        
        $("#overview-tbody").html(html);
    }

    
</script>
@endpush
