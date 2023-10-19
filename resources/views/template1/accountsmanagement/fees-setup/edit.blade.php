@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
    @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')
    <div>

            @csrf
            <div class="card new-table">
                    <div class="card-header">
                        <div class="card-title float-left">
                            <h6 style="color: #000000">Fees Setup Edit</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('fees-setup.update',$fees->id)}}" method="post">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="">Select Fees Type</label>
                                    <select name="fees_type" class="form-control mb-2" id="">
                                        @foreach($feesTypes as $type)
                                            <option value="{{$type->id}}" {{$fees->fees_type_id == $type->id? "selected":''}}>{{$type->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Due Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($fees->details as $fee)
                                    <tr>
                                        <input type="hidden" class="tr-count" value="{{$fee->feesHead->count()}}">
                                        <td>
                                            <select name="month[]" class="form-control month" id="" required>
                                                <option value="">Select Month</option>
                                                @foreach ($months as $key => $month)
                                                    <option value="{{$key}}" {{$fee->month == $key? 'selected':''}}>{{$month}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" value="{{$fee->due_date}}" name="due_date[]" class="form-control due_date" id="">
                                        </td>
                                        <td>
                                            @if($loop->iteration == 1)
                                                <a href="" class="btn btn-info add-row"><i class=" fa fa-plus"></i> </a>
                                            @else
                                                <a href="" class="btn btn-danger delete-row"><i class=" fa fa-trash"></i> </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @foreach($fee->feesHead as $head)
                                        <tr>

                                            <td>
                                                <input type="text" style="border: 1px solid #0090e7!important;" value="{{$head->head}}" name="head-{{$fee->month}}[]" placeholder="Head" class="form-control head" required>
                                            </td>
                                            <td>
                                                <input type="number" style="border: 1px solid #0090e7!important;" value="{{$head->amount}}" name="amount-{{$fee->month}}[]"  placeholder="Amount" class="form-control amount" required>
                                            </td>
                                            <td colspan="3">
                                                @if($loop->last)
                                                    <a href=""  class="btn btn-dark head-plus"><i class=" fa fa-plus"></i> </a>
                                                @else
                                                    <a href=""  class="btn btn-danger head-minus"><i class=" fa fa-minus"></i> </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                </tbody>
                            </table>

                            <div class="form-group">
                              <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i>Update</button>
                            </div>
                        </form>
                    </div>
                </div>
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
        //change month
        $(document).on('change','.month',function(){
            let month = $(this).val();
            $(this).closest('tr').next('tr').find('.head').attr('name', `head-${month}[]`);
            $(this).closest('tr').next('tr').find('.amount').attr('name', `amount-${month}[]`);
        });

        //add parent row
        $(document).on('click','.add-row',function(e){
            e.preventDefault();
            let html = `     <tr>
                                <input type="hidden" class="tr-count" value="2">
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
                                    <input type="number"  placeholder="Amount" class="form-control amount" required>
                                </td>
                                <td>
                                    <a href=""  class="btn btn-dark head-plus"><i class=" fa fa-plus"></i> </a>
                                </td>
                            </tr>`;
            $('tbody').append(html);
        });
        //add child row
        $(document).on('click','.head-plus',function(e){
            e.preventDefault();
            let month = $(this).closest('td').prev('td').find('.amount').attr('name');
            var monthNum = month.replace(/\D/g, "");


            let html = ` <tr>
                            <td>
                                <input type="text" name="head-${monthNum}[]"  placeholder="Head" class="form-control head" required>
                            </td>
                            <td>
                                <input type="number" name="amount-${monthNum}[]"   placeholder="Amount" class="form-control amount" required>
                            </td>
                            <td>
                                <a href=""  class="btn btn-dark head-plus"><i class=" fa fa-plus"></i> </a>
                            </td>
                        </tr>`;

            $(this).closest('tr').after(html);
            $(this).closest('td').html(`<a href=""  class="btn btn-danger head-minus"><i class=" fa fa-minus"></i> </a>`);
        });

        //change due date by month
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

        //delete parent row
        $(document).on('click','.delete-row',function (e){
            e.preventDefault();
            let count = $(this).closest('tr').find('.tr-count').val();

            for (let i=0;i < count;i++){
                let newCount = count - i;
                removeTr($(this),newCount);
            }
            $(this).closest('tr').remove();
        });

        function removeTr($this,count){
            let next = '';
            for (let i = 0; i < count; i++) {
                next += '.next()';
            }
            let remove = `$this.closest('tr')${next}.remove()`;
            eval(remove);
        }

        //delete child row
        $(document).on('click','.head-minus',function (e){
            e.preventDefault();
            $(this).closest('tr').remove();
        });

    });
</script>
@endpush
