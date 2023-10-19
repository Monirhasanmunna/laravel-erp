<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    {{-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Paid Report</title>

</head>

<style>
    body {
        font-family: "Nikosh"
    }

    ,


    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;

    }

    .table1 {
        margin-top: 0px;
        margin-bottom: 5px;
    }

    .student-img {
        margin-right: 35px;
        object-fit: cover;
    }

    @media (max-width:515px) {
        .student-img {
            margin-right: 10px;
            width: 90px !important;
            height: 90px !important;
        }
    }

    td.tb-td-1 {
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.tb-td-2 {
        padding: 0 20px;
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.tb-td-father-name {
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.father {
        padding: 0 20px;
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.class {
        font-size: 17px;
        line-height: 28px;
        padding: 0 20px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.six {
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.mother {
        font-size: 17px;
        padding: 0 20px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.mother-name {
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.session {
        font-size: 17px;
        padding: 0 20px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.year {
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;
        /* display: flex; */
        /* align-items: center; */
        gap: 5px;

    }

    span.span-1 {
        color: black;
    }

    td.contact {
        font-size: 17px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    td.dot {
        font-size: 17px;
        padding: 0 20px;
        line-height: 28px;
        color: #3b4a54;
        font-weight: 400;
        width: 25%;

    }

    #tb td{
        border:1px dotted #000000;
        border-bottom:1px dotted #000000;
        padding-top:2px;
        padding-bottom:2px;
        font-size:15px;
    }

    #tb th{
        border: 1px dotted #000000;
        text-transform: uppercase;
        background-color:#d8d8d8;
        padding:1px;font-size:15px;
    }

    @media (max-width:1020px) {
        section#section {
            padding: 0 5px
        }
    }
</style>

<body>
    <div style="width:1100px; margin: auto;padding:0px 10px;margin-bottom:10px;margin-top:10px;height:auto;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 45px;height: auto;padding-bottom:5px;"><img style="width: 100%;"
                        src="{{ @Helper::academic_setting()->image ? Config::get('app.s3_url') . Helper::academic_setting()->image : asset('logo.jpg') }}">
                </td>
                <td style="">
                    <table style="width: 100%;padding-bottom:2px;">
                        @php
                            $stringNumber = Str::length(@Helper::academic_setting()->school_name);
                        @endphp
                        <tr>
                            <td colspan="3"
                                style="text-align: center;font-weight: 600;color:green;text-transform: uppercase;
                                    @if ($stringNumber > 30 && $stringNumber < 40) font-size: 23px;
                                    padding-right: 85px;
                                    @elseif($stringNumber > 40)
                                    font-size: 18px;
                                    padding-right: 85px;
                                    @else
                                    font-size: 24px;
                                    padding-right: 60px; @endif
                                    ">
                                {{ @Helper::academic_setting()->school_name }}</td>
                        </tr>
                        <tr>
                            <td colspan="3"
                                style=" width: 200px; text-align: center;padding-top:5px;text-transform: uppercase;color:black;padding-bottom:5px;
                                        @if ($stringNumber > 40) font-size: 10px;
                                            padding-right: 85px;
                                        @else
                                            font-size: 11px;
                                            padding-right: 60px; @endif
                                    ">
                                Address: {{ @Helper::school_info()->address }}</td>
                        </tr>
                        <tr>
                            <td colspan="3"
                                style=" width: 200px; text-align: center;padding-top:5px;text-transform: uppercase;color:black;padding-bottom:5px;
                                        @if ($stringNumber > 40) font-size: 10px;
                                            padding-right: 85px;
                                        @else
                                            font-size: 11px;
                                            padding-right: 60px; @endif
                                    ">
                                Paid Report: ({{$fromDate}} - {{$toDate}})</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center;padding: 5px ;"> <span
                        style="padding: 6px 30px; background-color: white; color: #000;font-size: 14px;font-weight: 600;text-transform: uppercase;border: 1px solid #1e2023;">Paid Report</span> </td>
            </tr>
        </table>


        <table style="width: 100%;margin-top:7px;">
            <tr>
                <td>
                    <table
                        style="width: 100%;border: 1px dotted;border-collapse: collapse;text-align:center;padding-right: 3px;" id="tb">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Id No</th>
                                <th>Name</th>
                                <th>Roll No</th>
                                <th>Class</th>
                                <th>Details</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($students as $student)
                                @php
                                    $total += $student['total_amount'];
                                @endphp
                                <tr>
                                    <td>
                                        <img src="{{ asset('male.png') }}" width="40px" alt="">
                                    </td>
                                    <td>{{ $student['id_no'] }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>{{ $student['roll_no'] }}</td>
                                    <td>{{ $student['class'] }}</td>
                                    <td>{{ $student['details'] }}</td>
                                    <td class="text-right">{{ number_format($student['total_amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" style="text-align: right">Total:</td>
                                <td>{{number_format($total,2)}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width:100%;padding-right:3px;margin-top: 20px;">
            <tr>
                <td style="padding-left:30px;"></td>
                <td style="padding-left:880px;text-align:center;">----------------</td>
            </tr>
            <tr>
                <td style="padding-left:30px;"></td>
                <td style="padding-left:880px;text-align:center;font-size:15px;">
                    {{ @Helper::academic_setting()->signText }}</td>
            </tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>
