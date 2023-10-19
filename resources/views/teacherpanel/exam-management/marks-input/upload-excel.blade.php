@extends('teacherpanel.layout.app')

@section('content')
    <div class="main-panel" id="marks-id">
        @include($adminTemplate.'.exammanagement.topmenu_exammanagement')

        <div>
            <div class="card new-table">
                <div class="card-header">
                    <p class="card-title" style="color:rgba(0, 0, 0, 0.5)">Upload Input Marks</p>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('exam-management.marks.upload-excel-store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-3">
                                    <label for="">Upload File</label>
                                    <input type="file" name="file" value="{{old('file')}}" class="form-control" id="">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-arrow-up"></i> Upload</button>
                        </form>

                        @if (@$data)
                        <form action="{{route('exam-management.marks.upload-excel-insert')}}" method="POST">
                            @csrf
                            <table class="table mt-4">
                                <thead>
                                    <tr>
                                        @foreach ($data['headings'] as $heading)
                                            @if ($loop->iteration > 5)
                                                <input type="hidden" name="short_code_name[]" value="{{$heading}}">
                                            @endif
                                            <th>{{$heading}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['body'] as $key => $body)  
                                    <input type="hidden" name="exam_name" value="{{$body[0]}}" >                                      
                                    <input type="hidden" name="subject_name" value="{{$body[1]}}" >                                      
                                    <input type="hidden" name="student_id[]" value="{{$body[2]}}" >      
                                    @php
                                        $count = count($body);
                                        $index = 0;
                                    @endphp
                                    
                                    @for ($i = 5; $i < $count; $i++)
                                        @php
                                        $index++;
                                        @endphp
                                        <input type="hidden" name="short_code-{{$index}}[]" value="{{$body[$i]}}" >  
                                    @endfor
                                    @if($loop->iteration > 5)
                                    @endif                                
                                    <tr>
                                        @foreach ($body as $item)

                                            <td>
                                                {{$item}}
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary mt-2"><i class="fa fa-save"></i> Save</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#customTable').DataTable();
        });
    </script>
@endsection
