@extends('admin.layouts.app')
@push('css')
    <style>
        .chosen-container-single .chosen-single div b {
            margin-top: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate.'.accountsmanagement.topmenu_accountsmanagement')

        <div>
            <form action="{{route('scholarship.store')}}" method="POST">
                @csrf
                <div class="card new-table">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title float-left">
                                <div>
                                    <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Scholarship Create</h4>
                                </div>

                            </div>
                            <a href="{{ Route('scholarship.index') }}" class="btn btn-dark mr-2 float-right"><i
                                    class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="">Select Fee Type</label>
                                    <select name="fees_type_id" class="form-control" id="fees_type_id" required>
                                        <option value="">Select Fee Type</option>
                                        @foreach ($feetypes as $feeType)
                                            <option value="{{ $feeType->id }}">{{ $feeType->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Select Student</label>
                                    <select name="student_id" class="form-control chosen-select" id="student_id" required>
                                        <option value="">Select Student</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }}
                                                <b>({{ $student->id_no }})</b></option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <a href="" class="btn btn-primary" id="process-btn" style="margin-top:33px "><i
                                            class="fa fa-arrow-right"></i> Process</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card new-table">
                    <div class="card-body">
                        <div class="col-md-8 px-0">
                            <div class="form-group mb-2">
                                <label for="">Note</label>
                                <textarea class="form-control" name="note" id="" cols="4" rows="4"></textarea>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="6%">SL</th>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Half</th>
                                        <th>Full</th>
                                        <th width="16%">Discount</th>
                                    </tr>
                                </thead>
                                <tbody id="ref-fees-tbody">
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>



    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
            $('.chosen-select').chosen();
            $("#accounts_setting").addClass('active');
            $('#settings-nav').removeClass('d-none');

        });
    </script>
@endpush
