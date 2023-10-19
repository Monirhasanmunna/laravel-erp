@extends('admin.layouts.app')
@section('content')

<div class="main-panel" id="marks-id">
    @include($adminTemplate.'.smsmanagement.topmenu_sms_management')
    <div class="card new-table">
        <div class="card-header">
            <div class="card-title float-left">
                <h4 style="color:rgba(0, 0, 0, 0.5)">Contact List</h4>
            </div>
            <div class="card-title float-right">
                <a href="{{route('sms.contact.excel-import')}}"  class="btn btn-info mr-2" ><i class="fa-solid fa-file-excel"></i>From Excel</a>
                <a href="{{route('sms.contact.sms-form')}}"  class="btn btn-success mr-2" ><i class="fa-solid fa-address-book"></i>From Contact</a>
                <a href="{{route('sms.contact.create')}}"  class="btn btn-primary mr-2" ><i class="fa-solid fa-circle-plus"></i>Add Contact</a>
            </div>
        </div>
        <div class="card-body">
                <table id="customTable" class="table table-striped table-bordered table-responsive" >
                    <thead>
                        <tr>
                            <th class="text-center" width='10%'>SL</th>
                            <th>File</th>
                            <th width='30%'>Total Number</th>
                            <th class="text-center" width='10%'>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $key => $contact)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td>{{$contact->file_name}}</td>
                            <td>{{$contact->numbers->count()}}</td>
                            <td class="text-center">
                                <a href="{{route('sms.contact.edit',$contact->id)}}" class="btn-sm btn-danger"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="javascript:void(0)" onclick="deleteFunction({{$contact->id}})" class="btn-sm btn-primary"><i class="fa-solid fa-trash"></i></a>
                            </td>

                            <form id="deleteForm_{{$contact->id}}" action="{{route('sms.contact.destroy')}}" method="POST" style="display: none">
                                @csrf 
                                <input type="hidden" name="id" value="{{$contact->id}}">
                            </form>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#customTable').DataTable();
    });
    
    function deleteFunction(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Deleted!',
                'Your contact has been deleted.',
                'success'
                )

                $(`#deleteForm_${id}`).submit();
            }
        })
    }

</script>
@endpush
