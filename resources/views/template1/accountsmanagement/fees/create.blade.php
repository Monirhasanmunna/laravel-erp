@extends('admin.layouts.app')

@section('content')
<div class="main-panel">
@include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <form action="{{ isset($fees)? route('fees.update',$fees->id) : route('fees.store') }}" method="post">
        @csrf
        <div class="card new-table">
        <div class="card-header">
                    <h5 class="text-primary">Student Wise Fees Add</h5>
                </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($fees))
                        @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div>
                                        <label for="student_id">Student</label>
                                    </div>
                                    <div>
                                        <select class="form-control chosen-select" id="student_id" name="student_id" required>
                                            <option selected>Select Student</option>
                                            @foreach ($students as $std)
                                            <option value="{{$std->id}}"
                                                @if (isset($fees))
                                                    {{($fees->student->id == $std->id)? 'selected':''}}
                                                @endif
                                            >{{$std->name}} ({{$std->id_no}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="fees_type_id">Fees Type</label>
                                    <div>
                                        <select class="custom-select" id="fees_type_id" name="fees_type_id" required>

                                            <option selected>Select Fees Type</option>
                                            @foreach ($feesType as $feesType)
                                            <option value="{{$feesType->id}}"
                                                @if (isset($fees))
                                                    {{($fees->feesType->id == $feesType->id)? 'selected': ''}}
                                                @endif
                                            >{{$feesType->type}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <select name="month" class="form-control" id="">
                                        @foreach ($months as $key => $month)
                                            <option value="{{$key}}">{{$month}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" required name="due_date" value="{{$fees->due_date ?? date("Y-m-d")}}">
                                </div>
                            </div>
                            @if (!isset($fees))
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="">Number Of Row</label>
                                        <input type="number" id="table_number" class="form-control" required>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-2">
                                <a href="javascript:void(0)" style="margin-top: 27px" onclick="increase()" class="btn btn-primary"><i class="fa fa-arrow-circle-right"></i>Proccess</a>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
        <div class="card new-table mb-3 d-none" id="head-card">
            <div class="card">
                <div class="card-body">
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <div class="form-check py-0 my-0">
                                            <input type="checkbox" class="form-check-input" checked id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th scope="col">Head</th>
                                    <th scope="col">Amount</th>

                                    <th></th>
                                </tr>
                            </thead>


                            <tbody>
                                @if (isset($fees))
                                @foreach ($fees->feeDetails as $key => $ad)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" checked name="check[]"
                                                id="exampleCheck1" value="{{ $key }}">
                                            <label class="form-check-label" for="exampleCheck1"></label>
                                        </div>
                                    </td>


                                    <td>
                                        <input type="text" class="form-control" value="{{ $ad->head }}" name="head[]"
                                            id="head_{{ $key }}" placeholder="Roll Number" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $ad->amount }}"
                                            name="amount[]" id="amount_{{ $key }}" placeholder="amount" required>
                                    </td>


                                    <td><button type="button" tabindex="-1" class="btn btn-info btn-xs delete"
                                            title="Delete This Row" onclick="removeRow(this)"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <br>
                        @if(isset($fees))
                        <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-alt-circle-up"></i>Update</button>
                        @else
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Submit</button>
                        @endif



                    </div>


                </div>
            </div>
        </div>
    </form>


</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
        $(".chosen-select").chosen();
    });
    $('input[type="checkbox"]').change(function () {
        var checked = $(this).is(':checked');
        var input = $(this).closest('tr').find('input[type="text"]');
        var select = $(this).closest('tr').find('select,input');
        input.prop('required', checked)
        select.prop('required', checked)
    })



    function increase() {
        $('#head-card').removeClass('d-none');
        var table_number = $('#table_number').val();
        if (table_number > 0) {
            $('tbody').html('')
            for (let i = 0; i < table_number; i++) {
                increaseto(i)
            }
        }
    }

    function increaseto($i) {
        let html;
        html += '<tr>'
        html +=
            '<td><div class="form-check"><input type="checkbox" class="form-check-input"  checked name="check[]" id="exampleCheck1" value="' +
            $i + '"><label class="form-check-label" for="exampleCheck1"></label></div></td>'
        html += '<td><input type="text" class="form-control" name="head[]" id="head_' + $i +
            '" placeholder="Head" required></td>'
        html += '<td><input type="text" class="form-control" name="amount[]" id="amount_' + $i +
            '" placeholder="Amount" required></td>'


        html +=
            '<td><button type="button" tabindex="-1" class="btn btn-info btn-xs delete" title="Delete This Row" onclick="removeRow(this)"><i class="fa fa-trash"></i></button></td>'
        html += '</tr>'
        $('tbody').append(html);

    }

    function removeRow(el) {
        $(el).parents("tr").remove()
    }
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("#accounts_setting").addClass('active');
    $('#settings-nav').removeClass('d-none');

</script>
@endpush
