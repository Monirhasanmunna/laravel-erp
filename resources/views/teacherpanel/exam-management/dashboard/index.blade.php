@extends('teacherpanel.layout.app')

@section('content')
    <div class="main-panel mt-3" id="marks-id">
        @include('teacherpanel.exam-management.nav-bar')
        <style type="text/css">
            .sub-table th,
            .sub-table td {
                padding: 3px !important;
                font-size: 16px;
            }
        </style>
        <div>
            <div class="card new-table">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" style="color:rgba(0, 0, 0, 0.5)"> Dashboard</h4>
                    </div>

                    <div class="card-body">
                        <h2>Exam Dashboard</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
    
    </script>
@endpush
