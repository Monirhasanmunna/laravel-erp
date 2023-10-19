@extends('frontent.theme1.layouts.web')

@section('content')
    <section class="StudentList section_gaps" style="padding-top: 51px !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="HeaderPart text-center">
                        <h2>Online Admission</h2>
                    </div>

                    <div class="StudentListHeader" style="border: 1px solid #efefef">
                        <form action="{{route('web.online_admission.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="session_id" class="pb-2"><strong>Academic Year</strong></label>
                                    <select name="session_id" id="session_id" class="form-control" required>
                                        <option value="">Select Session</option>
                                            @foreach ($sessions as $session)
                                            <option value="{{ $session->id }}">{{ $session->title }}</option>
                                            @endforeach
                                    </select>
                                </div>
    
                                <div class="col-lg-6">
                                    <label for="class_id" class="pb-2"><strong>Select Class</strong></label>
                                    <select name="class_id" id="class_id" class="form-control" required>
                                        <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                    </select>
                                </div>
    
                                <div class="col-lg-4">
                                    <div class="CustomeInput">
                                        <label>Name</label>
                                        <input type="text" name="name" placeholder="Enter Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="CustomeInput">
                                        <label>Father's Name</label>
                                        <input type="text" name="father_name" placeholder="Enter Father Name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="CustomeInput">
                                        <label>Mother's Name</label>
                                        <input type="text" name="mother_name" class="form-control" placeholder="Enter Mother Name" required>
                                    </div>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="gender" class="pb-2"><strong>Gender</strong></label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="religion" class="pb-2"><strong>Religion</strong></label>
                                    <select name="religion" id="religion" class="form-control" required>
                                        <option value="">Select Religion</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Christian">Christian</option>
                                    </select>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="blood" class="pb-2"><strong>Blood Group</strong></label>
                                    <select name="blood_group" id="blood" class="form-control" required>
                                        <option value="">Select blood group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="devision" class="pb-2"><strong>Devision</strong></label>
                                    <select class="form-control" name="division_id" id="division_id" required>
                                        <option value="" selected hidden>Select Devision</option>
                                        @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->name }} : {{ $division->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="district" class="pb-2"><strong>District</strong></label>
                                    <select class="form-control" name="district_id" id="district_id" disabled required>
                                        <option value="" selected hidden>Select District</option>
                                    </select>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="upazila" class="pb-2"><strong>Upazila</strong></label>
                                    <select class="form-control" name="upazila_id" id="upazila_id" disabled required>
                                        <option value="" selected hidden>Select Upazila</option>
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <div class="CustomeInput">
                                        <label>Date Of Birth</label>
                                        <input type="date" name="date_of_birth" placeholder="Enter Date Of Birth" required>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="CustomeInput">
                                        <label>Mobile</label>
                                        <input type="number" name="mobile_number" placeholder="Enter Mobile Number" required>
                                    </div>
                                </div>
    
                                <div class="col-lg-4 mt-3">
                                    <label for="customFile"><strong>Image</strong></label>
                                    <div class="custom-file pt-3">
                                        <input type="file" name="image" class="form-control" id="logoimage"
                                            onchange="document.getElementById('showlogoimage').src = window.URL.createObjectURL(this.files[0])"
                                            class="@error('image') is-invalid @enderror">
                                        {{-- <label class="custom-file-label" for="logoimage">Choose Image</label> --}}
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <img class="mt-2 d-none" id="showlogoimage" alt="image" width="100" height="100" />
                                </div>
                            </div>


                            <div class="mt-4 pb-4">
                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
                $("#division_id").on('change', function () {
                $("#district_id").empty();
                var id = $(this).val();
                console.log(id);
                var url = '/frontend/get-district/' + id;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $.each(response.districts, function (i, v) {
                            var items = `<option value='${v.id}'>${v.name} : ${v.bn_name}</option>`;
                            $("#district_id").append(items);
                        });

                        $("#district_id").removeAttr('disabled');
                    }
                });
            });

            $("#district_id").on('change', function () {
                $("#upazila_id").empty();
                var id = $(this).val();
                var url = '/frontend/get-upazila/' + id;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $.each(response.upazilas, function (i, v) {
                            var items = `<option value='${v.id}'>${v.name} : ${v.bn_name}</option>`;
                            $("#upazila_id").append(items);
                        });

                        $("#upazila_id").removeAttr('disabled');
                    }
                });
            });

            $("#logoimage").change(function () {
                $("#showlogoimage").removeClass('d-none');
            });
        });
    </script>
@endpush
