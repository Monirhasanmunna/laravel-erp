<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      .button {
          margin: 20px;
          background-color: #4CAF50;
          border: none;
          color: white;
          margin-left: 220px;
          padding: 8px 25px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
      }
    </style>
  </head>
  <body>

    @php
     $currentMonth = date('M');
     $year =  date('Y');
     $year = substr( $year, -2);
     $currentDate = $currentMonth.'/'.$year;
   
    @endphp
    <div>
        <a class="button"  href="#" onclick="printDiv('printableArea')" >Print</a>
    </div>

    @if(@$students)
        <div id="printableArea" >
            @foreach($students as $student)
                <div style="width: 1400px; height: 720px; margin: auto" >

                    <div style="
                          width: 333px;
                          float: left;
                          background-image: url(./logo.png);
                          background-position: 100% center;
                          background-size: contain;
                          background-repeat: no-repeat;
                          margin-right: 4px;
                          border-right: 2px dashed gray;
                          padding-right: 4px;
                        ">
                        <div style="background-color: #ffffffbf; padding: 3px">
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                            <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                        @else
                                            <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                        @endif
                                    </td>
                                    <td>
                                        <table style="width: 100%; text-align: center">
                                            <tr>
                                                <td class="tdHead" style="font-size: 15px; text-transform: capitalize">
                                                    (Office copy)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                            </tr>
                                            <tr>
                                                <td  style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table
                                style="width: 100%; border-collapse: collapse; text-align: center"
                            >
                                <tr>
                                    <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 18px; padding-bottom: 10px">
                                        {{$student['category']['name']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-collapse: collapse">
                                            <tr>
                                                <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td><img style="height: 45px" src="{{$student['photo'] ? Config::get('app.s3_url').$student['photo'] : asset('female.jpeg')}}" alt="" /></td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        Name: {{$student['name']}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        <span style="text-transform: uppercase">ID:</span> {{$student['id_no']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 10px">Class: {{$student['class']['name']}}</td>
                                    <td style="font-size: 15px; padding-top: 10px">
                                        Section: {{$student['section']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-top: 10px">Roll: {{$student['roll_no']}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td
                                        style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                    >
                                        Group: {{$student['group']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Shift: {{$student['shift']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Seasion: {{$student['session']['title']}}
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%; border-collapse: collapse" border="1">
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        SL
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Description
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Amount
                                    </td>
                                </tr>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($student['fees'] as $fee)
                                    @php
                                        $total += intval($fee["amount"]);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center">{{$loop->iteration}}</td>
                                        <td>
                                            {{$fee['description']}}
                                        </td>
                                        <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    ></td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Total:
                                    </td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        {{number_format($total,2)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td style="font-weight: 600; font-size: 15px">
                                        (In Word): {{Helper::convertNumberToWord($total)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="padding-top: 20px">
                                        Must have to pay within Df-10th and 25-30th
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%">
                                <tr>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Verified by
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Receiver
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Depositar
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="border-top: 1px solid black">Class Header's</td>
                                    <td style="border-top: 1px solid black">Authorized</td>
                                    <td style="border-top: 1px solid black">Guardean's</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- single -->
                    <div
                        style="
                          width: 333px;
                          float: left;
                          background-image: url(./logo.png);
                          background-position: 100% center;
                          background-size: contain;
                          background-repeat: no-repeat;
                          margin-right: 4px;
                          border-right: 2px dashed gray;
                          padding-right: 4px;
                        "
                    >
                        <div style="background-color: #ffffffbf; padding: 3px">
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                            <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                        @else
                                            <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                        @endif
                                    </td>
                                    <td>
                                        <table style="width: 100%; text-align: center">
                                            <tr>
                                                <td style="font-size: 15px; text-transform: capitalize">
                                                    (Bank copy)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table
                                style="width: 100%; border-collapse: collapse; text-align: center"
                            >
                                <tr>
                                    <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 18px; padding-bottom: 10px">
                                        {{$student['category']['name']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-collapse: collapse">
                                            <tr>
                                                <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td><img style="height: 45px" src="{{$student['photo'] ? Config::get('app.s3_url').$student['photo'] : asset('female.jpeg')}}" alt="" /></td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        Name: {{$student['name']}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        <span style="text-transform: uppercase">ID:</span> {{$student['id_no']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 10px">Class: {{$student['class']['name']}}</td>
                                    <td style="font-size: 15px; padding-top: 10px">
                                        Section: {{$student['section']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-top: 10px">Roll: {{$student['roll_no']}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td
                                        style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                    >
                                        Group: {{$student['group']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Shift: {{$student['shift']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Seasion: {{$student['session']['title']}}
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%; border-collapse: collapse" border="1">
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        SL
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Description
                                    </td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Amount
                                    </td>
                                </tr>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($student['fees'] as $fee)
                                    @php
                                        $total += intval($fee["amount"]);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center">{{$loop->iteration}}</td>
                                        <td>
                                            {{$fee['description']}}
                                        </td>
                                        <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    ></td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Total:
                                    </td>
                                    <td
                                        style="
                  text-align:right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        {{number_format($total,2)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td style="font-weight: 600; font-size: 15px">
                                        (In Word): {{Helper::convertNumberToWord($total)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="padding-top: 20px">
                                        Must have to pay within Df-10th and 25-30th
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%">
                                <tr>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Verified by
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Receiver
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Depositar
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="border-top: 1px solid black">Class Header's</td>
                                    <td style="border-top: 1px solid black">Authorized</td>
                                    <td style="border-top: 1px solid black">Guardean's</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <!-- single -->
                    <div
                        style="
          width: 333px;
          float: left;
          background-image: url(./logo.png);
          background-position: 100% center;
          background-size: contain;
          background-repeat: no-repeat;
          margin-right: 4px;
          border-right: 2px dashed gray;
          padding-right: 4px;
        "
                    >
                        <div style="background-color: #ffffffbf; padding: 3px">
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                            <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                        @else
                                            <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                        @endif
                                    </td>
                                    <td>
                                        <table style="width: 100%; text-align: center">
                                            <tr>
                                                <td style="font-size: 15px; text-transform: capitalize">
                                                    (teacher's copy)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table
                                style="width: 100%; border-collapse: collapse; text-align: center"
                            >
                                <tr>
                                    <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 18px; padding-bottom: 10px">
                                              {{$student['category']['name']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-collapse: collapse">
                                            <tr>
                                                <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td><img style="height: 45px" src="{{$student['photo'] ? Config::get('app.s3_url').$student['photo'] : asset('female.jpeg')}}" alt="" /></td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        Name: {{$student['name']}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        <span style="text-transform: uppercase">ID:</span> {{$student['id_no']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 10px">Class: {{$student['class']['name']}}</td>
                                    <td style="font-size: 15px; padding-top: 10px">
                                        Section: {{$student['section']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-top: 10px">Roll: {{$student['roll_no']}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td
                                        style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                    >
                                        Group: {{$student['group']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Shift: {{$student['shift']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Seasion: {{$student['session']['title']}}
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%; border-collapse: collapse" border="1">
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        SL
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Description
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Amount
                                    </td>
                                </tr>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($student['fees'] as $fee)
                                    @php
                                        $total += intval($fee["amount"]);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center">{{$loop->iteration}}</td>
                                        <td>
                                            {{$fee['description']}}
                                        </td>
                                        <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    ></td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Total:
                                    </td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        {{number_format($total,2)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td style="font-weight: 600; font-size: 15px">
                                        (In Word): {{Helper::convertNumberToWord($total)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="padding-top: 20px">
                                        Must have to pay within Df-10th and 25-30th
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Verified by
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Receiver
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Depositar
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="border-top: 1px solid black">Class Header's</td>
                                    <td style="border-top: 1px solid black">Authorized</td>
                                    <td style="border-top: 1px solid black">Guardean's</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- single -->
                    <div
                        style="
          width: 333px;
          float: left;
          background-image: url(./logo.png);
          background-position: 100% center;
          background-size: contain;
          background-repeat: no-repeat;
        "
                    >
                        <div style="background-color: #ffffffbf; padding: 3px">
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                            <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                        @else
                                            <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                        @endif
                                    </td>
                                    <td>
                                        <table style="width: 100%; text-align: center">
                                            <tr>
                                                <td style="font-size: 15px; text-transform: capitalize">
                                                    (student's copy)
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table
                                style="width: 100%; border-collapse: collapse; text-align: center"
                            >
                                <tr>
                                    <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 18px; padding-bottom: 10px">
                                              {{$student['category']['name']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-collapse: collapse">
                                            <tr>
                                                <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td><img style="height: 45px" src="{{$student['photo'] ? Config::get('app.s3_url').$student['photo'] : asset('female.jpeg')}}" alt="" /></td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        Name: {{$student['name']}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px; padding-top: 2px">
                                        <span style="text-transform: uppercase">ID:</span> {{$student['id_no']}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td style="font-size: 15px; padding-top: 10px">Class: {{$student['class']['name']}}</td>
                                    <td style="font-size: 15px; padding-top: 10px">
                                        Section: {{$student['section']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-top: 10px">Roll: {{$student['roll_no']}}</td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse">
                                <tr>
                                    <td
                                        style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                    >
                                        Group: {{$student['group']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Shift: {{$student['shift']['name']}}
                                    </td>
                                    <td style="font-size: 15px; padding-bottom: 8px">
                                        Seasion: {{$student['session']['title']}}
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%; border-collapse: collapse" border="1">
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        SL
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Description
                                    </td>
                                    <td
                                        style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Amount
                                    </td>
                                </tr>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($student['fees'] as $fee)
                                    @php
                                        $total += intval($fee["amount"]);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center">{{$loop->iteration}}</td>
                                        <td>
                                            {{$fee['description']}}
                                        </td>
                                        <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td
                                        style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    ></td>
                                    <td
                                        style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        Total:
                                    </td>
                                    <td
                                        style="
                  text-align:right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                    >
                                        {{number_format($total,2)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%">
                                <tr>
                                    <td style="font-weight: 600; font-size: 15px">
                                        (In Word): {{Helper::convertNumberToWord($total)}}
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="padding-top: 20px">
                                        Must have to pay within Df-10th and 25-30th
                                    </td>
                                </tr>
                            </table>

                            <table style="width: 100%">
                                <tr>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Verified by
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Receiver
                                    </td>
                                    <td
                                        style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                    >
                                        Depositar
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; text-align: center">
                                <tr>
                                    <td style="border-top: 1px solid black">Class Header's</td>
                                    <td style="border-top: 1px solid black">Authorized</td>
                                    <td style="border-top: 1px solid black">Guardean's</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="pagebreak" style="clear: both;page-break-after: always;"> </div>

            @endforeach
        </div>
    @endif

    @if(@$singleStudent)
        <div id="printableArea" >

            <div style="width: 1400px; height: 720px; margin: auto" >



                <div
                    style="
          width: 333px;
          float: left;
          background-image: url(./logo.png);
          background-position: 100% center;
          background-size: contain;
          background-repeat: no-repeat;
          margin-right: 4px;
          border-right: 2px dashed gray;
          padding-right: 4px;
        "
                >
                    <div style="background-color: #ffffffbf; padding: 3px">
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                        <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                    @else
                                        <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                    @endif
                                </td>
                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tr>
                                            <td class="tdHead" style="font-size: 15px; text-transform: capitalize">
                                                (Office copy)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                        </tr>
                                        <tr>
                                            <td  style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table
                            style="width: 100%; border-collapse: collapse; text-align: center"
                        >
                            <tr>
                                <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                            </tr>
                            <tr>
                                <td style="font-size: 18px; padding-bottom: 10px">
                                          {{$singleStudent['category']['name']}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    <table style="width: 100%; border-collapse: collapse">
                                        <tr>
                                            <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><img style="height: 45px" src="{{$singleStudent->photo ? Config::get('app.s3_url').$singleStudent->photo : asset('female.jpeg')}}" alt="" /></td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    Name: {{$singleStudent->name}}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    <span style="text-transform: uppercase">ID:</span> {{$singleStudent->id_no}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 10px">Class: {{$singleStudent->class->name}}</td>
                                <td style="font-size: 15px; padding-top: 10px">
                                    Section: {{$singleStudent->section->name}}
                                </td>
                                <td style="font-size: 15px; padding-top: 10px">Roll: {{$singleStudent->roll_no}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td
                                    style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                >
                                    Group: {{$singleStudent->group->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Shift: {{$singleStudent->shift->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Seasion: {{$singleStudent->session->title}}
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%; border-collapse: collapse" border="1">
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    SL
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Description
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Amount
                                </td>
                            </tr>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($fees as $fee)
                                @php
                                    $total += intval($fee["amount"]);
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{$loop->iteration}}</td>
                                    <td>
                                        {{$fee['description']}}
                                    </td>
                                    <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                ></td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Total:
                                </td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    {{number_format($total,2)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="font-weight: 600; font-size: 15px">
                                    (In Word): {{Helper::convertNumberToWord($total)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="padding-top: 20px">
                                    Must have to pay within Df-10th and 25-30th
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%">
                            <tr>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Verified by
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Receiver
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Depositar
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="border-top: 1px solid black">Class Header's</td>
                                <td style="border-top: 1px solid black">Authorized</td>
                                <td style="border-top: 1px solid black">Guardean's</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- single -->
                <div
                    style="
          width: 333px;
          float: left;
          background-image: url(./logo.png);
          background-position: 100% center;
          background-size: contain;
          background-repeat: no-repeat;
          margin-right: 4px;
          border-right: 2px dashed gray;
          padding-right: 4px;
        "
                >
                    <div style="background-color: #ffffffbf; padding: 3px">
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                        <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                    @else
                                        <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                    @endif
                                </td>
                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tr>
                                            <td style="font-size: 15px; text-transform: capitalize">
                                                (Bank copy)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table
                            style="width: 100%; border-collapse: collapse; text-align: center"
                        >
                            <tr>
                                <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                            </tr>
                            <tr>
                                <td style="font-size: 18px; padding-bottom: 10px">
                                          {{$singleStudent['category']['name']}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    <table style="width: 100%; border-collapse: collapse">
                                        <tr>
                                            <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><img style="height: 45px" src="{{$singleStudent->photo ? Config::get('app.s3_url').$singleStudent->photo : asset('female.jpeg')}}" alt="" /></td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    Name: {{$singleStudent->name}}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    <span style="text-transform: uppercase">ID:</span> {{$singleStudent->id_no}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 10px">Class: {{$singleStudent->class->name}}</td>
                                <td style="font-size: 15px; padding-top: 10px">
                                    Section: {{$singleStudent->section->name}}
                                </td>
                                <td style="font-size: 15px; padding-top: 10px">Roll: {{$singleStudent->roll_no}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td
                                    style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                >
                                    Group: {{$singleStudent->group->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Shift: {{$singleStudent->shift->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Seasion: {{$singleStudent->session->title}}
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%; border-collapse: collapse" border="1">
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    SL
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Description
                                </td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Amount
                                </td>
                            </tr>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($fees as $fee)
                                @php
                                    $total += intval($fee["amount"]);
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{$loop->iteration}}</td>
                                    <td>
                                        {{$fee['description']}}
                                    </td>
                                    <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                ></td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Total:
                                </td>
                                <td
                                    style="
                  text-align:right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    {{number_format($total,2)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="font-weight: 600; font-size: 15px">
                                    (In Word): {{Helper::convertNumberToWord($total)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="padding-top: 20px">
                                    Must have to pay within Df-10th and 25-30th
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%">
                            <tr>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Verified by
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Receiver
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Depositar
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="border-top: 1px solid black">Class Header's</td>
                                <td style="border-top: 1px solid black">Authorized</td>
                                <td style="border-top: 1px solid black">Guardean's</td>
                            </tr>
                        </table>

                    </div>
                </div>
                <!-- single -->
                <div
                    style="
          width: 333px;
          float: left;
          background-image: url(./logo.png);
          background-position: 100% center;
          background-size: contain;
          background-repeat: no-repeat;
          margin-right: 4px;
          border-right: 2px dashed gray;
          padding-right: 4px;
        "
                >
                    <div style="background-color: #ffffffbf; padding: 3px">
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                        <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                    @else
                                        <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                    @endif
                                </td>
                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tr>
                                            <td style="font-size: 15px; text-transform: capitalize">
                                                (teacher's copy)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table
                            style="width: 100%; border-collapse: collapse; text-align: center"
                        >
                            <tr>
                                <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                            </tr>
                            <tr>
                                <td style="font-size: 18px; padding-bottom: 10px">
                                          {{$singleStudent['category']['name']}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    <table style="width: 100%; border-collapse: collapse">
                                        <tr>
                                            <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><img style="height: 45px" src="{{$singleStudent->photo ? Config::get('app.s3_url').$singleStudent->photo : asset('female.jpeg')}}" alt="" /></td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    Name: {{$singleStudent->name}}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    <span style="text-transform: uppercase">ID:</span> {{$singleStudent->id_no}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 10px">Class: {{$singleStudent->class->name}}</td>
                                <td style="font-size: 15px; padding-top: 10px">
                                    Section: {{$singleStudent->section->name}}
                                </td>
                                <td style="font-size: 15px; padding-top: 10px">Roll: {{$singleStudent->roll_no}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td
                                    style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                >
                                    Group: {{$singleStudent->group->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Shift: {{$singleStudent->shift->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Seasion: {{$singleStudent->session->title}}
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%; border-collapse: collapse" border="1">
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    SL
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Description
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Amount
                                </td>
                            </tr>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($fees as $fee)
                                @php
                                    $total += intval($fee["amount"]);
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{$loop->iteration}}</td>
                                    <td>
                                        {{$fee['description']}}
                                    </td>
                                    <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                ></td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Total:
                                </td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    {{number_format($total,2)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="font-weight: 600; font-size: 15px">
                                    (In Word): {{Helper::convertNumberToWord($total)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="padding-top: 20px">
                                    Must have to pay within Df-10th and 25-30th
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Verified by
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Receiver
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Depositar
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="border-top: 1px solid black">Class Header's</td>
                                <td style="border-top: 1px solid black">Authorized</td>
                                <td style="border-top: 1px solid black">Guardean's</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- single -->
                <div
                    style="
          width: 333px;
          float: left;
          background-image: url(./logo.png);
          background-position: 100% center;
          background-size: contain;
          background-repeat: no-repeat;
        "
                >
                    <div style="background-color: #ffffffbf; padding: 3px">
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    @if(@Helper::academic_setting()->image == null || Helper::academic_setting()->image == "default-logo.png")
                                        <img style="height: 45px" src="{{asset("assets/images/logo/logo.png")}}" alt="">
                                    @else
                                        <img style="height: 45px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="">
                                    @endif
                                </td>
                                <td>
                                    <table style="width: 100%; text-align: center">
                                        <tr>
                                            <td style="font-size: 15px; text-transform: capitalize">
                                                (student's copy)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 18px">{{\App\Helper\Helper::school_info()->name}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 13px">{{\App\Helper\Helper::school_info()->address}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table
                            style="width: 100%; border-collapse: collapse; text-align: center"
                        >
                            <tr>
                                <td style="font-size: 18px; padding-top: 10px">Fees Book</td>
                            </tr>
                            <tr>
                                <td style="font-size: 18px; padding-bottom: 10px">
                                          {{$singleStudent['category']['name']}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td>
                                    <table style="width: 100%; border-collapse: collapse">
                                        <tr>
                                            <td style="font-size: 17px">Date: {{date("y-m-d")}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 17px">Month: {{$currentDate}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td><img style="height: 45px" src="{{$singleStudent->photo ? Config::get('app.s3_url').$singleStudent->photo : asset('female.jpeg')}}" alt="" /></td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    Name: {{$singleStudent->name}}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px; padding-top: 2px">
                                    <span style="text-transform: uppercase">ID:</span> {{$singleStudent->id_no}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td style="font-size: 15px; padding-top: 10px">Class: {{$singleStudent->class->name}}</td>
                                <td style="font-size: 15px; padding-top: 10px">
                                    Section: {{$singleStudent->section->name}}
                                </td>
                                <td style="font-size: 15px; padding-top: 10px">Roll: {{$singleStudent->roll_no}}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse">
                            <tr>
                                <td
                                    style="font-size: 15px; padding-top: 2px; padding-bottom: 8px"
                                >
                                    Group: {{$singleStudent->group->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Shift: {{$singleStudent->shift->name}}
                                </td>
                                <td style="font-size: 15px; padding-bottom: 8px">
                                    Seasion: {{$singleStudent->session->title}}
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%; border-collapse: collapse" border="1">
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    SL
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Description
                                </td>
                                <td
                                    style="
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Amount
                                </td>
                            </tr>
                            @php
                                $total = 0;
                            @endphp
                            @foreach($fees as $fee)
                                @php
                                    $total += intval($fee["amount"]);
                                @endphp
                                <tr>
                                    <td style="text-align: center">{{$loop->iteration}}</td>
                                    <td>
                                        {{$fee['description']}}
                                    </td>
                                    <td style="text-align: right">{{number_format($fee["amount"],2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td
                                    style="
                  text-align: center;
                  border: none;
                  text-align: center;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                ></td>
                                <td
                                    style="
                  text-align: right;
                  text-transform: uppercase;
                  font-weight: 500;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    Total:
                                </td>
                                <td
                                    style="
                  text-align:right;
                  text-transform: uppercase;
                  font-weight: 600;
                  background-color: gainsboro;
                  font-size: 15px;
                "
                                >
                                    {{number_format($total,2)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="font-weight: 600; font-size: 15px">
                                    (In Word): {{Helper::convertNumberToWord($total)}}
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="padding-top: 20px">
                                    Must have to pay within Df-10th and 25-30th
                                </td>
                            </tr>
                        </table>

                        <table style="width: 100%">
                            <tr>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Verified by
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Receiver
                                </td>
                                <td
                                    style="
                  padding-bottom: 10px;
                  padding-top: 40px;
                  padding-bottom: 40px;
                  text-align: center;
                "
                                >
                                    Depositar
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%; text-align: center">
                            <tr>
                                <td style="border-top: 1px solid black">Class Header's</td>
                                <td style="border-top: 1px solid black">Authorized</td>
                                <td style="border-top: 1px solid black">Guardean's</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script type="text/javascript">

        function printDiv(divName) {
            let printContents = document.getElementById(divName).innerHTML;
            Popup(printContents);
        }

        function Popup(data) {
            var mywindow = window.open();
            mywindow.document.write('<html><head><title>Print Area</title>');
            mywindow.document.write(`<style></style>`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.print();
            mywindow.close();

            return true;
        }
    </script>
  </body>
</html>
