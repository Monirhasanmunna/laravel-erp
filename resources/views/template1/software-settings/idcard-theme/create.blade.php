@extends('admin.layouts.app')
@push('css')
<style>
    .form-check-inline {
        display: inline-block;
        margin-right: 10px;
        /* Adjust as needed */
    }

    /* Target the label element inside .form-check-inline */
    .form-check-inline .form-check-label {
        display: inline;
        margin-left: 5px;
        /* Adjust as needed */
    }

</style>
@endpush
@section('content')
<div class="main-panel">
    @include($adminTemplate.'.software-settings.software-settings-nav')
    <div class="card new-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 style="color:rgba(0, 0, 0, 0.5)">Add Id Card</h4>
            <a href="{{route('software-settings.idcardtheme.index')}}" class="btn btn-sm btn-primary "><i
                    class="fa-solid fa-arrow-left"></i>Back</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8" style="border-right:1px solid black">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card"
                                style="background-image: url(https://res.cloudinary.com/dnzbdnw4b/image/upload/v1675951543/front_plfkqz.png); background-repeat: no-repeat; background-size: cover; float: left; margin-left: 20px;width: 311px;height: 406px;margin-top:20px; margin-bottom:-60px;margin:0 auto;">
                                <div>
                                    <div style="height:65px">
                                        <h2
                                            style="color: white;font-size: 12px;font-weight: 600;text-align: center;text-transform: uppercase; padding-top:10px;padding: 5px 0px 0px 0px; line-height:11px ">
                                            {{@Helper::academic_setting()->school_name}}</h2>
                                        <h3
                                            style="color: white; font-size: 11px;text-align: center; margin-right:-10px; margin-top: 5px; margin-bottom:5px; text-transform: uppercase;margin-bottom: 5px;font-weight: 500;">
                                            identity card</h3>
                                    </div>

                                    <div style="width: 50px;height: 50px;margin: auto;">
                                        <img style="width: 100%;height: 100%;border-radius: 100px;object-fit: cover;justify-content: center;align-items: center;"
                                            src="{{asset('male.png')}}" alt="card image">
                                    </div>
                                    <h1
                                        style="font-size: 14px;text-align: center;font-weight: bold;margin-top: 6px; margin-bottom:3px;color: #1c1c1c;">
                                        MD. Monir Hasan</h1>
                                    <table
                                        style="margin-top: 20px; margin-left: 5px; text-transform: uppercase; width: 301px;height: 178px; line-height: 10px;border:0 !important;">
                                        <tr style="border:0 !important;">
                                            <td
                                                style="font-size:10px;text-transform: capitalize; font-weight: 400;padding: 0px 19px;">
                                                Roll</td>
                                            <td style="font-size:10px;text-transform: capitalize; font-weight: 400;">
                                                1001</td>
                                        </tr>
                                        <tr style="border:0 !important;">
                                            <td width="35%"
                                                style="font-size:10px;text-transform: capitalize; font-weight: 400;padding: 0px 19px;">
                                                Father</td>
                                            <td style="font-size:10px; text-transform: capitalize; font-weight: 400;"> :
                                                Rafiq Hasan</td>
                                        </tr>
                                        <tr style="border:0 !important;">
                                            <td
                                                style="font-size:10px;text-transform: capitalize; font-weight: 400;padding: 0px 19px;">
                                                Mother</td>
                                            <td style="font-size:10px; text-transform: capitalize; font-weight: 400;"> :
                                                Nilima Hasan</td>
                                        </tr>
                                        <tr style="border:0 !important;">
                                            <td
                                                style="font-size:10px;text-transform: capitalize; font-weight: 400;padding: 0px 19px;">
                                                Cell</td>
                                            <td style="font-size:10px;text-transform: capitalize; font-weight: 400;"> :
                                                01771501865</td>
                                        </tr>
                                    </table>
                                    <table width="100%" style="border: 0;">
                                        <tr>
                                            <td><img style="width:50px;height:auto;"
                                                    src="{{Config::get('app.s3_url').@Helper::academic_setting()->image}}"
                                                    alt=""></td>
                                            <td
                                                style="font-size:10px;text-transform: uppercase; font-weight: 400;text-align:right">
                                                ID : 1000001&nbsp;&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="p-2" style="border:1px solid rgba(0, 0, 0, 0.184)">
                                    <lable style="color:rgba(0, 0, 0, 0.5)">Background Image</lable>
                                    <input type="file" class="form-control" accept="image/*" name="background_image" />
                                    <p class="pt-1" style="font-size: 10px;color: rgba(0, 0, 0, 0.578)">Accepted Files
                                        PNG</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="p-2" style="border:1px solid rgba(0, 0, 0, 0.184)">
                                <div class="form-group">
                                    <lable style="color:rgba(0, 0, 0, 0.5)">Title</lable>
                                    <input type="text" class="form-control" id="title" placeholder="Enter Id Card Tile">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="p-2" style="border:1px solid rgba(0, 0, 0, 0.184)">
                                <lable style="color:rgba(0, 0, 0, 0.5)">Items</lable>
                                <select class="form-control" name="sms_template" id="sms_template">
                                    <option value="father_name">Father Name</option>
                                    <option value="mother_name">Mother Name</option>
                                    <option value="class">Class</option>
                                    <option value="mobile_number">Mobile</option>
                                    <option value="blood_group">Blood Group</option>
                                </select>
                            </div>
                        </div>
                    </div>
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
