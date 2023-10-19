@extends('teacherpanel.layout.app')
@push('css')
@endpush
@section('content')
    <div class="main-panel">
        @include('teacherpanel.account-management.nav-bar')
        <div class="card new-table">
            <div class="card-header">
                <h5 class="text-primary">Student Fees Collection</h5>
            </div>
            <div class="card-body">
                <form action="" id="payment-form" method="GET">
                    <div class="row py-2" id="all-row-py-2">
                        <div class="col-sm-2"> <label for="session_id"> Academic Year</label>
                            <select name="session_id" id="session_id" class="form-control">
                                <option value="">Select</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}">{{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2"> <label for="section_id">Section</label>
                            <select name="section_id" id="section_id" class="form-control chosen-select">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div class="col-sm-2"> <label for="category_id">Select Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Select Category</option>

                            </select>
                        </div>
                        <div class="col-sm-2"> <label for="group_id">Select Group</label>
                            <select name="group_id" id="group_id" class="form-control">
                                <option value="">Select Group</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label>Select Month</label>
                            <select name="month" class="form-control" id="month">
                                @foreach ($months as $key => $month)
                                    <option value="{{ $key }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary" style="margin-top: 35px"> <i
                                    class="fa fa-arrow-circle-right"></i> Process</button>
                        </div>

                    </div>

                </form>
            </div>
        </div>


        <div class="card new-table d-none " id="student-list-card">
            <div class="card-header">
                <p id="search-query"></p>
            </div>
            <div class="card-body">
                <table id="student-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id No</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Payable</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Advance</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th>Action</th>
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
            $('#total').html(0);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function searchQuery() {
                let session = $('#session_id option:selected').text();
                let section = $('#section_id option:selected').text();
                let category = $('#category_id option:selected').text();
                let group = $('#group_id option:selected').text();
                let month = $('#month option:selected').text();

                $('#search-query').html(
                    `<b>Session:</b>${session}, <b>Class:</b>${section}, <b>Category:</b>${category}, <b>Group:</b>${group}, <b>Month:</b>${month}`
                    );
            }

            $('#payment-form').submit(function(e) {

                e.preventDefault();
                let form = $(this);
                let url = "{{ route('teacherpanel.account-management.collection.get-payments') }}";

                searchQuery();
                $.ajax({
                    url: url,
                    type: "GET",
                    data: form.serialize(),
                    success: function(data) {
                        $("#student-list-card").removeClass('d-none');
                        let html = '';
                        $.each(data, function(idx, val) {

                            let btnLink = "/teacherpanel/account-management/collection/view-payments/" +
                                val.id + '/' + val.month;
                            let invBtnLink =
                                "/teacherpanel/account-management/collection/view-invoice/" + val.id +
                                '/' + val.month;

                            html += `<tr>
                                    <td>${val.id_no}</td>
                                    <td>${val.name}</td>
                                    <td>${val.roll_no}</td>
                                    <td>${val.total_payable}</td>
                                    <td>${val.paid_amount}</td>
                                    <td>${val.due}</td>
                                    <td>${val.advance}</td>
                                    <td>${val.discount}</td>
                                 
                                    <td>
                                        <div class="badge badge-info ${val.status == 'Partial Paid'? '':'d-none'}">${val.status}</div>
                                        <div class="badge badge-danger ${val.status == 'Unpaid'? '':'d-none'}">${val.status}</div>
                                        <div class="badge badge-success ${val.status == 'Paid'? '':'d-none'}">${val.status}</div>
                                        <div class="badge badge-warning ${val.status == 'Advance Paid'? '':'d-none'}">${val.status}</div>
                                    </td>
                                    <td>
                                        <a href="${invBtnLink}" class="btn btn-warning ${val.status == 'Not Paid'? 'd-none':''}" target="_blank"><i class="fa fa-list"></i>List</a>
                                        <a href="${btnLink}" class="btn btn-dark" target="_blank"><i class="fa fa-eye"></i>Collect</a>
                                    </td>
                                </tr>`;

                        });
                        $('tbody').html(html);
                        $("#student-table").DataTable();
                    }
                });

            });

            function getMonthName(monthNumber) {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString('en-US', {
                    month: 'long'
                });
            }




        });

        $('#session_id').change(function() {
            let session_id = $(this).val();
            // $('.chosen-select').chosen("destroy");

            $.get("{{ route('teacherpanel.account-management.collection.get-sections') }}", {
                    session_id
                },
                function(data) {
                    let html = '<option value="" selected hidden>Select Section</option>';

                    if (data) {
                        $.each(data.sections, function(i, item) {
                            html +=
                                `<option value="${item.id}">${item.class}-${item.shift}-${item.name}</option>`;
                        });
                    }

                    $('#section_id').html(html);
                    // $('.chosen-select').chosen();


                });
        });

        $('#section_id').change(function() {
            let section_id = $(this).val();
            let url = "{{ route('teacherpanel.account-management.collection.get-cat-groups') }}";

            $.get(url, {
                    section_id
                },
                function(data) {
                    let group = '<option value="">Select Group</option>';
                    let category = '<option value="">Select Category</option>';

                    data.category.map(function(val) {
                        category += `<option value='${val.id}'>${val.name}</option>`;
                    });
                    $('#category_id').html(category);

                    data.group.map(function(val) {
                        group += `<option  value='${val.id}'>${val.name}</option>`;
                    });
                    $('#group_id').html(group);
                });
        });
    </script>
@endpush
