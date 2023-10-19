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
    <div style="width:750px; margin: auto; border: 8px solid #92d092;border-style:double;padding:0px 10px;margin-bottom:10px;margin-top:10px;height:auto;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 45px;height: auto;padding-bottom:5px;"><img style="width: 100%;" src="{{@Helper::academic_setting()->image ? Config::get('app.s3_url').Helper::academic_setting()->image : asset('logo.jpg')}}"></td>
                <td style="">
                    <table style="width: 100%;">
                        @php
                        $stringNumber = Str::length(@Helper::academic_setting()->school_name );
                        @endphp
                        <tr>
                            <td colspan="3" style="text-align: center;font-weight: 600;color:green;text-transform: uppercase;
                                    @if($stringNumber > 30 && $stringNumber < 40)
                                    font-size: 21px;
                                    padding-right: 85px;
                                    @elseif($stringNumber > 40)
                                    font-size: 16px;
                                    padding-right: 85px;
                                    @else
                                    font-size: 22px;
                                    padding-right: 60px;
                                    @endif
                                    ">{{@Helper::academic_setting()->school_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style=" width: 200px; text-align: center;padding-top: 5px;text-transform: uppercase;color:black;padding-bottom:5px;
                                        @if($stringNumber > 40)
                                            font-size: 9px;
                                            padding-right: 85px;
                                        @else
                                            font-size: 10px;
                                            padding-right: 60px;
                                        @endif
                                    ">Address: {{@Helper::school_info()->address}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center;padding: 5px ;"> <span
                        style="padding: 6px 30px; background-color: white; color: #000;font-size: 13px;font-weight: 600;text-transform: uppercase;border: 1px solid #48525d;">Committee List</span> </td>
            </tr>
        </table>


        <table style="width: 100%;margin-top:4px;">
            <tr>
                <td>
                    <table
                        style="width: 100%;border: 1px solid;border-collapse: collapse;text-align:center;padding-right: 3px;">
                        <tr>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8;padding:1px;font-size:12px;">
                                SL</td>
                            <td
                                style="border: 1px solid #48525d ;background-color: #d8d8d8; padding:1px;font-size:12px;">
                                Image</td>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8; padding:1px;font-size:12px;">
                                Name</td>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8;padding:1px;font-size:12px;">
                                Designation</td>
                            <td
                                style="border: 1px solid #48525d ;background-color: #d8d8d8; padding:1px;font-size:12px;">
                                Gender</td>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8; padding:1px;font-size:12px;">
                                Mobile</td>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8; padding:1px;font-size:12px;">
                                B/G</td>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8; padding:1px;font-size:12px;">
                                Start Date</td>
                            <td
                                style="border: 1px solid #48525d;background-color:#d8d8d8; padding:1px;font-size:12px;">
                                End Date</td>
                        </tr>
                        {{-- {{dd($committies)}} --}}
                        @foreach ($committies as $key => $committiee)
                            <tr style="border:1px solid #48525d;">
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{$key+1}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;"><img style="width:25px;" src="{{@$committiee->image ? Config::get('app.s3_url').$committiee->photo : Helper::default_image() }}" alt="" srcset=""></td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{@$committiee->name}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{@$committiee->designation->title}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{ucfirst(@$committiee->gender)}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{@$committiee->mobile_number}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{@$committiee->blood_group}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{@$committiee->joining_date}}</td>
                                <td align="center" style="border:1px solid #48525d; border-bottom:1px solid #48525d;padding-top:2px;padding-bottom:2px;font-size:11px;">{{@$committiee->leaving_date}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
        
        <table style="width:100%;padding-right:3px;margin-top: 20px;">
            <tr>
                <td></td>
                <td style="padding-left:480px;text-align:center;"><img style="width:80px;"
                        src="{{@Helper::academic_setting()->signImage ? Config::get('app.s3_url').Helper::academic_setting()->signImage : asset('signature.png')}}"
                        alt=""></td>
            </tr>
            <tr>
                <td style="padding-left:30px;"></td>
                <td style="padding-left:480px;text-align:center;">----------------</td>
            </tr>
            <tr>
                <td style="padding-left:30px;"></td>
                <td style="padding-left:480px;text-align:center;">{{@Helper::academic_setting()->signText}}</td>
            </tr>
        </table>
    </div>
</body>

</html>
