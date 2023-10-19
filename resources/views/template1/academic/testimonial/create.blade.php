<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800&family=Roboto:wght@100;300;400;500;700;900&display=swap");
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Roboto", sans-serif;
      }
    </style>
  </head>
  <body>
    <div style="width: 80%; margin: auto; padding: 40px;">
      <form action="{{route('academic.testimonial.store',$student->id)}}" method="POST">
        @csrf
      <div
        style="
          border: 15px solid #1c6077;
          width: 100%;
          padding: 4px;
          float: left;
          margin-bottom: 40px;
        "
      >
        <div
          style="
            border: 2px solid #1c6077;
            width: 100%;
            padding: 1px;
            float: left;
            
          "
        >
          <div
            style="
              border: 2px solid #1c6077;
              width: 100%;
              padding: 4px;
              float: left;
            "
          >
            <div style="width: auto; float: left">
              <img style="height: 120px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" />
              <p style="font-size: 15px; color: #000">SL No. {{$student->id_no}}</p>
              <p style="font-size: 15px; color: #000">ID. {{$student->roll_no}}</p>
            </div>
            <div style="width: 70%; float: left">
              <div
                style="
                  width: 100%;
                  float: left;
                  text-align: center;
                  margin-top: 16px;
                "
              >
                <h1 style="font-size: 25px; font-weight: 600">{{ Helper::academic_setting()->school_name }}</h1>

                <p style="font-size: 14px; color: #000; margin-top: 10px">{{Helper::school_info()->address}}</p>
                <p style="font-size: 16px; color: #000">
                  Email : {{Helper::school_info()->email}}
                </p>
              </div>
            </div>
            <div style="width: 100%; float: left">
              <h1
                style="
                  text-align: center;
                  font-size: 40px;
                  color: rgb(20, 20, 20);
                  margin-top: 25px;
                "
              >
                Testimonial
              </h1>
            </div>
            <div
              style="
                width: 100%;
                float: left;
                margin-top: 15px;
                padding: 0px 30px;
              "
            >
              
                <div class="row">
                  <div class="col-lg-3">
                      <div class="form-group">
                          <label for="name">Name</label><br>
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{@$student->name}}">
                      </div>
                  </div>
                  <div class="col-lg-3">
                      <div class="form-group">
                          <label for="father_name">Father's Name</label><br>
                          <input type="text" class="form-control @error('father_name') is-invalid @enderror" id="father_name" name="father_name" value="{{@$student->father_name}}">
                      </div>
                  </div>

                  <div class="col-lg-3">
                      <div class="form-group">
                          <label for="mother_name">Mother's Name</label><br>
                          <input type="text" class="form-control @error('mother_name') is-invalid @enderror" id="mother_name" name="mother_name" value="{{@$student->mother_name}}">
                      </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                        <label for="result">Registration</label><br>
                        <input type="number" class="form-control @error('registration_no') is-invalid @enderror" name="registration_no" id="registration_no">
                    </div>
                  </div>

                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="roll_no">Roll</label><br>
                          <input type="number" class="form-control @error('roll') is-invalid @enderror" name="roll" id="roll_no" value="{{@$student->roll_no}}">
                      </div>
                  </div>

                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="phone">Division</label>
                          <select class="form-control @error('division_id') is-invalid @enderror" name="division_id" id="division_id">
                              <option value="" selected hidden>select one</option>
                              @foreach ($divisions as $division)
                              <option value="{{ $division->id }}">
                                  {{ $division->name }} : {{ $division->bn_name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="phone">District</label>
                          <select class="form-control @error('district_id') is-invalid @enderror" name="district_id" id="district_id">
                              @foreach ($districts as $district)
                              <option value="{{ $district->id }}">
                                  {{ $district->name }} : {{ $district->bn_name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="phone">Upazila</label>
                          <select class="form-control @error('upazila_id') is-invalid @enderror" name="upazila_id" id="upazila_id">
                              @foreach ($upazilas as $upazila)
                              <option value="{{ $upazila->id }}">
                                  {{ $upazila->name }} : {{ $upazila->bn_name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="phone">Session</label>
                          <select class="form-control @error('session_id') is-invalid @enderror" name="session_id" id="session">
                              @foreach ($sessions as $session)
                              <option value="{{ $session->id }}">
                                  {{ $session->title }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
  
                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="phone">Class</label>
                          <select class="form-control @error('class_id') is-invalid @enderror" name="class_id" id="class_id">
                              @foreach ($classes as $class)
                              <option value="{{ $class->id }}">
                                  {{ $class->name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
  
                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="phone">Exam</label>
                          <select class="form-control @error('semister_id') is-invalid @enderror" name="semister_id" id="semister_id">
                              @foreach ($semesters as $semester)
                              <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <div class="col-lg-3 pt-2">
                    <div class="form-group">
                        <label for="result">Board</label><br>
                        <input type="text" class="form-control @error('board_name') is-invalid @enderror" name="board_name" id="board_name">
                    </div>
                  </div>
  
                  <div class="col-lg-3 pt-2">
                      <div class="form-group">
                          <label for="result">Result</label><br>
                          <input type="text" class="form-control @error('result') is-invalid @enderror" name="result" id="result">
                      </div>
                  </div>

                </div>
            </div>

            <input type="hidden" name="student_id" value="{{$student->id}}">
            
            <div style="width: 40%; float: left; text-align: center;margin-bottom:40px;">
                <div style="width: 30%; margin: auto; height: 2px; background-color: #000;margin-top: 100px;"></div>
                <p style="margin-top: 10px;font-size: 15px;">Office Assistant</p>
            </div>

            <div style="width: 20%;float: left;margin: auto;margin-bottom: 20px;margin-top:90px">
                {{-- @if(asset('academic/qrcode/'.Helper::getInstituteId().'_qrcode.png')) <img style="height: 90px;" src="{{asset('academic/qrcode/'.Helper::getInstituteId().'_qrcode.png')}}"> @endif --}}
            </div>

            <div style="width: 40%; margin: auto; float: left; text-align: center;margin-bottom: 40px;">
                <div style="width: 50%; margin: auto; height: 2px; background-color: #000;margin-top: 100px;"></div>
                <p style="margin-top: 10px;font-size: 15px;">{{Helper::academic_setting()->signText}}
                </p>
            </div>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-sm btn-primary pull-right">Update</button>
  </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $("#division_id").on('change', function () {
            $("#district_id").empty();
            var id = $(this).val();
            var url = '/get-district/' + id;
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $.each(response.districts, function (i, v) {
                        var items = `<option value='${v.id}'>${v.name} : ${v.bn_name}</option>`;
                        $("#district_id").append(items);
                    });
                }
            });
        });


        $("#district_id").on('change', function () {
            $("#upazila_id").empty();
            var id = $(this).val();
            var url = '/get-upazila/' + id;
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $.each(response.upazilas, function (i, v) {
                        var items = `<option value='${v.id}'>${v.name} : ${v.bn_name}</option>`;
                        $("#upazila_id").append(items);
                    });
                }
            });
        });
    </script>
  </body>
</html>
