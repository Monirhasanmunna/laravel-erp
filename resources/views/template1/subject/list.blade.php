@extends('admin.layouts.app')
@push('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate . '.academic.academicnav')

        <nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 mt-2 py-1 mb-2 nested-menu shadow-sm rounded">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="navbar-nav navbar-nav-hover ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('classes.show', $id) }}" id="nav-hov">
                                Shifts
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('academic/class/category*') ? 'active' : '' }}">
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


        <div class="card new-table">
            <div class="card-header">
                <div class="float-left">
                    <p>Assign Subject List <b>({{$class_name->name}})</b></p>
                </div>
                <div class="float-right">
                    <a class="btn btn-primary py-2" href="{{ route('subject.index', $id) }}">Add Subject <i
                            class="fa fa-plus"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($subjectTypeData as $key => $type)
                        @if($type->count() > 0)
                            <input type="hidden" name="subject_type" class="subject_type" value="{{$key}}">
                            <div class="col-md-4 {{$loop->iteration > 3 ? 'mt-4':''}}">
                                <div class="card">
                                    <div class="card-header  bg-primary">
                                        <p class="text-light">{{ $key }}</p>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group" id="sortable-{{$key}}" style="cursor:pointer">
                                            @foreach ($type as $data)
                                                <li class="list-group-item list-item-{{ $key }}" data-id="{{$data->id}}">
                                                    <p>{{ $data->subject->sub_name }} ({{$data->subject->sub_code}})</p>
                                                    <a href="{{route('subject.delete-class-subject',$data->id)}}" style="position: absolute;right: 10px;top: 10px;" class="btn btn-danger">X</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.subject_type').each(function (){
                let type = $(this).val();

                $(`#sortable-${type}`).sortable({
                    placeholder: "highlight",
                    update: function() {
                        sendOrderToServer(type);
                    }
                });
            });

            function sendOrderToServer(type) {

                var order = [];
                let path = "{{route('subject.order-subjects')}}";

                $(`.list-item-${type}`).each(function(index,element) {
                    order.push({
                        id: $(this).attr('data-id'),
                        position: index+1
                    });
                });

                $.post(path,{order},function (data){
                    if(data.status === 200){
                        toastr.success('Order Updated');
                    }
                });

            }
        });
    </script>
@endpush
