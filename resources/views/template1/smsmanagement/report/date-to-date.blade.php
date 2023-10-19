@extends('admin.layouts.app')
@push('css')
<style>
  .show-read-more .more-text{
        display: none;
    }
    table td {
        border: solid 1px #666;
        width: 110px;
        word-wrap: break-word;
      }
    .th-class{
        width:462px!important;
    }  
</style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.smsmanagement.topmenu_sms_management')
        <div class="card new-table">
            <div class="card-header">
                <div class="card-title float-left">
                    <h6 style="color: black">Date to Date Report</h6>
                </div>

            </div>
            <div class="card-body">
                <form id="date-range-form" method="GET">
                    <div class="form-row">
                        <div class="col-md-3">
                            <label for="">From Date</label>
                            <input type="date" name="from_date" value="{{ date('Y-m-d') }}" class="form-control"
                                id="">
                        </div>
                        <div class="col-md-3">
                            <label for="">To Date</label>
                            <input type="date" name="to_date" value="{{ date('Y-m-d') }}" class="form-control"
                                id="">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" style="margin-top: 35px" class="btn btn-primary"><i
                                    class="fa fa-arrow-right"></i>Process</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div id="loader" class="d-flex justify-content-center m-2">

        </div>

        <div class="card new-table d-none" id="list-card">
            <div class="card-body">
                <table class="table table-responsive" id="customTable">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Title</th>
                            <th id="th-content">Content</th>
                            <th>Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
           
            $('#customTable').on( 'draw.dt', function () {
                showContent();
            });

          
           

            $('#date-range-form').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                $('#loader').html('<i class="fa-solid fa-spinner fa-spin"></i>');

                let url = "{{ route('sms.date-to-date-report.get-reports') }}";

                $.get(url, form.serialize(), function(data) {
                    $('#loader').html('');
                    $('#list-card').removeClass('d-none');
                    $('#customTable').dataTable().fnDestroy();
                    let html = "";
                    if (data.length > 0) {
                        $.each(data, function(idx, val) {
                            html += `<tr>
                                    <td>${idx+1}</td>
                                    <td>${getDateStr(val.time)}</td>
                                    <td>${getTimeStr(val.time)}</td>
                                    <td>${val.title}</td>
                                    <td><p class="show-read-more">${val.description}</p></td>
                                    <td>${val.number}</td>
                                    <td>
                                        ${val.status == 1? "<div class='badge badge-success'>Sent</div>":"<div class='badge badge-danger'>Failed</div>"}
                                    </td>
                                </tr>`;
                        });
                    }
                    else{
                        $('#list-card').addClass('d-none');
                        toastr.error("No Data Found");
                    }

                    $('tbody').html(html);
                    $('#customTable').DataTable();
                    showContent();
                    $('#th-content').addClass('th-class');
                });
            });


            function showContent(){
                var maxLength = 65;
                $(".show-read-more").each(function(){
                    var myStr = $(this).text();
                    if($.trim(myStr).length > maxLength){
                        var newStr = myStr.substring(0, maxLength);
                        var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                        $(this).empty().html(newStr);
                        $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
                        $(this).append('<br><span class="more-text">' + removedStr + '</span>');
                    }
                });
                $(".read-more").click(function(){
                    $(this).siblings(".more-text").contents().unwrap();
                    $(this).remove();
                });
            }


            function getTimeStr(timestamp){
                let dateFormat = new Date(timestamp);
                let time = dateFormat.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
                return time;
            }

            function getDateStr(timestamp){
                let dateFormat = new Date(timestamp);
                let date = dateFormat.getDate()+" "+getMonthShortName(dateFormat.getMonth())+" "+dateFormat.getFullYear();
                return date;
            }

            function getMonthShortName(monthNo) {
                const date = new Date();
                date.setMonth(monthNo - 1);

                return date.toLocaleString('en-US', { month: 'short' });
            }
        });
    </script>
@endpush
