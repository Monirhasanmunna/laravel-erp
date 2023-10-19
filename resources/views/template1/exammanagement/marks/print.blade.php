<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    {{-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Input Marks</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

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

    #tb td {
        border: 1px dotted #000000;
        border-bottom: 1px dotted #000000;
        padding-top: 2px;
        padding-bottom: 2px;
        font-size: 17px;
        padding: 6px;
    }

    #tb th {
        border: 1px dotted #000000;
        text-transform: uppercase;
        background-color: #d8d8d8;
        padding: 1px;
        font-size: 17px;
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
                                {{ @Helper::school_info()->address }}</td>
                        </tr>
                        <tr>
                            <td colspan="3"
                                style=" width: 200px; text-align: center;padding-top:5px;text-transform: uppercase;color:black;padding-bottom:5px;
                                        @if ($stringNumber > 40) font-size: 14px;
                                            padding-right: 85px;
                                        @else
                                            font-size: 11px;
                                            padding-right: 60px; @endif
                                    ">
                                Class: <b>{{ $title['class'] }}</b>,
                                Category: <b>{{ $title['category'] }}</b> ,
                                Group: <b>{{ $title['group'] }}</b>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center;padding: 5px ;"> <span
                        style="padding: 6px 30px; background-color: white; color: #000;font-size: 14px;font-weight: 600;text-transform: uppercase;border: 1px solid #1e2023;">
                        {{$title['subject']}} &ensp;({{ $title['exam']}})</span> </td>
            </tr>
        </table>


        <table style="width: 100%;margin-top:7px;">
            <tr>
                <td>
                    <table
                        style="width: 100%;border: 1px dotted;border-collapse: collapse;text-align:center;padding-right: 3px;"
                        id="tb">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Student Id</th>
                                <th>Student Name</th>
                                <th>Roll No</th>
                                @foreach ($data['mark_dists'] as $markDist)
                                    <th>{{ $markDist['subMarkDistType']['title'] }}
                                        {{ $markDist['mark'] }}-{{ $markDist['pass_mark'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['students'] as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student['id_no'] }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>{{ $student['roll_no'] }}</td>
                                    <input type="hidden" class="student_id" name="student_id[]" value="{{$student['id']}}" >
                                    @foreach ($data['mark_dists'] as $markDist)
                                        <td>
                                            <span class="mark_dist_details_id-{{$markDist['id']}}"></span>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
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
        //get mark dist data
        getMarksInput(); 
        function getMarksInput() {

            let class_id = {{$request['class_id']}};
            let section_id = {{$request['section_id']}};
            let category_id = {{$request['category_id']}};
            let group_id = {{$request['group_id']}};
            let exam_id = {{$request['exam_id']}};
            let subject_id = {{$request['class_subject_id']}};

            $.get("{{ route('exam-management.marks.get-marks') }}", {
                class_id,
                section_id,
                category_id,
                group_id,
                exam_id,
                subject_id
            }, function(data) {
                console.log(data);
                if (data) {
                    $('input[name="student_id[]"]').map(function(idx, elem) {
                        let studentId = $(elem).val();
                        let result = data.filter((item) => {
                            return item.student_id == studentId;
                        });
                        $.each(result, function(i, v) {
                            $(elem).closest('tr').find(
                                `.mark_dist_details_id-${v.sub_marks_dist_details_id}`
                            ).text(v.marks);
                        });
                    }).get();
                    $('#save-btn').html("<i class='fa fa-save'></i>Update");
                } else {
                    $('#save-btn').html("<i class='fa fa-save'></i>Save");
                }

            });
        }

        //for print
        var css = '@page { size: landscape; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');

        style.type = 'text/css';
        style.media = 'print';

        if (style.styleSheet){
        style.styleSheet.cssText = css;
        } else {
        style.appendChild(document.createTextNode(css));
        }

        head.appendChild(style);

        window.print();
        window.print();
    </script>
</body>

</html>
