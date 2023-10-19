@extends('admin.layouts.app')
@push('css')
    {{-- <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
            }

            th, td {
            text-align: left;
            padding: 8px;
            }
    </style> --}}
@endpush
@section('content')
    <div class="main-panel">
        @include($adminTemplate.'.exammanagement.topmenu_exammanagement')

        <div class="card new-table">
            <div class="card-body">
                <h6>Transcript</h6>
                <hr>
                
               <form id="student-form" action="{{ route('exam-management.report.transcript.transcript-result') }}" method="POST">
                @csrf
                @include('custom-blade.search-student3')
            </form>
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


@push('js')
<script>
    


    $('#exam_report_nav').show();
    $('#setting_menu').hide();

</script>
@endpush
