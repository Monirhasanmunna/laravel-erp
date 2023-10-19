// navbar js
window.addEventListener('scroll',function(){
  var nav=this.document.querySelector('.navbar');
  nav.classList.toggle('navbar-main',window.scrollY > 0)
})

// scroll to top js

window.addEventListener('scroll',function(){
  var topbutton=this.document.querySelector('.back_to_top');
  topbutton.classList.toggle('active',window.scrollY > 0)
})

function scrollToTop(){
  window.scrollTo({
    top: 0,
    behavior:'smooth'
  })
}
// typed.js slider writer
  var typed = new Typed('.education', {
    strings: ['education', 'developer','designer'],
    typeSpeed: 50,
    loop:true,
    backSpeed:50,
    showCursor:true
  });
  // 
  var typed = new Typed('.education_2', {
    strings: ['education', 'developer','designer'],
    typeSpeed: 50,
    loop:true,
    backSpeed:50,
    showCursor:true
  });
  // 
  var typed = new Typed('.education_3', {
    strings: ['education', 'developer','designer'],
    typeSpeed: 50,
    loop:true,
    backSpeed:50,
    showCursor:true
  });
  
