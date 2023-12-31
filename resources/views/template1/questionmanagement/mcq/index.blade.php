@extends('admin.layouts.app')
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css"/>
    <style>
    .bg_color{
        background-color: #42B3C5 !important;
        color:white !important;
    }

    table thead th {
        background: #ddd !important;
        text-transform: capitalize;
        color: #777 !important;
        font-weight: 600;
    }

    table td {
        border: 1px solid #e5e1e1 !important;
        color: black !important;
        font-weight: 400;
        padding: 6px 10px !important;
    }
    </style>
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.questionmanagement.questionnav')
        <div>
            <div class="card new-table">
                <div class="card">

                    <div class="card-header">
                        <div class="card-title float-left">
                            <h6 style="color: black">MCQ Qustion List</h6>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <table id="customTable" class="table table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Class</th>
                                    <th class="text-center">Subject</th>
                                    <th class="text-center">Chapter</th>
                                    <th class="text-center">Questions</th>
                                    <th class="text-center">Answer</th>
                                    <th width=10% class="text-center">Action</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @foreach ($chapters as $key => $chapter)
                                <tr>
                                    <td class="text-center">{{$chapter->ins_class->name}}</td>
                                    <td class="text-center">{{$chapter->subject->sub_name}}</td>
                                    <td class="text-center">{{$chapter->name}}</td>
                                    <td class="text-center">
                                        @foreach ($chapter->questions as $q)
                                        <p class="mt-2">{!! Str::limit($q->question, 90, '...')!!}</p>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @foreach ($chapter->questions as $q)
                                        <p class="mt-2">
                                            @if ($q->answer == 1)
                                                {{$q->option1}}
                                            @elseif($q->answer == 2)
                                                {{$q->option2}}
                                            @elseif($q->answer == 3)
                                                {{$q->option3}}
                                            @else
                                                {{$q->option4}}
                                            @endif
                                        </p>
                                        @endforeach
                                    </td>
                                    <td width=10% class="text-center">
                                        <a class="btn btn-sm btn-info" href="javascript:void(0)" onclick="showData({{$chapter->id}})"><i class="fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- grade edit modal --}}
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="groupModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><span class="text-warning">Question List</span>  <span id="question-info" class="ml-3 text-center"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding-right: 26px; padding-top: 24px;">
                  <span aria-hidden="true" class="text-white">&times;</span>
                </button>
              </div>
            <div class="modal-body p-0">
                <div class="" style="padding-top:0px;height:auto !important;" id="wrapperr">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });

        function showData(id) {
        $.ajax({
            url: '/question/mcq-show/' + id,
            type: 'GET',
            success: (data) => {
                $("#wrapperr").empty();

                var question='';
                 $.each(data.questions,function(i,v){
                    
                    var answer = '';

                    if(v.answer == 1){
                       answer = `${v.option1}`
                    }else if(v.answer == 2){
                        answer = `${v.option2}`
                    }else if(v.answer == 3){
                        answer = `${v.option3}`
                    }else{
                        answer = `${v.option4}`
                    }

                    question += `<tr>
                                    <td scope="row" class='text-center p-2'>${i+1}</td>
                                    <td class='p-2'>${v.question}</td>
                                    <td class='p-2 text-center'>${v.option1}</td>
                                    <td class='p-2 text-center'>${v.option2}</td>
                                    <td class='p-2 text-center'>${v.option3}</td>
                                    <td class='p-2 text-center'>${v.option4}</td>
                                    <td class='p-2 text-center'>${answer}</td>
                                </tr>
                    `;
                });
            

                var html = `
                    <div class="content-wrapper bg-light" style='padding-top:21px;'>
                        <div class="row mt-2">
                            <table id="" class="table table-bordered table-responsive table-striped" style="width:100%">
                            <thead class=''>
                                <tr>
                                    <th class="text-center p-2 bg_color" width='5%'>SL</th>
                                    <th class="text-center p-2 bg_color">Question</th>
                                    <th class="text-center p-2 bg_color">Opt(One)</th>
                                    <th class="text-center p-2 bg_color">Opt(Two)</th>
                                    <th class="text-center p-2 bg_color">Opt(Three)</th>
                                    <th class="text-center p-2 bg_color">Opt(Four)</th>
                                    <th class="text-center p-2 bg_color">Answer</th>
                                </tr>
                            </thead>
                                ${question}
                            <tbody>
                                    
                            </tbody>
                        </table>
                        </div>
                     </div>
                `;

                var questionInfo = `
                    ( <span id="class" class='text-white'> Class : ${data.ins_class.name}</span>, 
                    <span id="sub" class='text-white ml-1'> Subject : ${data.subject.sub_name}</span>, 
                    <span class='text-white ml-1' id="chapter"> Chapter : ${data.name}</span> )
                `;
                
                $("#question-info").html(questionInfo);
                $("#wrapperr").html(html);
                $("#showModal").modal('show');
            }
        });
    }
    </script>
@endpush
