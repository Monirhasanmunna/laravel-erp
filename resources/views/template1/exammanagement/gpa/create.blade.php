@extends('admin.layouts.app')
@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendors/chosen/chosen.css')}}">
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
                        <form action="{{route('exam-management.setting.gpa-grading.store')}}" method="POST">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                    <label for="">Select Class</label>
                                    <select  name="class_id[]" class="form-control chosen-select" id="" multiple required>
                                        @foreach ($classes as $class)
                                            <option value="{{$class->id}}">{{$class->name}} ({{$class->session->title}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tbody>
                                        <tr>
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
                                                <input type="text" name="gpa_point[]" id="gpa_point" class="form-control" placeholder="Gpa Point" required>
                                            </td>
                                            <td>
                                                <input type="text" name="comments[]" id="comments" class="form-control" placeholder="Comments">
                                            </td>
                                            <td>
                                                <a href="" id="add-btn" class="btn btn-primary"><i class="fa fa-copy"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </thead>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i> Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/vendors/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen();
            $('#customTable').DataTable();
            $('#setting_menu').css('display','block');

            $('#add-btn').click(function(e){

                let rangeFrom = $('#range_form').val();
                let rangeTo = $('#range_to').val();
                let pointFrom = $('#point_from').val();
                let pointTo = $('#point_to').val();
                let gpaName = $('#gpa_name').val();
                let gpaPoint = $('#gpa_point').val();
                let comments = $('#comments').val();

                e.preventDefault();
                let html = `        <tr>
                                        <td>
                                            <input type="text" value="${rangeFrom}" name="range_form[]" class="form-control" placeholder="Range From" required>
                                        </td>
                                        <td>
                                            <input type="text" value="${rangeTo}" name="range_to[]" class="form-control" placeholder="Range To" required>
                                        </td>
                                        <td>
                                            <input type="text" value="${pointFrom}" name="point_from[]" class="form-control" placeholder="Point From" required>
                                        </td>
                                        <td>
                                            <input type="text" value="${pointTo}" name="point_to[]" class="form-control" placeholder="Point To" required>
                                        </td>
                                        <td>
                                            <input type="text" value="${gpaName}" name="gpa_name[]" class="form-control" placeholder="Gpa Name" required>
                                        </td>
                                        <td>
                                            <input type="text" value="${gpaPoint}" name="gpa_point[]" class="form-control" placeholder="Gpa Point" required>
                                        </td>
                                        <td>
                                            <input type="text" value="${comments}" name="comments[]" class="form-control" placeholder="Comments">
                                        </td>
                                        <td>
                                            <a href=""  class="btn btn-danger minus-btn"><i class="fa fa-minus"></i></a>
                                        </td>
                                    </tr>`;
                $('tbody').append(html);

            });

            $(document).on('click','.minus-btn',function(e){
                e.preventDefault();
                let $this = $(this);
                $this.closest('tr').remove();
            });
        });
    </script>
@endpush
