@extends('admin.layouts.app')

@push('css')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
    rel="stylesheet">

    <style>
        .btn-class{
            height: 33px;
            margin-top: 10px;
            margin-left: 10px;
        }
    </style>
@endpush

@section('content')
<div class="main-panel">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')
    <div>
        <div class="card new-table">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 style="color:rgba(0, 0, 0, 0.5)">Add Contact</h4>
                    <a href="{{route('sms.contact.index')}}" class="btn btn-sm btn-primary "><i class="fa-solid fa-arrow-left"></i>Back</a>
                </div>
                <div class="content-wrapper text-primary">
                    <form action="{{route('sms.contact.store')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="">
                                    <label for="title" class="form-label">File Name</label>
                                    <input type="text" class="form-control mt-2" name="file_name" id="title" placeholder="Enter File Name Here" required>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-primary ">Add</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4" id="number-col">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="title" class="form-label">Number</label>
                                    <button class="btn-sm btn-primary" type="button" onclick="addRow()"><i class="fa-solid fa-plus"></i></button>
                                </div>
                                {{-- <input type="number" class="form-control mt-2" name="numbers[]" id="title" placeholder="Enter Number Here" required> --}}
                                <div class="d-flex row-input" >
                                    <input type="number" class="form-control mt-2" name="numbers[]" id="title" placeholder="Enter Number Here" required>
                                    <button class="btn-sm btn-danger btn-class deleteRow" type="button">X</button>
                                </div>
                            </div>
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
        function addRow()
        {
            $("#number-col").append(`
                <div class="d-flex row-input">
                    <input type="number" class="form-control mt-2" name="numbers[]" id="title" placeholder="Enter Number Here" required>
                    <button class="btn-sm btn-danger btn-class deleteRow" type="button">X</button>
                </div>
            `);
        }

        $(".deleteRow").click(function(){
            $(this).closest('.row-input').remove();
        });

        $(document).click(function(){
            $(".deleteRow").click(function(){
                $(this).closest('.row-input').remove();
            });
        });
    </script>
@endpush
