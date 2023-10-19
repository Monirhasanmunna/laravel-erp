@extends('admin.layouts.app')
@push('css')

@endpush
@section('content')
    <div class="main-panel">

        @include($adminTemplate.'.routinemanagement.routineNav')

        <div class="card new-table mb-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title float-left">
                        <h6 style="color: black"> View Class Routine</h6>
                    </div>
                    {{-- <a href="{{route('classroutine.create')}}" class="btn btn-primary float-right"> <i class="fa fa-plus"></i> Create</a> --}}

                </div>
                <div class="card-body">
                    <div class="card-body">
                        <form action="{{route('classroutine.index')}}" method="GET">
                            @include('custom-blade.search-class-routine2')
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(@$data['routines'])

        <div class="card new-table" id="routine-card">
            <div class="card-header">
                <p>Class Routine</p>
                <form action="{{route('classroutine.print')}}" id="print-form" method="GET">
                    <input type="hidden"  name="data" id="print-data">
                    <button class="btn btn-success" id="print-btn"><i class="fa fa-print"></i>Print</button>
                </form>
            </div>
            <div class="card-body">
               <table class="table"> 
                        <tr>
                            <th class="text-center" width="10%">Day</th>
                            @foreach ($data['periods'] as $item)
                                <th class="text-center">{{$item->name}}</th>
                            @endforeach
                        </tr>
                    @forelse ($data['routines'] as $day)
                        <tr>
                            <td class="text-center">{{$day['day']}}</td>

                            @foreach ($day['periods'] as $item)
                                <td class="text-center">
                                    @if($item != null)
                                        <p style="font-weight: 400">{{$item['period_time']['start_time']}} - {{$item['period_time']['end_time']}}</p>
                                        <p style="font-weight: 400">{{$item['class_subject']['subject']['sub_name']}} ({{$item['teacher']['name']}})</p>
                                    @else
                                    @endif
                           
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <p class="text-danger text-center">No Routine Found</p>
                            </td>
                        </tr>
                    @endforelse
                   
               </table>
            </div>
        </div>
        @endif


    </div>
@endsection

@push('js')
    <script>
    $(document).ready(function() {
        $('#customTable').DataTable();

        $('#print-btn').click(function(e){
            e.preventDefault();
            let searchParams = new URLSearchParams(window.location.search)

            let class_id    = searchParams.get('class_id');
            let section_id  = searchParams.get('section_id');
            let category_id = searchParams.get('category_id');
            let group_id    = searchParams.get('group_id');
            
            let params = {
                            class_id:class_id,
                            section_id:section_id,
                            category_id:category_id,
                            group_id:group_id,
                        };

            $('#print-data').val(JSON.stringify(params));
            $('#print-form').submit();
        });
     
    });
    </script>
@endpush
