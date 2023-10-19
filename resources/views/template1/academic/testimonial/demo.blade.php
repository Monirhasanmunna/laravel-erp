<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Testimonial Design</title>

    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
    </style>
  </head>
  <body>
    <div
      style="
        width: 1180px;
        margin: auto;
        border: 28px solid transparent;
        background-image: url({{Config::get('app.s3_url').Helper::academic_setting()->image}});
        background-repeat: no-repeat;
        background-position: center;
        background-size: 40%;
        border-image: url({{asset('academic/testimonial/border.png')}}) 30 round;
        padding-top: 1rem;
      "
    >
      <div
        style="
          width: 100%;
          padding: 1rem 2rem;
          margin: auto;
          background-color: #ffffffe3;
        "
      >
        <!-- college name start -->
        <div style="margin: auto; text-align: center; width: 100%">
          <h2 style="font-size: 32px; color: #000; text-transform: capitalize">
            {{ Helper::academic_setting()->school_name }}
          </h2>
          <p
            style="
              font-size: 15px;
              margin-top: 5px;
              color: rgb(46, 46, 46);
              text-transform: capitalize;
            "
          >
          {{Helper::school_info()->address}}
          </p>
        </div>
        <!-- college name ends -->

        <!-- collage logo starts -->
        <div
          style="
            width: 100%;
            margin: auto;
            text-align: center;
            margin-top: 10px;
          "
        >
          <img style="height: 85px" src="{{Config::get('app.s3_url').Helper::academic_setting()->image}}" alt="" />
        </div>
        <!-- collage logo ends -->

        <!-- testimonial name start -->
        <div
          style="
            width: 100%;
            margin: auto;
            text-align: center;
            margin-top: 10px;
          "
        >
          <h2
            style="
              font-size: 28px;
              font-weight: bold;
              background-image: linear-gradient(
                to right,
                #0f3c78,
                #24aec9,
                #0f3c78
              );
              width: max-content;
              margin: auto;
              padding: 5px 2rem;
              color: white;
              text-transform: uppercase;
            "
          >
            Testimonial
          </h2>
        </div>
        <!-- testimonial name ends-->

        <!-- testimonials sl no starts -->
        <div
          style="
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
          "
        >
          <div style="width: 100%;font-size: 19px;">
            <strong>SL-{{$serial_no}}</strong>
          </div>
          <div style="width: 100%; display: flex; justify-content: flex-end;font-size: 20px;">
            <span style="">
              <span style="font-weight: 700; text-transform: capitalize;"
                >Issue Date :</span
              >
              {{$issue_date}}
            </span>
          </div>
        </div>
        <!-- testimonials sl no ends -->

        <!-- testimonials pera starts -->
        <div style="width: 100%; margin-top: 1rem">
            {!! $content !!}
        </div>
        <!-- testimonials pera ends -->

        <!-- testimonials signature starts-->
        <div
          style="
            margin-top: 8rem;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 3rem;
          "
        >
          <div
            style="
              width: 200px;
              text-align: center;
              display: flex;
              flex-direction: column;
              gap: 6px;
            "
          >
            <div style="height: 40px">
              <img
                style="width: 100%; height: 100%"
                src="{{Helper::academic_setting()->signImage ? Config::get('app.s3_url').Helper::academic_setting()->signImage : ''}}"
                alt=""
              />
            </div>
            <div
              style="text-transform: capitalize; border-top: 2px dashed black"
            >
            {{@Helper::academic_setting()->signText}}
            </div>
          </div>
        </div>
        <!-- testimonials signature ends -->
      </div>
    </div>

    <script>
      window.print();
    </script>
  </body>
</html>
