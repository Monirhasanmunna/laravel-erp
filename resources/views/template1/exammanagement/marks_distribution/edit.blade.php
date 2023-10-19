@extends('admin.layouts.app')

@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate.'.exammanagement.topmenu_exammanagement')

        <div>
            <div class="card new-table">
                {{-- mark dist setup end--}}
                <div class="card-header">
                    <p>Mark DIstribution Edit</p>
                </div>
                <form id="mark-dist-update" action="{{route('exam-management.setting.marks-dist.update',$markDist->id)}}" method="POST">
                    @csrf
                <div class="card-body">
                    <table class="table table-bordered" id="mark-dist-table">
                        <thead>
                        <tr>
                            <th>Subject/Short Code</th>
                            <th>Total Mark</th>
                            <th>Pass Mark</th>
                            <th>Take</th>
                            <th>Grace</th>
                        </tr>
                        </thead>
                        <tbody id="mark-dist-list">
                            <tr>
                                <td>{{$markDist['classSubject']['subject']['sub_name']}}</td>
                                <td>
                                    <input class="form-control" type="text" name="total_mark" value="{{$markDist->total_mark}}" id="">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="pass_mark" value="{{$markDist->pass_mark}}" id="">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="take" value="{{$markDist->take}}" id="">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="grace" value="{{$markDist->grace}}" id="">
                                </td>
                            </tr>
                            @foreach($markDist->details as $detail)
                            <tr>
                                <td>
                                    <select  id="sub_marks_dist_type_id" name="mark_dist_type_id[]" class="form-control sub_marks_dist_type_id">
                                        <option value="">Select Type</option>
                                        @foreach ($types as $item)
                                            <option value="{{ $item->id }}" {{$detail->sub_marks_dist_type_id == $item->id ? 'selected':'' }}>{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" placeholder="Mark" name="marks[]" value="{{$detail->mark}}" class="form-control mark" required>
                                </td>
                                <td>
                                    <input type="number" placeholder="Passmark" name="pass_marks[]" value="{{$detail->pass_mark}}" class="form-control pass-mark"
                                           required>
                                </td>
                                @if($loop->iteration == 1)
                                    <td colspan="3">
                                        <a href="" class="btn btn-dark marks-plus"><i class=" fa fa-plus"></i> </a>
                                    </td>
                                @else
                                    <td colspan="3">
                                        <a href=""  class="btn btn-danger marks-minus"><i class=" fa fa-minus"></i> </a>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group mt-3">
                        <a href="" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                        <button type="submit"   class="btn btn-primary float-right mb-2"><i class="fa fa-save"></i>Update</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

            $('#mark-dist-update').submit(function(event){
                if(!confirm("Associated Marks Input Will be deleted!")){
                    event.preventDefault();
                }
            });


            $(document).on('click', '.marks-plus', function(e) {

                e.preventDefault();
                let html = `<tr>
                                <td>
                                    <select name="mark_dist_type_id[]" id="sub_marks_dist_type_id" class="form-control sub_marks_dist_type_id">
                                        <option value="">Select Type</option>
                                        @foreach ($types as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="marks[]" placeholder="Mark" class="form-control mark" required>
                                </td>
                                <td>
                                    <input type="text" name="pass_marks[]"  placeholder="Passmark" class="form-control pass-mark" required>
                                </td>
                                <td colspan="3">
                                    <a href=""  class="btn btn-danger marks-minus"><i class=" fa fa-minus"></i> </a>
                                </td>
                           </tr>`;

                $('#mark-dist-list').append(html);
            });

            $(document).on('click', '.marks-minus', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

        });
    </script>
@endpush
