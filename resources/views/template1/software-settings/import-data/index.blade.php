@extends('admin.layouts.app')
@push('css')
<style>
    .counter {
        background-color: #f5f5f5;
        padding: 20px 0;
        border-radius: 5px;
        border: 1px solid #FF5E00;
    }

    .count-title {
        font-size: 25px;
        font-weight: normal;
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;
    }

    .count-text {
        font-size: 16px;
        font-weight: 500;
        margin-top: 10px;
        margin-bottom: 0;
        text-align: center;
    }

    .fa-2x {
        margin: 0 auto;
        float: none;
        display: table;
        color: #FF5E00;
    }
</style>
@endpush
@section('content')
    <div class="main-panel" id="message-id">
        @include($adminTemplate.'.software-settings.software-settings-nav')
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)">Import Data</h4>
                    </div>

                    <div class="card-body">
                        @foreach($modules as $key => $module)
                        <div class="card {{$loop->iteration > 1? 'mt-3':''}}">
                            <div class="card-header">
                                <p>{{$key}} Import</p>
                                @if(array_sum($module) == 0)
                                    <a href="{{route('software-settings.import-data.import',$key)}}" class="btn btn-primary"><i class="fa fa-arrow-circle-up"></i>Import Data</a>
                                @endif
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    @foreach($module as $key => $field)
                                        <td style="border: none!important;">
                                            <div class="counter {{$loop->iteration > 1? 'ml-2':''}}">
                                                <i class="fa fa-check fa-2x"></i>
                                                <h2 class="timer count-title count-number">{{$field}}</h2>
                                                <p class="count-text ">{{str_replace("_"," ",$key)}}</p>
                                            </div>
                                        </td>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @endforeach
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
        });
        $(".deleteBtn").click(function () {
            $(".deleteForm").submit();
        });

        $('#home').removeClass('show').removeClass('active');
        $('#setting').addClass('show').addClass('active');


    </script>
@endpush
