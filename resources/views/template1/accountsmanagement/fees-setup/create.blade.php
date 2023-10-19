@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
    @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <div>
        <form action="{{route('fees-setup.store')}}" method="post">
            @csrf
            <div class="card new-table">
                    <div class="card-header">
                        <div class="card-title float-left">
                            <h6 style="color: #000000">Bulk Fees Setup</h6>
                        </div>
                        <a href="{{route('fees-setup.index')}}" class="btn btn-dark float-right"><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                    <div class="card-body">
                        @include('custom-blade.bulk-fees-search')
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Due Date</th>
                                    <th>Action</th>
                                </tr>

                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <select name="month[]" class="form-control month" id="" required>
                                            <option value="">Select Month</option>
                                            @foreach ($months as $key => $month)
                                                <option value="{{$key}}">{{$month}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" value="{{date('Y-01-15')}}" name="due_date[]" class="form-control due_date" id="">
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-info add-row"><i class=" fa fa-plus"></i> </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" style="border: 1px solid #0090e7!important;" placeholder="Head" class="form-control head" required>
                                    </td>
                                    <td>
                                        <input type="text" style="border: 1px solid #0090e7!important;"  placeholder="Amount" class="form-control amount" required>
                                    </td>
                                    <td colspan="3">
                                        <a href=""  class="btn btn-dark head-plus"><i class=" fa fa-plus"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="form-group">
                          <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i>Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $('#customTable').DataTable();
        $("#accounts_setting").addClass('active');
        $('#settings-nav').removeClass('d-none');
        $('#session_id').attr("required", "true");
        $('#section_id').attr("required", "true");

        $(document).on('change','.month',function(){
            let month = $(this).val();
            $(this).closest('tr').next('tr').find('.head').attr('name', `head-${month}[]`);
            $(this).closest('tr').next('tr').find('.amount').attr('name', `amount-${month}[]`);
        });
        $(document).on('click','.head-plus',function(e){
            e.preventDefault();
            let month = $(this).closest('td').prev('td').find('.amount').attr('name');
            var monthNum = month.replace(/\D/g, "");

            let html = ` <tr>
                            <td>
                                <input type="text" name="head-${monthNum}[]"  placeholder="Head" class="form-control head" required>
                            </td>
                            <td>
                                <input type="text" name="amount-${monthNum}[]"   placeholder="Amount" class="form-control amount" required>
                            </td>
                            <td>
                                <a href=""  class="btn btn-dark head-plus"><i class=" fa fa-plus"></i> </a>
                            </td>
                        </tr>`;

            $(this).closest('tr').after(html);
            $(this).closest('td').html(`<a href=""  class="btn btn-danger head-minus"><i class=" fa fa-minus"></i> </a>`);
        });


        $(document).on('click','.add-row',function(e){
            e.preventDefault();
            let html = `     <tr>
                                <td>
                                    <select name="month[]" class="form-control month" id="" required>
                                        <option value="">Select Month</option>
                                        @foreach ($months as $key => $month)
                                            <option value="{{$key}}">{{$month}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="date" value="{{date('Y-01-15')}}" name="due_date[]" class="form-control due_date" id="">
                                </td>
                                <td>
                                    <a href="" class="btn btn-danger delete-row"><i class=" fa fa-trash"></i> </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text"  placeholder="Head" class="form-control head" required>
                                </td>
                                <td>
                                    <input type="text"  placeholder="Amount" class="form-control amount" required>
                                </td>
                                <td>
                                    <a href=""  class="btn btn-dark head-plus"><i class=" fa fa-plus"></i> </a>
                                </td>
                            </tr>`;
            $('tbody').append(html);
        });

        $(document).on('change','.month',function (){
            let monthS = $(this).val();
            var now = new Date();
            var day = 15;
            var month = monthStr(monthS);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            $(this).parent().next().find('.due_date').val(today);
        });
        function monthStr(m){
            return m < 10 ? `0${m}`:m;
        }
        $(document).on('click','.delete-row',function (e){
            e.preventDefault();
            $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        $(document).on('click','.head-minus',function (e){
            e.preventDefault();
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush
