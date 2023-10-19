@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css"
        integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .prev {
            color: red;
            top: 38%;
            left: -2em;
            font-size: 1.5em;

            :hover {
                cursor: pointer;
                color: black;
            }
        }
    </style>
@endpush
@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate . '.accountsmanagement.topmenu_accountsmanagement')
        <div>
            <div class="card new-table">
                {{-- today start --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title float-left">
                            <h6 style="color: #000000">Accounts Dashboard (Today Report)</h6>
                        </div>
                        <a href="{{ route('accountsmanagement.overview') }}" class="btn btn-primary"><i
                                class="fa fa-credit-card"></i>Accounts Overview</a>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12  grid-margin stretch-card" id="today-card">
                              
                            </div>
                        </div>
                    </div>
                </div>
                {{-- today end --}}


                {{-- monthly start --}}
                <div class="card">
                    <div class="card-header">
                        <h6 style="color: #000000">Total Monthly Report <span id="month-year-name"></span></h6>
                        <div class="d-flex">
                            <select class="form-control" name="session_id" id="session_id">
                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}"
                                        {{ $currentYear == $session->title ? 'selected' : '' }}>{{ $session->title }}</option>
                                @endforeach
                            </select>
                            <select class="form-control ml-2" name="month" id="month">
                                @foreach ($months as $key => $month)
                                    <option value="{{ $key }}" {{ $currentMonth == $key ? 'selected' : '' }}>
                                        {{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12  grid-margin stretch-card" id="month-card">

                            </div>
                        </div>
                    </div>
                </div>
                {{-- monthly end --}}

                {{-- session start --}}
                <div class="card">
                    <div class="card-header">
                        <h6 style="color: #000000">Total Yearly Report <span id="session-name"></span></h6>
                        <div class="d-flex">
                            <select class="form-control" name="session_id" id="session_id2">
                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}"
                                        {{ $currentYear == $session->title ? 'selected' : '' }}>{{ $session->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12  grid-margin stretch-card" id="session-card">

                            </div>

                        </div>
                    </div>
                </div>
                {{-- session end --}}

                {{-- overview start --}}
                <div class="card">
                    <div class="card-header">
                        <h6 style="color: #000000">Accounts Overview Report</h6>
                        <div class="d-flex">

                            <select class="form-control" name="session_id" id="session_id3">
                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}"
                                        {{ $currentYear == $session->title ? 'selected' : '' }}>{{ $session->title }}
                                    </option>
                                @endforeach
                            </select>
                            <select class="form-control ml-2" name="month" id="month3">
                                @foreach ($months as $key => $month)
                                    <option value="{{ $key }}" {{ $currentMonth == $key ? 'selected' : '' }}>
                                        {{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered" id="fees-type-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Fees Type</th>
                                        <th>Payable</th>
                                        <th>Collection</th>
                                        <th>Dues</th>
                                        <th>Scholarship</th>
                                        <th>Discount</th>
                                        <th>Fine</th>

                                    </tr>
                                </thead>
                                <tbody id="overview-tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- overview end --}}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {

            //slick slider
            function destroySlider(target) {
                if ($(target).hasClass('slick-initialized')) {
                    $(target).slick('unslick');
                }
            }

            function applySlider(target) {
                $(target).slick({
                    infinite: false,
                    dots: false,
                    slidesToShow: 6,
                    slidesToScroll: 2,
                    prevArrow: false,
                    nextArrow: false
                });
            }

            //get accounts today data
            getAccountsDataToday();

            function getAccountsDataToday() {

                $.get("{{ route('get-account-dashboard-data-today') }}", function(data) {
                    console.log(data);
                    let todayHtml = "";
                    let index = 0;
                    $.each(data, function(idx, val) {
                        index++;
                        todayHtml += `<div><div class="card ${index > 1? "ml-2":""} mb-2 mt-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-9">
                                                            <div class="d-flex align-items-center align-self-start">
                                                                <h3 class="mb-0 text-black">
                                                                    ${val}
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="icon icon-box-success ">
                                                                <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6 style="text-transform:capitalize" class="text-muted font-weight-normal">${'Today '+idx.replace('_',' ')}</h6>
                                                </div>
                                            </div></div>`;
                    });
                    $('#today-card').html(todayHtml);
                    //session slick
                    applySlider('#today-card');
                });
            }



            //Change Month & Session Name
            let monthName = $("#month option:selected").text();
            let sessionName = $("#session_id option:selected").text();

            changeMonthSessioName(monthName, sessionName);

            function changeMonthSessioName(month, session) {
                $("#month-year-name").html(`(${month}-${session})`);
            }

            let session2Name = $("#session_id2 option:selected").text();
            changeSessionName(session2Name);

            function changeSessionName(session) {
                $("#session-name").html(`(${session})`);
            }


            let session_id = $("#session_id").val();
            let month = $("#month").val();

            getAccountsDataSession(session_id, month);

            function getAccountsDataSession(session_id, month) {

                $.get("{{ route('get-account-dashboard-data') }}", {
                        session_id,
                        month
                    },
                    function(data) {
                        destroySlider('#month-card');

                        let monthHtml = "";
                        let index = 0;
                        $.each(data, function(idx, val) {
                            index++;
                            monthHtml += `<div><div class="card ${index > 1? "ml-2":""} mb-2 mt-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-9">
                                                            <div class="d-flex align-items-center align-self-start">
                                                                <h3 class="mb-0 text-black">
                                                                    ${val}
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="icon icon-box-success ">
                                                                <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6 style="text-transform:capitalize" class="text-muted font-weight-normal">${idx.replace('_',' ')}</h6>
                                                </div>
                                            </div></div>`;
                        });
                        $('#month-card').html(monthHtml);

                        //session slick
                        applySlider('#month-card');
                    });
            }

            $("#session_id").change(function() {
                let session_id = $(this).val();
                let month = $("#month").val();

                let sessionName = $(this).find('option:selected').text();
                let monthName = $("#month option:selected").text();

                changeMonthSessioName(monthName, sessionName);
                getAccountsDataSession(session_id, month);

            });

            $("#month").change(function() {
                let session_id = $("#session_id").val();
                let month = $(this).val();

                let sessionName = $("#session_id option:selected").text();
                let monthName = $(this).find('option:selected').text();

                changeMonthSessioName(monthName, sessionName);
                getAccountsDataSession(session_id, month);
            });



            let session_id2 = $("#session_id2").val();
            getAccountsData(session_id2);
            //get accounts data for session
            function getAccountsData(session_id2) {

                $.get("{{ route('get-account-dashboard-data-by-session') }}", {
                        session_id: session_id2
                    },
                    function(data) {
                        destroySlider("#session-card");
                        let sessionHtml = "";
                        let index = 0;
                        $.each(data, function(idx, val) {
                            index++;
                            sessionHtml += `<div><div class="card ${index > 1? "ml-2":""} mb-2 mt-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-9">
                                                            <div class="d-flex align-items-center align-self-start">
                                                                <h3 class="mb-0 text-black">
                                                                    ${val}
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="icon icon-box-success ">
                                                                <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h6 style="text-transform:capitalize" class="text-muted font-weight-normal">${idx.replace('_',' ')}</h6>
                                                </div>
                                            </div></div>`;
                        });
                        $('#session-card').html(sessionHtml);
                        //session slick
                        applySlider("#session-card");

                    });


            }




            $("#session_id2").change(function() {
                let session_id2 = $(this).val();
                let sessionName = $(this).find('option:selected').text();

                changeSessionName(sessionName);
                getAccountsData(session_id2);


            });

            //accounts overview report
            let sessionId3 = $('#session_id3').val();
            let month3 = $('#month3').val();

            getaccountsOverviewData(sessionId3, month3);

            function getaccountsOverviewData(sessionId3, month3) {
                $.get("{{ route('get-account-overview-data-session-month') }}", {
                    session_id: sessionId3,
                    month: month3
                }, function(data) {
                    let html = '';
                    $.each(data, function(idx, val) {
                        html += `<tr>
                                <td>${idx+1}</td>
                                <td>${val.name}</td>
                                <td>${val.total_payable}</td>
                                <td>${val.total_collection}</td>
                                <td>${val.total_dues}</td>
                                <td>${val.scholarship}</td>
                                <td>${val.discount}</td>
                                <td>${val.fine}</td>
                             </tr>`;
                    });
                    $('#overview-tbody').html(html);
                });
            }

            $("#session_id3").change(function() {
                let sessionId3 = $(this).val();
                let month3 = $('#month3').val();
                getaccountsOverviewData(sessionId3, month3);
            });

            $("#month3").change(function() {
                let sessionId3 = $("#session_id3").val();
                let month3 = $(this).val();
                getaccountsOverviewData(sessionId3, month3);
            });
        });
    </script>
@endpush
