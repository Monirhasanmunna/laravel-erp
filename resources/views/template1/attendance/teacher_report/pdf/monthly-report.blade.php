<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Admit Card</title>

</head>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;

    }

    .page_break {
        page-break-before: always;
    }

</style>

<body>

    <div style="width:750px; margin: auto; border: 8px solid #92d092;border-style:double;padding:0px 10px;margin-bottom:10px;margin-top:10px;height:auto;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 40px;height: auto;padding-bottom:5px;"><img style="width: 100%;"
                        src="{{Helper::academic_setting()->image ? Config::get('app.s3_url').Helper::academic_setting()->image : asset('logo.jpg')}}">
                </td>
                <td style="">
                    <table style="width: 100%;">
                        @php
                        $stringNumber = Str::length(Helper::academic_setting()->school_name );
                        @endphp
                        <tr>
                            <td colspan="3" style="text-align: center; font-weight: 600;color:green;text-transform: uppercase;
                                        @if($stringNumber > 30 && $stringNumber < 40)
                                        font-size: 21px;
                                        padding-right: 85px;
                                        @elseif($stringNumber > 40)
                                        padding-right: 85px;
                                        font-size: 16px;
                                        @else
                                        font-size: 20px;
                                        padding-right: 55px;
                                        @endif
                                        ">{{Helper::academic_setting()->school_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style=" width: 200px; text-align: center;padding-right: 85px;padding-top: 5px;text-transform: uppercase;color:black;
                                            @if($stringNumber > 40)
                                                font-size: 9px;
                                            @else
                                            padding-right: 55px;
                                                font-size: 9px;
                                            @endif
                                        ">Address: {{Helper::school_info()->address}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center;padding: 12px ;"> <span
                        style="padding: 6px 30px; background-color: white; color: #000;font-size: 13px;text-transform: uppercase;border: 1px solid #2c2e33;">Monthly Report</span> </td>
            </tr>

            {{-- table here --}}
        </table>

        <table style="width: 100%;margin-top:4px;padding-bottom:20px;
        @if(Count($htmlData['applications']) > 0)
            border-bottom:1px solid rgba(59, 59, 59, 0.979);
        @endif
        ">
            <tr>
                <td>
                    <table
                        style="width: 100%;border: 1px solid;border-collapse: collapse;text-align:center;padding-right: 3px;">
                        
                        {!! $htmlData['table_head'] !!}
                        

                        {!! $htmlData['table_body'] !!}
                    </table>
                </td>
            </tr>
        </table>


        @if(Count($htmlData['applications']) > 0)
            <table style="width: 100%;margin-top:4px;">
                <tr>
                    <td colspan="5" style="text-align: center;padding: 12px ;"> <span
                            style="padding: 6px 30px; background-color: white; color: #000;font-size: 13px;font-weight: 600;text-transform: uppercase;border: 1px solid #2c2e33;">Leave Report</span> </td>
                </tr>
            </table>
        
        
            <table style="width: 100%;border: 1px solid;border-collapse: collapse;text-align:center;padding-right: 3px;margin-top:4px;padding-bottom:30px;">
                <thead>
                    <tr>
                        <td
                            style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">
                            Name</td>
                        <td
                        style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">
                        Designation</td>
                        <td
                        style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">
                        From Date</td>
                        <td
                        style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">
                        To Date</td>
                        <td
                        style="border: 1px solid #2c2e33;background-color:#d8d8d8;padding:1px;font-size:12px;">
                        Total Leave</td>
                        </tr>
                <thead>
                
                <tbody>
                    @foreach ($htmlData['applications'] as $application)
                        <tr> 
                            <td style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:1px;padding-bottom:1px;font-size:11px;">{{$application->source->teacher->name}}</td>
                            <td style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:1px;padding-bottom:1px;font-size:11px;">{{$application->source->teacher->designation->title}}</td> 
                            <td style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:1px;padding-bottom:1px;font-size:11px;">{{date('d-M-y',strtotime($application->to_date))}}</td> 
                            <td style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:1px;padding-bottom:1px;font-size:11px;">{{date('d-M-y',strtotime($application->from_date))}}</td> 
                            <td style="border:1px solid #2c2e33; border-bottom:1px solid #2c2e33;padding-top:1px;padding-bottom:1px;font-size:11px;">{{$application->total_day}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
                


        <table style="width:100%;padding-right:3px; margin-top:15px;">
            <tr>
                <td></td>
                <td style="padding-left:480px;text-align:center;"><img style="width:70px;"
                        src="{{Helper::academic_setting()->signImage ? Config::get('app.s3_url').Helper::academic_setting()->signImage : asset('signature.png')}}"
                        alt=""></td>
            </tr>
            <tr>
                <td style="padding-left:30px;"></td>
                <td style="padding-left:480px;text-align:center;">----------------</td>
            </tr>
            <tr>
                <td style="padding-left:30px;font-size: 12px;"></td>
                <td style="padding-left:480px;text-align:center;font-size: 12px;">
                    {{Helper::academic_setting()->signText}}</td>
            </tr>
        </table>
    </div>
</body>

</html>
