@extends('admin.layouts.app')
@push('css')
<style>
    tr {
        height: 40px;
        padding-top: 0px;
        padding-bottom: 0px;
    }

</style>
@endpush
@section('content')

<div class="main-panel" id="marks-id" style="overflow: scroll">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')

    <div class="card new-table">
        <div class="card-header">
            <div class="card-title float-left">
                <h6 style="color: #009FFF">Student Absent Sms</h6>
            </div>
        </div>
        <div class="card-body">
            <form action="#" id="search-form" method="Get">
                @include('custom-blade.search-student-absent-sms')
            </form>
        </div>
    </div>

</div>



@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#search-form').submit(function(e){
            e.preventDefault();
            let form = $(this);
            console.log(form.serialize());
        })
    });

</script>
@endpush
