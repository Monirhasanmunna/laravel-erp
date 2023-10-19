<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    {{-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admit Card</title>

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

    @media (max-width:1020px) {
        section#section {
            padding: 0 5px
        }
    }

</style>

<body>
    <div style="width:750px; margin: auto; border: 8px solid #92d092;border-style:double;padding:0px 10px;margin-bottom:10px;margin-top:10px;
                @if(isset($routine) && $isRoutineSelected == true)
                height:auto;
                @else
                height:auto;
                @endif
                ">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 40px;height: auto;padding-bottom:5px;"><img style="width: 100%;" src="{{@Helper::academic_setting()->image ? Config::get('app.s3_url').Helper::academic_setting()->image : asset('logo.jpg')}}"></td>
                    <td style="">
                        <table style="width: 100%;">
                            @php
                            $stringNumber = Str::length(@Helper::academic_setting()->school_name ?? '');
                            @endphp
                            <tr>
                                <td colspan="3" style="text-align: center; font-weight: 600;color:green;text-transform: uppercase;
                                        @if(@$stringNumber > 30 && $stringNumber < 40)
                                        font-size: 21px;
                                        padding-right: 85px;
                                        @elseif(@$stringNumber > 40)
                                        padding-right: 85px;
                                        font-size: 16px;
                                        @else
                                        font-size: 20px;
                                        padding-right: 75px;
                                        @endif
                                        ">{{@Helper::academic_setting()->school_name}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" style=" width: 200px; text-align: center;padding-right: 85px;padding-top: 5px;text-transform: uppercase;color:black;
                                            @if($stringNumber > 40)
                                                font-size: 9px;
                                            @else
                                                font-size: 9px;
                                            @endif
                                        ">Address: {{@Helper::school_info()->address}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;padding: 12px ;"> <span
                            style="padding: 6px 30px; background-color: white; color: #000;font-size: 13px;font-weight: 600;text-transform: uppercase;border: 2px solid #36414d;">Admit
                            Card</span> </td>
                </tr>
                <tr>
                    <td style="width: 55px; height: 54px;padding:16px 0px">
                        <img style="width: 100%;border: 2px solid #4e3f3f; padding: 3px;margin-top:-15px;"
                            src="{{@$student->photo ? Config::get('app.s3_url').$student->photo : asset('male.png')}}">
                    </td>
                    <td style="padding-left: 20px;">
                        <table style="width:90%;margin-top:-7px;">
                            <tr>
                                <td style="padding:2px;font-size: 12px;">St Name</td>
                                <td style="padding:2px;font-size: 12px;">:&#160;&#160;&#160;&#160;&#160;</td>
                                <td style="padding:2px;font-size: 12px;">{{@$student->name}}</td>
                                <td style="padding:2px;font-size: 12px;">Session</td>
                                <td style="padding:2px;font-size: 12px;">:</td>
                                <td style="padding:2px;font-size: 12px;">{{@$student->session->title}}</td>
                            </tr>
                            <tr>
                                <td style="padding:2px;font-size: 12px;">ST ID</td>
                                <td style="padding:2px;font-size: 12px;">:</td>
                                <td style="padding:2px;font-size: 12px;">{{@$student->id_no}}</td>
                                <td style="padding:2px;font-size: 12px;">Class-Shift-Section</td>
                                <td style="padding:2px;font-size: 12px;">:&#160;&#160;&#160;</td>
                                <td style="padding:2px;font-size: 12px;">{{@$student->class->name}}-{{@$student->shift->name}}-{{@$student->section->name}}</td>
                            </tr>
                            <tr>
                                <td style="padding:2px;font-size: 12px;">Class Roll</td>
                                <td style="padding:2px;font-size: 12px;">:</td>
                                <td style="padding:2px;font-size: 12px;">{{@$student->roll_no}}</td>
                                <td style="padding:2px;font-size: 12px;">Category</td>
                                <td style="padding:2px;font-size: 12px;">:&#160;&#160;&#160;</td>
                                <td style="padding:2px;font-size: 12px;">{{Str::limit(@$student->category->name, 13,'...')}}</td>
                            </tr>
                            <tr>
                                <td style="padding:2px;font-size: 12px;">Exam</td>
                                <td style="padding:2px;font-size: 12px;">:&#160;&#160;&#160;&#160;&#160;</td>
                                <td style="padding:2px;font-size: 12px;">{{@$exam->name}}</td>
                                <td style="padding:2px;font-size: 12px;">Group</td>
                                <td style="padding:2px;font-size: 12px;">:&#160;&#160;&#160;</td>
                                <td style="padding:2px;font-size: 12px;">{{@$student->group->name}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @if(isset($routine) && $isRoutineSelected == true)
                <tr>
                    <td colspan="5"
                        style="text-align: center;border: 1px solid #36414d;padding: 3px ;"> <span
                            style="padding: 3px 30px; background-color: white; color: #000;font-size: 14px;font-weight: 600;">EXAM
                            ROUTINE: {{@$exam->name}} ({{$session->title}})</span></td>
                </tr>
                @endif
            </table>
            @if (isset($routine) && $isRoutineSelected == true)
            <table style="width: 100%;margin-top:4px;">
                <tr>
                    <td>
                        <table
                            style="width: 100%;border: 1px solid;border-collapse: collapse;text-align:center;padding-right: 3px;">
                            <tr>
                                <td
                                    style="border: 2px solid #36414d;background-color:#d8d8d8;padding:1px; font-weight: 500;font-size:12px;">
                                    Date</td>
                                <td
                                    style="border: 2px solid #36414d ;background-color: #d8d8d8; padding:1px; font-weight: 500;font-size:12px;">
                                    Subject</td>
                                <td
                                    style="border: 2px solid #36414d;background-color:#d8d8d8; padding:1px; font-weight: 500;font-size:12px;">
                                    Time</td>
                                <td
                                    style="border: 2px solid #36414d;background-color:#d8d8d8;padding:1px; font-weight: 500;font-size:12px;">
                                    Date</td>
                                <td
                                    style="border: 2px solid #36414d ;background-color: #d8d8d8; padding:1px; font-weight: 500;font-size:12px;">
                                    Subject</td>
                                <td
                                    style="border: 2px solid #36414d;background-color:#d8d8d8; padding:1px; font-weight: 500;font-size:12px;">
                                    Time</td>
                            </tr>

                            @foreach ($routine->classSubjects as $key => $sub)
                            @if (($key + 1) % 2 != 0)
                            <tr style="border:1px solid #36414d;">
                                @endif
                                <td align="center"
                                    style="border:1px solid #36414d; border-bottom:1px solid #36414d;padding-top:2px;padding-bottom:2px;font-size:11px;">
                                    {{date('d-M-y', strtotime($sub->pivot->date))}}</td>
                                <td align="center" style="border:1px solid #36414d;padding-top:2px;padding-bottom:2px;font-size:12px;">
                                    {{ $sub->subject->sub_name }}</td>
                                <td align="center" style="border:1px solid #36414d;padding-top:2px;padding-bottom:2px;font-size:11px;">
                                    {{date('h:i a', strtotime($sub->pivot->start_time))}} -
                                    {{date('h:i a', strtotime($sub->pivot->end_time))}}</td>
    
                                @if (($key + 1) % 2 == 0)
                            </tr>
                            @endif
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
            @endif
            <div style="width: 100%;padding:0px 5px;font-size:10px;">
                @if (isset($routine) && $isRoutineSelected == true)
                    {!! $routine->instruction !!}
                @endif
            </div>
            <table style="width:100%;padding-right:3px;">
                <tr>
                    <td style="padding-left:30px;"><img style="width:70px;"
                            src="{{@$assign_teacher_signature ? Config::get('app.s3_url').$assign_teacher_signature : asset('signature.png')}}"
                            alt=""></td>
                    <td style="padding-left:480px;text-align:center;"><img style="width:70px;"
                            src="{{@Helper::academic_setting()->signImage ? Config::get('app.s3_url').Helper::academic_setting()->signImage : asset('signature.png')}}"
                            alt=""></td>
                </tr>
                <tr>
                    <td style="padding-left:30px;">-----------------</td>
                    <td style="padding-left:480px;text-align:center;">----------------</td>
                </tr>
                <tr>
                    <td style="padding-left:30px;font-size: 12px;">Class Teacher</td>
                    <td style="padding-left:480px;text-align:center;font-size: 12px;">{{@Helper::academic_setting()->signText}}</td>
                </tr>
            </table>
        </div>
</body>

</html>
