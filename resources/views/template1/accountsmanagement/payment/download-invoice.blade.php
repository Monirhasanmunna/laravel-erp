<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
            font-size: 12px;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }

        .column {
            float: left;
            width: 33.33%;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

    </style>

</head>
<body>
<div class="main" style="border: 1px solid #000000;padding: 20px">


    <div class="row">
        <div class="column">
            <img src="{{Config::get('app.s3_url').$academicSetting->image}}" alt="" width="60"/>
        </div>
        <div class="column" style="text-align: center">
            <p style="border: 1px solid black;padding: 5px" >OFFICE COPY</p>
        </div>
        <div class="column" style="text-align: right">
            <h4>{{$schoolInfo->name}}</h4>
            <h4>{{$schoolInfo->address}}</h4>
            <h4>{{$schoolInfo->eiin_no}}</h4>
        </div>
    </div>



    <table width="100%">
        <tr>
            <td>
                <p><b>Student:</b> {{$payment->student->name}} ({{$payment->student->id_no}})</p>
                <p><b>Class:</b>{{$payment->student->class->name}}-{{$payment->student->shift->name}}-{{$payment->student->section->name}}</p>
                <p><b>Phone:</b> {{@$payment->student->mobile_number}}</p>
            </td>
            <td>
                <div style="float:right">
                    <p>#INV{{$payment->invoice_no}}</p>
                    <p>{{$payment->date}}</p>
                    <p>{{\App\Helper\Helper::getMonthFromNumber($payment->month)}}</p>
                </div>
            </td>
        </tr>
    </table>

    <br/>

    <table width="100%">
        <thead style="background-color: #efefef;">
        <tr>
            <th>Fees Type</th>
            <th>Month</th>
            <th>Due date</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payment->feeReceivedDetails as $fees)
            <tr>
                <td align="center">{{$fees->feesType->type}}</td>
                <td align="center">{{\App\Helper\Helper::getMonthFromNumber($fees->source->month)}}</td>
                <td align="center">{{$fees->source->due_date}}</td>
                <td align="right">{{$fees->amount}}</td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td colspan="2"></td>
            <td align="right">Subtotal</td>
            <td align="right">{{number_format($payment->invoice_total,2)}}</td>
        </tr>
        @if($payment->discount)
            <tr>
                <td colspan="2"></td>
                <td align="right">Discount</td>
                <td align="right">{{number_format($payment->discount,2)}}</td>
            </tr>
        @endif
        @if($payment->advance)
            <tr>
                <td colspan="2"></td>
                <td align="right">Advance</td>
                <td align="right">{{number_format($payment->advance,2)}}</td>
            </tr>
        @endif
        @if($payment->due_amount)
            <tr>
                <td colspan="2"></td>
                <td align="right">Due</td>
                <td align="right">{{number_format($payment->due_amount,2)}}</td>
            </tr>
        @endif
        <tr>
            <td colspan="2"></td>
            <td align="right">Payable</td>
            <td align="right">{{number_format($payment->total_payable,2)}}</td>
        </tr>

        <tr>
            <td>
                <p style="text-transform: capitalize">In Word:{{\App\Helper\Helper::convertNumberToWord($payment->paid_amount)}} <span style="text-transform: lowercase">tk.</span> Only</p>
            </td>
            <td></td>
            <td align="right">Paid Amount</td>
            <td align="right" class="gray">{{number_format($payment->paid_amount,2)}}</td>
        </tr>
        </tfoot>

    </table>


    <div class="signature-div" style="margin-top: 25px">
        <p style="float: left;border-top: 1px solid #000000">Authorized Sign</p>
        <p style="float: right;border-top: 1px solid #000000">Authorized Sign</p>
        <div style="clear: both;"></div>
    </div>

</div>


<br>


<div class="main" style="border: 1px solid #000000;padding: 20px">

    <div class="row">
        <div class="column">
            <img src="{{Config::get('app.s3_url').$academicSetting->image}}" alt="" width="60"/>
        </div>
        <div class="column" style="text-align: center">
            <p style="border: 1px solid black;padding: 5px" >STUDENT COPY</p>
        </div>
        <div class="column" style="text-align: right">
            <h4>{{$schoolInfo->name}}</h4>
            <h4>{{$schoolInfo->address}}</h4>
            <h4>{{$schoolInfo->eiin_no}}</h4>
        </div>
    </div>

    <table width="100%">
        <tr>
            <td>
                <p><b>Student:</b> {{$payment->student->name}} ({{$payment->student->id_no}})</p>
                <p><b>Class:</b>{{$payment->student->class->name}}-{{$payment->student->shift->name}}-{{$payment->student->section->name}}</p>
                <p><b>Phone:</b> {{@$payment->student->mobile_number}}</p>
            </td>
            <td>
                <div style="float:right">
                    <p>#INV{{$payment->invoice_no}}</p>
                    <p>{{$payment->date}}</p>
                    <p>{{\App\Helper\Helper::getMonthFromNumber($payment->month)}}</p>
                </div>
            </td>
        </tr>
    </table>

    <br/>

    <table width="100%">
        <thead style="background-color: #efefef;">
        <tr>
            <th>Fees Type</th>
            <th>Month</th>
            <th>Due date</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($payment->feeReceivedDetails as $fees)
            <tr>
                <td align="center">{{$fees->feesType->type}}</td>
                <td align="center">{{\App\Helper\Helper::getMonthFromNumber($fees->source->month)}}</td>
                <td align="center">{{$fees->source->due_date}}</td>
                <td align="right">{{$fees->amount}}</td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td colspan="2"></td>
            <td align="right">Subtotal</td>
            <td align="right">{{number_format($payment->invoice_total,2)}}</td>
        </tr>
        @if($payment->discount)
            <tr>
                <td colspan="2"></td>
                <td align="right">Discount</td>
                <td align="right">{{number_format($payment->discount,2)}}</td>
            </tr>
        @endif
        @if($payment->advance)
            <tr>
                <td colspan="2"></td>
                <td align="right">Advance</td>
                <td align="right">{{number_format($payment->advance,2)}}</td>
            </tr>
        @endif
        @if($payment->due_amount)
            <tr>
                <td colspan="2"></td>
                <td align="right">Due</td>
                <td align="right">{{number_format($payment->due_amount,2)}}</td>
            </tr>
        @endif
        <tr>
            <td colspan="2"></td>
            <td align="right">Payable</td>
            <td align="right">{{number_format($payment->total_payable,2)}}</td>
        </tr>

        <tr>
            <td>
                <p style="text-transform: capitalize">In Word:{{\App\Helper\Helper::convertNumberToWord($payment->paid_amount)}} <span style="text-transform: lowercase">tk.</span> Only</p>
            </td>
            <td></td>
            <td align="right">Paid Amount</td>
            <td align="right" class="gray">{{number_format($payment->paid_amount,2)}}</td>
        </tr>
        </tfoot>

    </table>


    <div class="signature-div" style="margin-top: 25px">
        <p style="float: left;border-top: 1px solid #000000">Authorized Sign</p>
        <p style="float: right;border-top: 1px solid #000000">Authorized Sign</p>
        <div style="clear: both;"></div>
    </div>

</div>

</body>
</html>
