@extends('admin.layouts.app')
@push('css')
    <style>
        tr {
            height: 40px;
            padding-top: 0px;
            padding-bottom: 0px;
        }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.software-settings.software-settings-nav')
        <div>
            <div class="card new-table">
                <div class="card-header">
                    <p>Recycle Bin</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label for="">Select Model</label>
                                <select name="model_name" id="model_name" class="form-control">
                                    <option value="">Select Model</option>
                                    <option value="\App\Models\Student">Student</option>
                                    <option value="\App\Models\Teacher">Teacher</option>
                                </select>
                            </div>
                            <div class="col-md-3" style="margin-top:35px">
                                <a href="" id="processBtn" class="btn btn-primary"><i
                                        class="fa fa-arrow-right"></i>Process</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card new-table">
                <div class="card-body" id="item-list">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {


            $('#processBtn').click(function(e) {
                e.preventDefault();
                let modelName = $('#model_name').val();

                $.get("{{ route('software-settings.recycle-bin.get-data') }}", {
                    modelName
                }, function(data) {

                    let tBodyRow = '';
                    let tHeadRow = '';
                    
                    if(data.length == 0){
                        toastr.error("No Data Found");
                    }

                    $.each(data, function(idx, val) {
                        let td = '';
                        var th = '';
                        let objKeys = Object.keys(val);

                        $.each(objKeys, function(index, value) {
                            td += `<td>${val[value]}</td>`;
                            th +=
                                `<th>${value.includes('_')? value.replace('_',' '):value}</th>`;
                        });

                        tHeadRow = `<tr>
                                        ${th}
                                        <th>Action</th>
                                    </tr>
                                    `;

                        tBodyRow += `<tr>
                                        ${td}
                                        <td>
                                            <a href="" data-model="${modelName}" data-id="${val.id}" id="restore" class="btn btn-info"><i class="fa fa-trash-restore"></i></a>
                                        </td>
                                    </tr>
                                    `;
                    });

                    let table = ` <table id="customTable" class="table">
                                        <thead>
                                            ${tHeadRow}
                                        </thead>
                                        <tbody>
                                            ${tBodyRow}
                                        </tbody>
                                    </table>`;

                    $('#item-list').html(table);
                    $('#customTable').DataTable();
                });
            });

            $(document).on('click', '#restore', function(e) {
                e.preventDefault();
                if (!confirm("Are you sure to restore?")) {
                    return false;
                }

                let id = $(this).data('id');
                let model = $(this).data('model');
                let $this = $(this);

                $.get("{{ route('software-settings.recycle-bin.restore-data') }}", {
                    id,
                    model
                }, function(data) {
                    if (data.status === 200) {
                        $this.closest('tr').remove();
                    }
                });
            });

        });
    </script>
@endpush
