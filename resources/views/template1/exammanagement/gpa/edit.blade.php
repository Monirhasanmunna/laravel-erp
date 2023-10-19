@extends('admin.layouts.app')
@push('css')
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.exammanagement.topmenu_exammanagement')

        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Gpa Grading</p>
                        <div class="float-right">
                            <a href="{{route('exam-management.setting.gpa-grading.index')}}" class="btn btn-dark"><i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('exam-management.setting.gpa-grading.update',$id)}}" method="POST">
                            @csrf
                            <table class="table">
                                <thead>
                                    <thead>
                                        <tr>
                                            <th>Range From</th>
                                            <th>Range To</th>
                                            <th>Point From</th>
                                            <th>Point To</th>
                                            <th>Gpa Name</th>
                                            <th>Gpa Point</th>
                                            <th>Comment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grades as $grade)
                                            <tr>
                                                <td>
                                                    <input type="text" name="range_form[]" id="range_form" class="form-control" value="{{$grade->range_from}}" placeholder="Range From" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="range_to[]" id="range_to" class="form-control" value="{{$grade->range_to}}" placeholder="Range To" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="point_from[]" id="point_from" class="form-control" value="{{$grade->point_from}}" placeholder="Point From" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="point_to[]" id="point_to" class="form-control" value="{{$grade->point_to}}" placeholder="Point To" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="gpa_name[]" id="gpa_name" class="form-control" value="{{$grade->gpa_name}}" placeholder="Gpa Name" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="gpa_point[]" id="gpa_point" class="form-control" value="{{number_format($grade->gpa_point,2)}}" placeholder="Gpa Point" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="comments[]" id="comments" class="form-control" value="{{@$grade->comment}}" placeholder="Comments">
                                                </td>
                                                <td>
                                                    @if($loop->last)
                                                        <a href="" class="btn btn-primary plus"><i class="fa fa-plus"></i></a>
                                                    @else
                                                        <a href="" class="btn btn-danger minus"><i class="fa fa-minus"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </thead>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i> Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#setting_menu').css('display','block');

            $(document).on('click','.plus',function (e){
                e.preventDefault();
                let html = `<tr>
                                <td>
                                    <input type="text" name="range_form[]" id="range_form" class="form-control" placeholder="Range From" required>
                                </td>
                                <td>
                                    <input type="text" name="range_to[]" id="range_to" class="form-control" placeholder="Range To" required>
                                </td>
                                <td>
                                    <input type="text" name="point_from[]" id="point_from" class="form-control" placeholder="Point From" required>
                                </td>
                                <td>
                                    <input type="text" name="point_to[]" id="point_to" class="form-control" placeholder="Point To" required>
                                </td>
                                <td>
                                    <input type="text" name="gpa_name[]" id="gpa_name" class="form-control" placeholder="Gpa Name" required>
                                </td>
                                <td>
                                    <input type="text" name="gpa_point[]" id="gpa_point" class="form-control"  placeholder="Gpa Point" required>
                                </td>
                                <td>
                                    <input type="text" name="comments[]" id="comments" class="form-control"  placeholder="Comments">
                                </td>
                                <td>
                                    <a href="" class="btn btn-primary plus"><i class="fa fa-plus"></i></a>
                               </td>
                         </tr>`;
               $('tbody').append(html);
               $(this).closest('td').html('<a href="" class="btn btn-danger minus"><i class="fa fa-minus"></i></a>');
            });

            $(document).on('click','.minus',function (e){
               e.preventDefault();
               let $this = $(this);
               $this.closest('tr').remove();
            });
        });
    </script>
@endpush
