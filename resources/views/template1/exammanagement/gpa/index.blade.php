@extends('admin.layouts.app')

@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.exammanagement.topmenu_exammanagement')

        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title" style="color:black">Gpa Grading</p>
                        <div class="float-right">
                            <a href="{{route('exam-management.setting.gpa-grading.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Create New</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label for="">Select Session</label>
                                <select name="session_id" class="form-control" id="session_id">
                                    @foreach($sessions as $session)
                                        <option value="{{$session->id}}">{{$session->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <table id="customTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Class</th>
                                    <th>Gpa Name</th>
                                    <th>Gpa Point</th>
                                    <th>Range From</th>
                                    <th>Range To</th>
                                    <th>Point From</th>
                                    <th>Point To</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
            $('#setting_menu').css('display','block');

            let sessionId = $('#session_id').val();

            $("#session_id").change(function (){
                let sessionId = $(this).val();
                getGrades(sessionId);
            });

            const getGrades = (sessionId) => {
                $.get("{{route('exam-management.setting.gpa-grading.get-grades')}}",{session_id:sessionId},function (data){
                    let html = '';

                    $.each(data,function (i,v){
                        let tRow = '';
                        $.each(v,function (idx,val){
                            tRow += `<tr>
                                        <td>${val.gpa_name}</td>
                                        <td>${val.gpa_point}</td>
                                        <td>${val.range_from}</td>
                                        <td>${val.range_to}</td>
                                        <td>${val.point_from}</td>
                                        <td>${val.point_to}</td>
                                        <td>${val.comment}</td>
                                    </tr>`;
                        });

                        let btnLink = "{{route('exam-management.setting.gpa-grading.edit',100)}}";
                        let newLink = btnLink.replace(100,i);

                        html += `<tr>
                                    <td rowspan="${v.length + 1}">
                                        <a href="${newLink}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                    </td>
                                    <td rowspan="${v.length + 1}">${v[0].class.name}</td>
                                </tr>
                                    ${tRow}
                                `;
                    });
                    $('tbody').html(html);
                });
            }
            getGrades(sessionId);

        });
    </script>
@endpush
