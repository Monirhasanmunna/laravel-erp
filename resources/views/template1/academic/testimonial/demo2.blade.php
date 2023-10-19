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
    <div style="margin-top: 5px">
      <div
        style="
          width: 1122px;
          margin: auto;
          border: 50px solid transparent;
          background-image: url({{Config::get('app.s3_url').Helper::academic_setting()->image}});
          background-repeat: no-repeat;
          background-position: center;
          background-size: 30%;
          border-image: url({{asset('academic/testimonial/border.png')}}) 50 round;
          border-image-repeat: round;
        "
      >
        <div
          style="
            width: 100%;
            padding: 0rem 1rem;
            margin: auto;
            background-color: #ffffffe3;
          "
        >
          <!-- college name start -->
          <div style="margin: auto; text-align: center; width: 100%">
            <h2
              style="
                font-size: 32px;
                color: #141414;
                text-transform: capitalize;
              "
            >
              Notre Dame College, Mymensingh
            </h2>
            <p
              style="
                font-size: 16px;
                margin-top: 5px;
                color: rgb(46, 46, 46);
                text-transform: capitalize;
              "
            >
              1/B Garib-E-Newaz Avenue, Sector-11, Uttara, Dhaka
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
            <img style="height: 85px" src="{{{{Config::get('app.s3_url').Helper::academic_setting()->image}}}}" alt="" />
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
                text-transform: capitalize;
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
            <div style="width: 100%">
              <strong style="font-size: 18px; color: #141414"
                >SL-130004102707323</strong
              >
            </div>
            <div style="width: 100%; display: flex; justify-content: flex-end">
              <span style="color: #141414">
                <span
                  style="
                    font-weight: 700;
                    text-transform: capitalize;
                    font-size: 18px;
                  "
                  >Issue Date :</span
                >
                null
              </span>
            </div>
          </div>
          <!-- testimonials sl no ends -->

          <!-- testimonials pera starts -->
          <div style="width: 100%; margin-top: 1rem">
            <p
              style="
                font-size: 18px;
                text-align: justify;
                line-height: 24px;
                color: #141414;
              "
            >
              This is to certify that
              <strong style="text-transform: uppercase">SHOHAG </strong> son of
              <strong style="text-transform: uppercase">MD OHID MOLLA</strong>
              and <strong>HOSNEARA</strong> Village
              <strong style="text-transform: uppercase">Bangladesh</strong> ,
              Post Office , Police Station / Upazilla , District was a student
              of this institute. He duly passed the
              <strong style="text-transform: uppercase">PEC</strong> Examination
              under the
              <strong style="text-transform: uppercase"
                >Board of Intermediate and Secondary Education, Dhaka</strong
              >
              held in
              <strong style="text-transform: uppercase">2023</strong> from
              <strong style="text-transform: uppercase">N/A</strong> group
              bearing Roll No.
              <strong style="text-transform: uppercase">2343</strong> and
              Registration No.
              <strong style="text-transform: uppercase">1</strong> in the
              session
              <strong style="text-transform: uppercase">2023</strong> and
              achieved
              <strong style="text-transform: uppercase">GPA 5.0</strong> . His
              date of birth is
              <strong style="text-transform: uppercase">15/12/1998</strong>.
            </p>
            <p
              style="
                margin-top: 1rem;
                font-size: 18px;
                line-height: 24px;
                color: #141414;
              "
            >
              He is a Bangladeshi by birth. To the best of my knowledge he did
              not take part in any subversive activity against the discipline of
              state.
            </p>
            <p
              style="
                margin-top: 1rem;
                font-size: 18px;
                line-height: 24px;
                color: #141414;
              "
            >
              He has a good moral character. I wish him every success in life.
            </p>
          </div>
          <!-- testimonials pera ends -->

          <!-- testimonials signature starts-->
          <div
            style="
              width: 100%;
              display: flex;
              justify-content: flex-end;
              margin-top: 1rem;
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
                  src="https://upload.wikimedia.org/wikipedia/commons/4/47/Ram_Mohan_Roy_Bengali_signature.svg"
                  alt=""
                />
              </div>
              <div
                style="
                  text-transform: capitalize;
                  border-top: 2px dashed #141414;
                  color: #141414;
                "
              >
                Head Teacher
              </div>
            </div>
          </div>
          <!-- testimonials signature ends -->
        </div>
      </div>
    </div>
 <script>
      window.print();
    </script> 
  </body>
</html>
