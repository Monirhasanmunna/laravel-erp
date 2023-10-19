@extends('admin.layouts.app')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/chosen/chosen.css') }}">
@endpush
@section('content')
    <style>
        input[type="radio"],
        input[type="checkbox"] {
            box-sizing: border-box;
            padding: 0;
        }

        .form-check-input {
            position: absolute;
            margin-top: 0.3rem;
            margin-left: -4.25rem;
        }

        .form-check {
            min-height: 18px;
            padding-left: 0rem;
        }

        .list-group-item {
            background-color: transparent;
            border-bottom: 1px solid #ddd;
            border-radius: 0;
            color: #6C7293;
        }
    </style>
    <div class="main-panel ">
        @include($adminTemplate . '.academic.academicnav')
        <nav class="navbar navbar-expand-lg navbar-light bg-white mt-2 z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav navbar-nav-hover ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('classes.show', $id) }}" id="nav-hov">
                                Shifts
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('category.index', $id) }}" id="nav-hov">
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('section.index', $id) }}" id="nav-hov">
                                Sections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('group.index', $id) }}" id="nav-hov">
                                Groups
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('academic/class/subject*') ? 'custom_nav' : '' }}">
                            <a class="nav-link" href="{{ route('subject.list', $id) }}" id="nav-hov">
                                Subject
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div>
            <div class="card new-table" style="margin-top: 20px;">
                <div class="card-header">
                    <h4>Subject Assign</h4>
                    <a href="" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> <i
                            class="fa fa-plus"></i>Add Subject</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('subject.subjectAdd') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $id }}">
                        <div class="row">
                            @foreach ($subjectTypes as $type)
                                <input type="hidden" name="type_id[]" value="{{ $type['id'] }}">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header  bg-primary">
                                            <p class="text-light">{{ $type['name'] }}</p>
                                        </div>
                                        <div class="card-body">
                                            <table class="table">
                                                <thead id="sub-list">
                                                    <tr>
                                                        <input type="hidden" name="" class="type_id"
                                                            value="{{ $type['id'] }}">
                                                        <td>
                                                            <select name="subject_id-{{ $type['id'] }}[]" id=""
                                                                class="form-control chosen-select">
                                                                <option value="">Select Subject</option>
                                                                @foreach ($subjects as $subject)
                                                                    <option value="{{ $subject->id }}">
                                                                        {{ $subject->sub_name }}
                                                                        ({{ $subject->sub_code }})</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <a href="" class="btn btn-primary add-btn"><i
                                                                    class="fa fa-plus"></i></a>
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary mt-4"><i class="fa fa-save"></i>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subject Create</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('subject.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Subject Name</label>
                            <input type="text" class="form-control" name="name" id="" required>
                        </div>
                        <div class="form-group">
                            <label for="">Subject Code</label>
                            <input type="text" class="form-control" name="code" id="" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    modal --}}
@endsection



@push('js')
    <script src="{{ asset('assets/vendors/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen();
            $('.add-btn').click(function(e) {
                e.preventDefault();
                let typeId = $(this).closest('tr').find('.type_id').val();
                let tRow = `<tr>
                                <td>
                                    <select name="subject_id-${typeId}[]" id="" class="form-control chosen-select">
                                        <option value="">Select Subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->sub_name }} ({{ $subject->sub_code }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <a href=""  class="btn btn-danger minus-btn"><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>`;

                $(this).closest('tr').parent().append(tRow);
                $(".chosen-select").chosen();

            });

            $(document).on('click', '.minus-btn', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });


        });
    </script>
@endpush
