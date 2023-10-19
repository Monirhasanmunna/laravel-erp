<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>School</title>
  <!-- bootstart 5 cdn -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <!-- font awesome cds -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- swiper slider css cdn -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <!--  -->

  <!-- costom css  -->
  <link rel="stylesheet" href="{{asset('assets/theme-2/css/style.css')}}" />
</head>

<body>
  <!-- Back to Top Start -->
  <a href="#" class="back_to_top"><i class="fa fa-long-arrow-up"></i></a>
  <!-- Back to Top end -->

  <!-- top bar start -->
  <section class="topbar">
    <div class="container mx-auto">
      <div class="d-flex justify-content-between align-items-center gap-3">
        <ul class="topbar_Ul">
          <li>
            <a href="mailto: example@gmail.com"><i class="fa-sharp fa-solid fa-envelope"></i>
              <span>Email: example@gmail.com</span>
            </a>
          </li>
          <li>
            <a href="tel: 2222-333-7889"><i class="fa-solid fa-phone"></i>
              <span>Contact: 2222-333-7889</span>
            </a>
          </li>
          <li>
            <p>
              <i class="fa-regular fa-clock"></i>
              <span>
                <span class="work">Work time:</span> 9:00AM - 16:00PM</span>
            </p>
          </li>
        </ul>
        <ul class="topbar_ul_2">
          <li>
            <a href="#" class="studentLoginBtn">Student</a>
          </li>
          <span>|</span>
          <li>
            <a href="#">Teacher</a>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- top bar end -->

  <!-- navbar start -->

  <nav class="navbar navbar-expand-lg">
    <div class="container mx-auto">
      <a class="navbar-brand" href="#">
        <img class="logoImg"
          src="https://themesfamily.com/html-template/edugarden/assets/img/logo-black.png" alt="" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-sharp fa-solid fa-bars navbar-toggler-icon"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Dropdown
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li>
                <a class="dropdown-item" href="#">Something else here</a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Disabled</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- navbar end -->

  <!-- hero carousel start -->
  <section class="heroslider">
    <div class="swiper" id="heroSwiper">
      <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
          <div class="gradient"></div>
          <img class="w-100 h-100"
            src="https://www.dell.org/wp-content/uploads/2019/05/todays-college-student-Twitter.jpg" alt="" />

          <div class="herotitlewrapper">
            <div class="container mx-auto">
              <p>The best team for</p>
              <h2>
                <span class="education"></span>
              </h2>
              <div class="button">
                <button>Buy now</button>
              </div>
            </div>
          </div>

          <div class="sliderboxMobile" id="sliderbox">
            <div class="container mx-auto">
              <div class="row">
                <div class="col slider">
                  <i class="fa-solid fa-graduation-cap"></i>
                  <div class="sliderBoxTitle">
                    <h2>Best Industry Leaders</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
                <div class="col slider">
                  <i class="fa-solid fa-school"></i>
                  <div class="sliderBoxTitle">
                    <h2>Learn Courses Online</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
                <div class="col slider">
                  <i class="fa-solid fa-book"></i>
                  <div class="sliderBoxTitle">
                    <h2>Book Library & Store</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
             <!-- update news bar -->
             <div class="updateNews">
              <div class="newsTitleUp">
                <span>update news:</span>
              </div>

             <a target="_blank" href="#" class="marqueeWraperr">
              <marquee
              onmouseover="this.stop();"
              onmouseout="this.start();"
              scrollamount="12"
              behavior="scroll"
                direction="left"
                >আমার সোনার বাংলা, আমি তোমায় ভালোবাসি।
              </marquee>
             </a>

            </div>
            </div>
          </div>
        </div>
        <!-- Slides -->
        <div class="swiper-slide">
          <div class="gradient"></div>
          <img class="w-100 h-100"
            src="https://www.dell.org/wp-content/uploads/2019/05/todays-college-student-Twitter.jpg" alt="" />

          <div class="herotitlewrapper">
            <div class="container mx-auto">
              <p>The best team for</p>
              <h2>
                <span class="education_2"></span>
              </h2>
              <div class="button">
                <button>Buy now</button>
              </div>
            </div>
          </div>

          <div class="sliderboxMobile" id="sliderbox">
            <div class="container mx-auto">
              <div class="row">
                <div class="col slider">
                  <i class="fa-solid fa-graduation-cap"></i>
                  <div class="sliderBoxTitle">
                    <h2>Best Industry Leaders</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
                <div class="col slider">
                  <i class="fa-solid fa-school"></i>
                  <div class="sliderBoxTitle">
                    <h2>Learn Courses Online</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
                <div class="col slider">
                  <i class="fa-solid fa-book"></i>
                  <div class="sliderBoxTitle">
                    <h2>Book Library & Store</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Slides -->
        <div class="swiper-slide">
          <div class="gradient"></div>
          <img class="w-100 h-100"
            src="https://www.dell.org/wp-content/uploads/2019/05/todays-college-student-Twitter.jpg" alt="" />

          <div class="herotitlewrapper">
            <div class="container mx-auto">
              <p>The best team for</p>
              <h2>
                <span class="education_3"></span>
              </h2>
              <div class="button">
                <button>Buy now</button>
              </div>
            </div>
          </div>

          <div class="sliderboxMobile" id="sliderbox">
            <div class="container mx-auto">
              <div class="row">
                <div class="col slider">
                  <i class="fa-solid fa-graduation-cap"></i>
                  <div class="sliderBoxTitle">
                    <h2>Best Industry Leaders</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
                <div class="col slider">
                  <i class="fa-solid fa-school"></i>
                  <div class="sliderBoxTitle">
                    <h2>Learn Courses Online</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
                <div class="col slider">
                  <i class="fa-solid fa-book"></i>
                  <div class="sliderBoxTitle">
                    <h2>Book Library & Store</h2>
                    <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </section>

  <!-- mobile slider boxs start -->
  <div class="sliderboxMobile">
    <div class="container mx-auto p-0">
      <!-- update news bar -->
      <div class="updateNews">
        <div class="newsTitleUp">
          <span>updates:</span>
        </div>

       <a target="_blank" href="#" class="marqueeWraperr">
        <marquee
        onmouseover="this.stop();"
        onmouseout="this.start();"
        scrollamount="12"
        behavior="scroll"
          direction="left"
          >আমার সোনার বাংলা, আমি তোমায় ভালোবাসি।
        </marquee>
       </a>

      </div>
    </div>
    <div class="container mx-auto">

      <div class="row">
        <div class="col-12 slider">
          <i class="fa-solid fa-graduation-cap"></i>
          <div class="sliderBoxTitle">
            <h2>Best Industry Leaders</h2>
            <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
          </div>
        </div>
        <div class="col-12 slider">
          <i class="fa-solid fa-school"></i>
          <div class="sliderBoxTitle">
            <h2>Learn Courses Online</h2>
            <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
          </div>
        </div>
        <div class="col-12 slider">
          <i class="fa-solid fa-book"></i>
          <div class="sliderBoxTitle">
            <h2>Book Library & Store</h2>
            <a href="#">view more <i class="fa-solid fa-angle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- mobile slider boxs end -->

  <!-- hero carousel end -->

  <!-- success section start -->

  <section class="success">
    <div class="container mx-auto">
      <div class="row successRow">
        <!-- single box -->
        <div class="col" id="successBox">
          <i class="fa-solid fa-graduation-cap"></i>
          <h3>Wer are Suceess</h3>
          <p>
            Lorem ipsum dolor sit amet, ed do eiusmod tempor incididunt ut
            labore et dolore pork beef shank turducken.
          </p>
        </div>
        <!-- single box -->
        <div class="col" id="successBox">
          <i class="fa-solid fa-child"></i>
          <h3>Our Career</h3>
          <p>
            Lorem ipsum dolor sit amet, ed do eiusmod tempor incididunt ut
            labore et dolore pork beef shank turducken.
          </p>
        </div>
        <!-- single box -->
        <div class="col" id="successBox">
          <i class="fa-solid fa-graduation-cap"></i>
          <h3>Our Knowledge</h3>
          <p>
            Lorem ipsum dolor sit amet, ed do eiusmod tempor incididunt ut
            labore et dolore pork beef shank turducken.
          </p>
        </div>
      </div>
    </div>
  </section>
  <!-- success section end -->

  <!-- course section start -->

  <section class="course" id="course">
    <div class="container mx-auto">
      <!-- title -->
      <div class="courseTitle">
        <h2>
          EDUGARDEN
          <span>COURSES </span>
        </h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elitsed eiusmod
          tempor enim minim veniam quis notruexercit dolor sit amet eget.
        </p>
      </div>

      <!-- tab items -->
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <div class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
            role="tab" aria-controls="home" aria-selected="true">
            <i class="fa-solid fa-network-wired"></i>
            NETWORKING
          </div>
        </li>
        <li class="nav-item" role="presentation">
          <div class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab"
            aria-controls="profile" aria-selected="false">
            <i class="fa-brands fa-php"></i>

            php
          </div>
        </li>
        <li class="nav-item" role="presentation">
          <div class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="fa-brands fa-python"></i>
            python
          </div>
        </li>
        <li class="nav-item" role="presentation">
          <div class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#design" type="button" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="fa-solid fa-pen-nib"></i>
            design
          </div>
        </li>
        <li class="nav-item" role="presentation">
          <div class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#html" type="button" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="fa-solid fa-file-code"></i>
            html5/css3
          </div>
        </li>
        <li class="nav-item" role="presentation">
          <div class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#camera" type="button" role="tab"
            aria-controls="contact" aria-selected="false">
            <i class="fa-solid fa-camera"></i>
            photography
          </div>
        </li>
      </ul>

      <div class="tab-content" id="myTabContent">
        <!-- single tab -->
        <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row" id="courseDes">
            <div class="col courseSubBox">
              <h2>Rural Networking</h2>
              <p class="des">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna.
              </p>
              <div class="courseSub">
                <p><span></span> You can join any course my edugarden</p>
                <p><span></span> Education is a backbone a nation</p>
                <p><span></span>24-hour Supports for any courses</p>
                <p><span></span> Every exam online class recoding</p>
              </div>
              <button class="startBtn">Start learn now</button>
            </div>

            <div class="col">
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/vlDzYIIOYmM" title="YouTube video" allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
        <!-- single tab-->
        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <div class="row" id="courseDes">
            <div class="col courseSubBox">
              <h2>Learning PHP</h2>
              <p class="des">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna.
              </p>
              <div class="courseSub">
                <p><span></span> You can join any course my edugarden</p>
                <p><span></span> Education is a backbone a nation</p>
                <p><span></span>24-hour Supports for any courses</p>
                <p><span></span> Every exam online class recoding</p>
              </div>
              <button class="startBtn">Start learn now</button>
            </div>

            <div class="col">
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/YwEKIl3qQzA" title="YouTube video player" frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
        <!-- single tab-->
        <div class="tab-pane" id="contact" role="tabpanel" aria-labelledby="contact-tab">
          <div class="row" id="courseDes">
            <div class="col courseSubBox">
              <h2>Learning Python</h2>
              <p class="des">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna.
              </p>
              <div class="courseSub">
                <p><span></span> You can join any course my edugarden</p>
                <p><span></span> Education is a backbone a nation</p>
                <p><span></span>24-hour Supports for any courses</p>
                <p><span></span> Every exam online class recoding</p>
              </div>
              <button class="startBtn">Start learn now</button>
            </div>

            <div class="col">
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/vlDzYIIOYmM" title="YouTube video" allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
        <!-- single tab-->
        <div class="tab-pane" id="design" role="tabpanel" aria-labelledby="contact-tab">
          <div class="row" id="courseDes">
            <div class="col courseSubBox">
              <h2>Creative Design</h2>
              <p class="des">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna.
              </p>
              <div class="courseSub">
                <p><span></span> You can join any course my edugarden</p>
                <p><span></span> Education is a backbone a nation</p>
                <p><span></span>24-hour Supports for any courses</p>
                <p><span></span> Every exam online class recoding</p>
              </div>
              <button class="startBtn">Start learn now</button>
            </div>

            <div class="col">
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/vlDzYIIOYmM" title="YouTube video" allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
        <!-- single tab-->
        <div class="tab-pane" id="html" role="tabpanel" aria-labelledby="contact-tab">
          <div class="row" id="courseDes">
            <div class="col courseSubBox">
              <h2>HTML5/CSS3 Essentials</h2>
              <p class="des">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna.
              </p>
              <div class="courseSub">
                <p><span></span> You can join any course my edugarden</p>
                <p><span></span> Education is a backbone a nation</p>
                <p><span></span>24-hour Supports for any courses</p>
                <p><span></span> Every exam online class recoding</p>
              </div>
              <button class="startBtn">Start learn now</button>
            </div>

            <div class="col">
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/vlDzYIIOYmM" title="YouTube video" allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
        <!-- single tab-->
        <div class="tab-pane" id="camera" role="tabpanel" aria-labelledby="contact-tab">
          <div class="row" id="courseDes">
            <div class="col courseSubBox">
              <h2>Art of Photography</h2>
              <p class="des">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                do eiusmod tempor incididunt ut labore et dolore magna.
              </p>
              <div class="courseSub">
                <p><span></span> You can join any course my edugarden</p>
                <p><span></span> Education is a backbone a nation</p>
                <p><span></span>24-hour Supports for any courses</p>
                <p><span></span> Every exam online class recoding</p>
              </div>
              <button class="startBtn">Start learn now</button>
            </div>

            <div class="col">
              <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/vlDzYIIOYmM" title="YouTube video" allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- course section end -->

  <!-- service section start -->

  <section class="service">
    <div class="container mx-auto">
      <!-- title -->
      <div class="serviceWraper">
        <img class="male" src="./images/male.png" alt="" />
        <div class="courseTitle">
          <h2>
            OUR
            <span>LATEST </span>
            SERVICE
          </h2>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elitsed eiusmod
            tempor enim minim veniam quis notruexercit dolor sit amet eget.
          </p>
        </div>
        <img class="female" src="https://pngimg.com/d/teacher_PNG80.png" alt="" />
      </div>

      <!-- service boxs -->
      <div class="row mt-md-0 mt-4">
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <div class="serviceBox">
            <img class="serviceboxiconImg" src="./images/service/1.png" alt="" />
            <div class="serviceTitleWaper">
              <h3>Trending & Certified</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elitsed
                eiusmod tempor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <div class="serviceBox">
            <img class="serviceboxiconImg" src="./images/service/2.png" alt="" />
            <div class="serviceTitleWaper">
              <h3>Trending & Certified</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elitsed
                eiusmod tempor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <div class="serviceBox">
            <img class="serviceboxiconImg" src="./images/service/3.png" alt="" />
            <div class="serviceTitleWaper">
              <h3>Trending & Certified</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elitsed
                eiusmod tempor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <div class="serviceBox">
            <img class="serviceboxiconImg" src="./images/service/4.png" alt="" />
            <div class="serviceTitleWaper">
              <h3>Trending & Certified</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elitsed
                eiusmod tempor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <div class="serviceBox">
            <img class="serviceboxiconImg" src="./images/service/5.png" alt="" />
            <div class="serviceTitleWaper">
              <h3>Trending & Certified</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elitsed
                eiusmod tempor.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <div class="serviceBox">
            <img class="serviceboxiconImg" src="./images/service/6.png" alt="" />
            <div class="serviceTitleWaper">
              <h3>Trending & Certified</h3>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elitsed
                eiusmod tempor.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- service section end -->

  <!-- teachers section start -->
  <section class="teachers">
    <div class="container mx-auto">
      <!-- title -->
      <div class="courseTitle">
        <h2>
          EDUGARDEN
          <span>TEACHERS </span>
        </h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elitsed eiusmod
          tempor enim minim veniam quis notruexercit dolor sit amet eget.
        </p>
      </div>
      <!-- teacher boxes -->
      <div class="row mt-4">
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-md-4 mb-3">
          <div class="my-team">
            <div class="pic">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/teachers/teacher-image-2.jpg"
                class="img-fluid" alt="teacher-member" />
            </div>
            <div class="team-content">
              <h3 class="title">Abdur Kabir</h3>
              <span class="post">Web Designer</span>
            </div>
            <ul class="social">
              <li>
                <a href="https://www.youtube.com/watch?v=YwEKIl3qQzA" target="_blank" class="icofont-facebook">
                  <i class="fa-brands fa-facebook-f"></i>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-twitter"><i class="fa-brands fa-twitter"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-google-plus"><i class="fa-brands fa-google-plus-g"></i></a>
              </li>
              <li>
                <a href="#" target="_blank" class="icofont-linkedin"><i class="fa-brands fa-linkedin"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- teachers section end -->


  <!-- gallery section start -->
  <section class="gallery">
    <div class="container mx-auto">
      <!-- title -->
      <div class="courseTitle">
        <h2>
          EDUGARDEN
          <span>TEACHERS </span>
        </h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elitsed eiusmod
          tempor enim minim veniam quis notruexercit dolor sit amet eget.
        </p>
      </div>
      <!-- gallery slider  -->


      <!-- slide single -->
      <!-- Swiper -->


      <div class="swiper mySwiper mt-4" id="gallerySwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="portfolio">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/portfolio/4.jpg" alt="Portfolio">
              <div class="portfolio-content">
                <ul class="icon">
                  <li><a href="#">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                </ul>
                <h3 class="title">China</h3>
                <span class="post">Comming Soon June 30,2022</span>
              </div>
            </div>

          </div>
          <div class="swiper-slide">
            <div class="portfolio">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/portfolio/4.jpg" alt="Portfolio">
              <div class="portfolio-content">
                <ul class="icon">
                  <li><a href="#">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                </ul>
                <h3 class="title">China</h3>
                <span class="post">Comming Soon June 30,2022</span>
              </div>
            </div>

          </div>
          <div class="swiper-slide">
            <div class="portfolio">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/portfolio/4.jpg" alt="Portfolio">
              <div class="portfolio-content">
                <ul class="icon">
                  <li><a href="#">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                </ul>
                <h3 class="title">China</h3>
                <span class="post">Comming Soon June 30,2022</span>
              </div>
            </div>

          </div>
          <div class="swiper-slide">
            <div class="portfolio">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/portfolio/4.jpg" alt="Portfolio">
              <div class="portfolio-content">
                <ul class="icon">
                  <li><a href="#">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                </ul>
                <h3 class="title">China</h3>
                <span class="post">Comming Soon June 30,2022</span>
              </div>
            </div>

          </div>
          <div class="swiper-slide">
            <div class="portfolio">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/portfolio/4.jpg" alt="Portfolio">
              <div class="portfolio-content">
                <ul class="icon">
                  <li><a href="#">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                </ul>
                <h3 class="title">China</h3>
                <span class="post">Comming Soon June 30,2022</span>
              </div>
            </div>

          </div>
          <div class="swiper-slide">
            <div class="portfolio">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/portfolio/4.jpg" alt="Portfolio">
              <div class="portfolio-content">
                <ul class="icon">
                  <li><a href="#">
                      <i class="fa fa-search"></i>
                    </a>
                  </li>
                </ul>
                <h3 class="title">China</h3>
                <span class="post">Comming Soon June 30,2022</span>
              </div>
            </div>

          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>





    </div>
    </div>
  </section>
  <!-- gallery section end -->


  <!-- recent news section start -->

  <section class="recentNews">
    <div class="container mx-auto">
      <!-- title -->
      <div class="courseTitle">
        <h2>
          OUR
          <span>RECENT </span>
          NEWS
        </h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elitsed eiusmod
          tempor enim minim veniam quis notruexercit dolor sit amet eget.
        </p>
      </div>
      <!-- cards -->
      <div class="swiper mySwiper mt-4" id="recentNewsSwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="recent-single-blog">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/2.jpg" alt="blog">

              <div class="recent-blog-content">
                <ul>
                  <li><a href="#"><i class="fa-solid fa-user-tie"></i> 15 July 2023</a></li>
                  <li><a href="#"><i class="fa-regular fa-clock"></i> 18 Nov 2023</a></li>
                </ul>
                <h3><a href="#">Awesome Teacher group work in this class.</a></h3>
                <p>Lorem ipsum dolor sit amet consecte adipisic elitsed eiusmod tempor enim minim veniamquis not
                  ruexercit dolor work in this class.</p>
                <div class="recent-hero-btn">
                  <a class="recent-main-btn" href="#">Read More</a>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="recent-single-blog">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/2.jpg" alt="blog">

              <div class="recent-blog-content">
                <ul>
                  <li><a href="#"><i class="fa-solid fa-user-tie"></i> 15 July 2023</a></li>
                  <li><a href="#"><i class="fa-regular fa-clock"></i> 18 Nov 2023</a></li>
                </ul>
                <h3><a href="#">Awesome Teacher group work in this class.</a></h3>
                <p>Lorem ipsum dolor sit amet consecte adipisic elitsed eiusmod tempor enim minim veniamquis not
                  ruexercit dolor work in this class.</p>
                <div class="recent-hero-btn">
                  <a class="recent-main-btn" href="#">Read More</a>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="recent-single-blog">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/2.jpg" alt="blog">

              <div class="recent-blog-content">
                <ul>
                  <li><a href="#"><i class="fa-solid fa-user-tie"></i> 15 July 2023</a></li>
                  <li><a href="#"><i class="fa-regular fa-clock"></i> 18 Nov 2023</a></li>
                </ul>
                <h3><a href="#">Awesome Teacher group work in this class.</a></h3>
                <p>Lorem ipsum dolor sit amet consecte adipisic elitsed eiusmod tempor enim minim veniamquis not
                  ruexercit dolor work in this class.</p>
                <div class="recent-hero-btn">
                  <a class="recent-main-btn" href="#">Read More</a>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="recent-single-blog">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/2.jpg" alt="blog">

              <div class="recent-blog-content">
                <ul>
                  <li><a href="#"><i class="fa-solid fa-user-tie"></i> 15 July 2023</a></li>
                  <li><a href="#"><i class="fa-regular fa-clock"></i> 18 Nov 2023</a></li>
                </ul>
                <h3><a href="#">Awesome Teacher group work in this class.</a></h3>
                <p>Lorem ipsum dolor sit amet consecte adipisic elitsed eiusmod tempor enim minim veniamquis not
                  ruexercit dolor work in this class.</p>
                <div class="recent-hero-btn">
                  <a class="recent-main-btn" href="#">Read More</a>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="recent-single-blog">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/2.jpg" alt="blog">

              <div class="recent-blog-content">
                <ul>
                  <li><a href="#"><i class="fa-solid fa-user-tie"></i> 15 July 2023</a></li>
                  <li><a href="#"><i class="fa-regular fa-clock"></i> 18 Nov 2023</a></li>
                </ul>
                <h3><a href="#">Awesome Teacher group work in this class.</a></h3>
                <p>Lorem ipsum dolor sit amet consecte adipisic elitsed eiusmod tempor enim minim veniamquis not
                  ruexercit dolor work in this class.</p>
                <div class="recent-hero-btn">
                  <a class="recent-main-btn" href="#">Read More</a>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="recent-single-blog">
              <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/2.jpg" alt="blog">

              <div class="recent-blog-content">
                <ul>
                  <li><a href="#"><i class="fa-solid fa-user-tie"></i> 15 July 2023</a></li>
                  <li><a href="#"><i class="fa-regular fa-clock"></i> 18 Nov 2023</a></li>
                </ul>
                <h3><a href="#">Awesome Teacher group work in this class.</a></h3>
                <p>Lorem ipsum dolor sit amet consecte adipisic elitsed eiusmod tempor enim minim veniamquis not
                  ruexercit dolor work in this class.</p>
                <div class="recent-hero-btn">
                  <a class="recent-main-btn" href="#">Read More</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>
  <!-- recent news section end -->


  <!-- contact section start -->
  <section class="contact">
    <div class="container mx-auto">
      <!-- title -->
      <div class="courseTitle">
        <h2>
          OUR
          <span>CONTACT </span>
          US
        </h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elitsed eiusmod
          tempor enim minim veniam quis notruexercit dolor sit amet eget.
        </p>
      </div>
      <!-- address boxes -->
      <div class="row mt-4">
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <a href="#" class="serviceBox addressBox">
            <i class="fa-sharp fa-solid fa-location-dot"></i>
            <div class="serviceTitleWaper">
              <h3>OUR HOME ADDRESS</h3>
              <p>
                Canada Place,
                <br>
                Sidney, BC, Canada
              </p>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <a href="mailto:info@business.com" class="serviceBox addressBox">
            <i class="fa-sharp fa-solid fa-envelope"></i>
            <div class="serviceTitleWaper">
              <h3>OUR EMAIL ADDRESS</h3>
              <p>
                info@business.com
                <br>
                support@business.com
              </p>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-4 col-12 mb-md-4 mb-3">
          <a href="tel:+1 (424) 652-5294" class="serviceBox addressBox">
            <i class="fa-solid fa-phone"></i>
            <div class="serviceTitleWaper">
              <h3>OUR PHONE</h3>
              <p>
                +1 (424) 652-5294
                <br>
                +1 (424) 652-5294

              </p>
            </div>
          </a>
        </div>
      </div>

      <!-- from and map -->
      <div class=" row mt-4 contactWrapper">
        <div class="col-md-6 col-12 mb-3 mb-md-0">


          <form class="from">
            <div class="name">
              <input type="text" placeholder="Your Name" name="" id="">
            </div>
            <div class="email">
              <input type="email" placeholder="Your Email" name="" id="">
            </div>
            <div class="phone">
              <input type="number" placeholder="Your Phone" name="" id="">
            </div>
            <div class="textAria">
              <textarea placeholder="Enter Your Message" name="" id="" cols="20" rows="5"></textarea>
            </div>
            <div class="sendBtn">
              <button>Send Message</button>
            </div>

          </form>
        </div>
        <div class="col-md-6 col-12">
          <div class="map">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3634.5591577392875!2d88.83437837436364!3d24.361844465030146!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fc08ee31228683%3A0xec7d9a92ddfc1103!2sPuthia%20Rajbari!5e0!3m2!1sen!2sbd!4v1683051673809!5m2!1sen!2sbd"
              width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- contact section end -->


  <!-- subscribe section start -->
  <section class="subscribe">
    <div class="container mx-auto">
      <div class="atf-single-mailchamp">
        <!-- Newsletter title -->
        <div class="atf-mailchame-content">
          <h2>Our Newsletter to Subscribe</h2>
          <p class="color-white">Dantes remained confused and silent by this explanation of the, consectetur adipisicing
            elit,</p>
        </div>

        <div class="subscribe-form">
          <div class="subscribe-form-inner">
            <!-- Newsletter form -->
            <form class="form-inline dv-form" id="mc-form" novalidate="true">
              <div class="form-group">
                <input type="email" name="EMAIL" id="email" placeholder="Your Email" required="required">
                <div class="atf-hero-btn">
                  <button class="atf-main-btn btn" name="Subscribe" id="subscribe-btn" type="submit">Submit</button>
                </div>
              </div>
              <br>
              <label class="atf-subscription-label" for="email"></label>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- subscribe section end -->


  <!-- footer section start -->
  <footer class="atf-footer-area">
    <div class="container mx-auto">
      <div class="row atf-section-padding">
        <div class="col-lg-4 atf-footer-link wow fadeIn mb-4 mb-lg-0" data-wow-duration="1s" data-wow-delay="0.2s"
          data-wow-offset="0"
          style="visibility: visible; animation-duration: 1s; animation-delay: 0.2s; animation-name: fadeIn;">
          <div class="atf-footer-box">
            <h5><a href="#">Edugarden Company</a></h5>
            <p class="pe-lg-3"> Lorem ipsum dolor sit amet consectetur adipiscing elit sed eiusmod dolor.</p>
            <h4 class="mt-4 text-white">Follow Us</h4>
            <div class="atf-footer-social-icon mt-4">
              <a href="#" class="icon"> <i class="fab fa-facebook"></i> </a>
              <a href="#" class="icon"> <i class="fab fa-dribbble"></i> </a>
              <a href="#" class="icon"> <i class="fab fa-behance"></i> </a>
              <a href="#" class="icon"> <i class="fab fa-linkedin"></i> </a>
              <a href="#" class="icon"> <i class="fab fa-twitter"></i> </a>
            </div>
          </div>
        </div><!--- END COL -->

        <div class="col-lg-2 col-6 atf-footer-link wow fadeIn" data-wow-duration="1s" data-wow-delay="0.3s"
          data-wow-offset="0"
          style="visibility: visible; animation-duration: 1s; animation-delay: 0.3s; animation-name: fadeIn;">
          <h5>Quick Links</h5>
          <ul class="atf-list-menu">
            <li> <a href="#"><i class="fas fa-caret-right me-2"></i>Get Stared</a></li>
            <li> <a href="#"><i class="fas fa-caret-right me-2"></i>Our Team</a></li>
            <li> <a href="#"><i class="fas fa-caret-right me-2"></i>About Us</a></li>
            <li> <a href="#"><i class="fas fa-caret-right me-2"></i>Need Helps</a></li>
            <li> <a href="#"><i class="fas fa-caret-right me-2"></i>Contact Us</a></li>
            <li> <a href="#"><i class="fas fa-caret-right me-2"></i>Our Teacher</a></li>
          </ul>
        </div><!--- END COL -->

        <div class="col-lg-2 col-6 atf-footer-link wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s"
          data-wow-offset="0"
          style="visibility: visible; animation-duration: 1s; animation-delay: 0.4s; animation-name: fadeIn;">
          <h5>Popular Tags</h5>
          <ul class="atf-list-menu">
            <li><a href="#"><i class="fas fa-caret-right me-2"></i>English</a></li>
            <li><a href="#"><i class="fas fa-caret-right me-2"></i>Our Bangla</a></li>
            <li><a href="#"><i class="fas fa-caret-right me-2"></i>Our Service</a></li>
            <li><a href="#"><i class="fas fa-caret-right me-2"></i>Site Map</a></li>
            <li><a href="#"><i class="fas fa-caret-right me-2"></i>Our News</a></li>
            <li><a href="#"><i class="fas fa-caret-right me-2"></i>Our Contact</a></li>
          </ul>
        </div><!--- END COL -->

        <div class="col-lg-4 atf-footer-link wow fadeIn mt-2 mt-lg-0" data-wow-duration="1s" data-wow-delay="0.5s"
          data-wow-offset="0"
          style="visibility: visible; animation-duration: 1s; animation-delay: 0.5s; animation-name: fadeIn;">
          <h5>Our Gallery</h5>
          <a href="#">
            <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/footer/1.jpg" alt="Instragram">
          </a>
          <a href="#">
            <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/footer/2.jpg" alt="Instragram">
          </a>
          <a href="#">
            <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/footer/3.jpg" alt="Instragram">
          </a>
          <a href="#">
            <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/footer/4.jpg" alt="Instragram">
          </a>
          <a href="#">
            <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/footer/5.jpg" alt="Instragram">
          </a>
          <a href="#">
            <img src="https://themesfamily.com/html-template/edugarden/assets/img/blog/footer/6.jpg" alt="Instragram">
          </a>
        </div><!--- END COL -->
      </div><!--- END ROW -->
    </div><!--- END CONTAINER -->


    <div class="row">
      <div class="col-lg-12">
        <div class="atf-footer-boottom text-center wow fadeIn" data-wow-duration="1s" data-wow-delay="0.3s"
          data-wow-offset="0"
          style="visibility: visible; animation-duration: 1s; animation-delay: 0.3s; animation-name: fadeIn;">
          <p>©Copyright - All Right Reserved.Designed <i class="fa fa-heart ms-1 me-1"></i> by <a target="_blank"
              href="https://expertfaisal.com/">EDTECO</a> </p>
        </div><!--- END COL -->
      </div><!--- END ROW -->
    </div>
    <!-- FOOTER SECTION END-->
  </footer>
  <!-- footer section end -->






  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

  <!-- bootstart 5 js cdn -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

  <!-- typed.js cdn -->
  <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>

  <!-- swiper slider js  -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <!-- costom js  -->
  <script src="{{asset('assets/theme-2/js/app.js')}}"></script>
  <script src="{{asset('assets/theme-2/js/swiper.js')}}"></script>

  <script>

  </script>
</body>

</html>
