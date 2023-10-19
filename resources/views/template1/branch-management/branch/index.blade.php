@extends('admin.layouts.app')
@push('css')
@endpush
@section('content')
    <div class="main-panel">
        @include('template1.branch-management.nav')


        <div class="card new-table">
            <div class="card-header">
                <p>Branch Overview</p>
                <select name="branch_id" class="form-control col-md-2" id="branch_id">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-2">
                        <table class="table">
                            <thead>
                                <th class="text-center">Session</th>
                                <th class="text-center">Student</th>
                                <th class="text-center">Male</th>
                                <th class="text-center">Female</th>
                            </thead>
                            <tbody id="std-info">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="card new-table">
            <div class="card">
                <div class="card-header">
                    <h4>Branch List</h4>
                    <a href="javascript:void(0)" id="addBtn" class="btn btn-primary float-right"><i
                            class="fa fa-plus"></i>Create new</a>
                </div>
                <div class="card-body p-0 px-2">
                    <table class="table table-bordered" id="customTable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Principal Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Students</th>
                                <th>Teachers</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="branch-info">
                          

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="branchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="branchModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="branch-form" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="branch_id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" name="name" class="form-control">
                            <small class="text-danger" id="nameError"></small>
                        </div>
                        <div class="form-group">
                            <label for="principal_name">Principal Name</label>
                            <input id="principal_name" type="text" name="principal_name" class="form-control">
                            <small class="text-danger" id="principalNameError"></small>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input id="phone" type="text" name="phone" class="form-control">
                            <small class="text-danger" id="phoneError"></small>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input id="address" type="text" name="address" class="form-control">
                            <small class="text-danger" id="addressError"></small>
                        </div>
                        <button type="submit" class="btn btn-primary" id="modalBtn"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //get student info
            let branch_id = $('#branch_id').val();
            getStudentInfo(branch_id);

            function getStudentInfo(branch_id) {
                $.get("{{ route('branch-management.branch.student-info') }}", {
                    branch_id
                }, function(data) {
                    let hTML = "";
                    $.each(data,function(idx,val){
                        hTML += `<tr>
                                    <td class="text-center">${val.name}</td>
                                    <td class="text-center">${val.students}</td>
                                    <td class="text-center">${val.male}</td>
                                    <td class="text-center">${val.female}</td>
                                 </tr>`;
                    });
                    $('#std-info').html(hTML);
                });
            }


            $('#branch_id').change(function(){
                let branch_id = $(this).val();
                getStudentInfo(branch_id);
            });


            $('#branchModal').on('hidden.bs.modal', function() {
                $("form#branch-form :input").each(function() {
                    let input = $(this);
                    if (input.attr('type') === 'text') {
                        input.val('');
                    }
                });
            })

            getBranches();

            function getBranches() {
                $.get("{{ route('branch-management.branch.get') }}", function(data) {
                    let html = '';
                    $.each(data, function(i, val) {
                        html += `<tr>
                                    <td>${i+1}</td>
                                    <td>${val.name}</td>
                                    <td>${val.principal_name}</td>
                                    <td>${val.phone}</td>
                                    <td>${val.address}</td>
                                    <td>${val.student_count}</td>
                                    <td>${val.teacher_count}</td>
                                    <td>
                                        <a href="" data-id="${val.id}" id="editBtn" class="btn btn-info"><i class="fa fa-edit"></i></a>
<!--                                        <a href="" data-id="${val.id}" id="deleteBtn" class="btn btn-danger"><i class="fa fa-trash"></i></a>-->
                                    </td>
                                  </tr>`;
                    });
                    $('#branch-info').html(html);
                    $('#customTable').DataTable();
                });
            }

            $('#addBtn').click(function() {
                $('#branchModal').modal('show');
                $('#branchModalLabel').text('Create Branch');
                $('#modalBtn').attr('action-type', 'store').text('Submit');
            });

            $('#modalBtn').click(function(e) {
                e.preventDefault();
                let action = "{{ route('branch-management.branch.store') }}";
                let name = $('#name').val();
                let principal_name = $('#principal_name').val();
                let phone = $('#phone').val();
                let address = $('#address').val();
                let actionType = $(this).attr("action-type");

                if (actionType === 'store') {
                    $.post(action, {
                        name,
                        principal_name,
                        phone,
                        address
                    }, function(data) {
                        if (data.errors) {
                            $('#nameError').text(data.errors.name);
                            $('#principalNameError').text(data.errors.principal_name);
                        }
                        if (data.success) {
                            $('#branchModal').modal('hide');
                            $('#name').val('');
                            $('#principal_name').val('');
                            $('#phone').val('');
                            $('#address').val('');
                            $('#nameError').text('');
                            $('#principalNameError').text('');
                            getBranches();
                        }
                    });
                } else {
                    let id = $('#branch_id').val();

                    $.post("{{ route('branch-management.branch.update') }}", {
                        id,
                        name,
                        principal_name,
                        phone,
                        address
                    }, function(data) {
                        $('#branchModal').modal('hide');
                        $('#name').val('');
                        $('#nameError').text('');
                        getBranches();
                    });

                }



            });

            $(document).on('click', '#editBtn', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let action = "{{ route('branch-management.branch.edit') }}";

                $.get(action, {
                    id
                }, function(data) {
                    if (data) {
                        $('#branch_id').val(data.id);
                        $('#branchModal').modal('show');
                        $('#name').val(data.name);
                        $('#principal_name').val(data.principal_name);
                        $('#phone').val(data.phone);
                        $('#address').val(data.address);
                        $('#modalBtn').text('Update');
                        $('#branchModalLabel').text('Update Branch');
                    }
                });
            });

            $(document).on('click', '#deleteBtn', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let action = "{{ route('branch-management.branch.delete') }}";
                let $this = $(this);

                $.get(action, {
                    id
                }, function(data) {
                    $this.closest('tr').remove();
                    getBranches();
                });
            });


        });
    </script>
@endpush
