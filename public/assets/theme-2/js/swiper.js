// hero slider
var swiper = new Swiper("#heroSwiper", {
  direction: "horizontal",
  loop: true,
  // autoplay: {
  //   delay: 5000,
  // },

  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },

  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

// gallery slider
var swiper = new Swiper("#gallerySwiper", {
  loop: true,
  autoplay: {
    delay: 5000,
  },
  slidesPerView: 1,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    480: {
      slidesPerView: 1,
      spaceBetween: 20,
    },
    768: {
      slidesPerView: 2,
      spaceBetween: 20,
    },
    992: {
      slidesPerView: 3,
      spaceBetween: 20,
    },
  },
});

// recent news slider
var swiper = new Swiper("#recentNewsSwiper", {
  loop: true,
  autoplay: {
    delay: 5000,
  },
  slidesPerView: 1,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    480: {
      slidesPerView: 1,
      spaceBetween: 20,
    },
    768: {
      slidesPerView: 2,
      spaceBetween: 20,
    },
    992: {
      slidesPerView: 3,
      spaceBetween: 20,
    },
  },
});
