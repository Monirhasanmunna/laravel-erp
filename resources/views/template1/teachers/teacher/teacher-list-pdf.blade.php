<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher List PDF</title>


    <style>
        .footer-section {
            width: 100%;
        }

        .table-data {
            width: 100%;
        }

        .print {
            float: left;
            width: 40%;
        }

        .Head-teacher {
            float: right;
            width: 24%;
        }

        .class-teacher {
            float: left;
            width: 29%;
        }

        .mid-content {
            max-width: 1300px;
            margin: auto;
        }

        .header-logo {
            float: left;
            width: 40%;
            text-align: center;
            margin-top: 5px;
        }

        .header-details {
            float: left;
            text-align: center;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: block;
        }

        th>h6 {
            line-height: 0px;

        }

        td>h6 {
    line-height: 0px;
    margin-left: 2px;
}

        table,
        th,
        td {
            border: 1px solid black;
            border-style: dotted;
            border-collapse: collapse;
        }

        .footer {
            padding: 40px 0px;
        }

        .footer-content {}

        .footer-content p {
            font-size: 25px;
            font-weight: 300;
        }
    </style>

</head>

<body>
    <section class="hader-section" style="width: 100%;">
        <div class="mid-content">
            <div style="float:left;margin-right:40px;width:10%;">
                <img src="{{asset('uploads/schoolInfo/'.Helper::school_info()->logo)}}" alt="" style="width:80px;height:80px:border-radius:50%;padding-top:15px;">
            </div>

            @php
                $schoolName = Str::length(Helper::academic_setting()->school_name);
            @endphp
            <div style="float:left;text-align:center;width:90%;margin-left:-75px;">
                <h2 style="{{$schoolName > 31 ? 'font-size:17px;' : ''}}">{{Helper::academic_setting()->school_name}}</h2>
                <h5 style="line-height:1px; ">{{Helper::school_info()->address}}</h5>
                <h6 style="line-height:1px;">Total : {{$teachers->count()}}</h6>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>

    <section class="table-section">
        <div class="mid-content">
            <div class="table-data ">
                <table style="width: 100%;">
                    <tr>
                        <th>
                            <h4>Id</h4>

                            <h4>Mobile</h4>
                        </th>
                        <th width='25%'>
                            <h4>Teacher's Name</h4>
                        </th>
                        <th>
                            <h4>Dob</h4>
                            <h4>Gender</h4>
                            <h4>Religion</h4>
                        </th>
                        
                        <th>
                            <h4>Designation</h4>
                        </th>

                        <th width='10%'>
                            <h4>Branch</h4>
                        </th>

                        <th>
                            <h4>Photo</h4>
                        </th>
                        <th>
                            <h4>Signature</h4>
                        </th>
                    </tr>

                    @foreach ($teachers as $teacher)
                    <tr>
                        <td style="text-align:center;">
                            <p>{{@$teacher->id_no}}</p>
                            <p>{{@$teacher->roll_no}}</p>
                            <p>{{@$teacher->mobile_number}}</p>
                        </td>
                        <td style="text-align:center;">
                            <p>{{@$teacher->name}}</p>
                        </td>

                        <td style="text-align:center;">
                            <p>{{$teacher->dob ?? '-'}}</p>
                            <p>{{ucfirst(@$teacher->gender)}}</p>
                            <p>{{ucfirst(@$teacher->religion ?? '-')}}</p>
                        </td>

                        <td style="text-align:center;">
                            <p>{{@$teacher->designation->title}}</p>
                        </td>

                        <td style="text-align:center;">
                            <p>{{@$teacher->branch->title}}</p>
                        </td>

                        <td style="text-align: center;">
                            @if(isset($teacher->photo))
                                <img src="{{Config::get('app.s3_url').$teacher->photo}}" alt="" style="width:50px;height:50px;border-radius:50%;">
                            @else
                                @if($teacher->gender == 'Male')
                                    <img src="{{asset('male.png')}}" alt="" style="width:50px;height:50px;border-radius:50%;">
                                @else
                                    <img src="{{asset('female.jpeg')}}" alt="" style="width:50px;height:50px;border-radius:50%;">
                                @endif
                            @endif
                        </td>
                        <td>
                            <p style="opacity:0.5;text-align:center;"></p>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>

    <section class="mid-content">
        <div class="footer-section">
            <div class="footer">
                <div class="print">
                    <h5>Print Date: {{ now()->format('d M') }} </h5>
                </div>
                <div class="Head-teacher"><h5>Head Teacher</h5></div>
            </div>
        </div>
    </section>
</body>
</html>
