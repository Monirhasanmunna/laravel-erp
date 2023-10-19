<!DOCTYPE html>
<html lang="en">

@include('admin.partial.head')
<link rel="stylesheet" href="{{asset('assets/vendors/chosen/chosen.css')}}">
@stack('css')
<body>

  <div class="container-scroller">
    <!-- partial:partials/_sidebar.html -->
   @include('admin.partial.sideber')
    <!-- partial: sideber end -->


    <div class="container-fluid page-body-wrapper">

      <!-- partial:partials/_navbar.html -->
     @include('admin.partial.header_menu')
      <!-- partial _navber end -->


  <!-- main-panel start -->
     @yield('content')
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>



  <!-- plugins:js start-->
 @include('admin.partial.footer')
 @include('admin.partial.expired-modal')
  <!-- End custom js for this page -->
<script src="{{asset('assets/vendors/chosen/chosen.jquery.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="//code.jquery.com/jquery-1.11.3.min.js"></script> --}}
 @stack('js')
  <script>
      $(document).ready(function (){
          $.get("{{route('expired-check')}}",function (data){
              if(data.expired){
                  if(data.paymentMethod != 'online'){
                      $('#pay-btn-wraper').addClass('d-none');
                  }
                  $('#msg_head').html(data.msgHead);
                  $('#msg_body').html(data.msgBody);
                  $('#adminExpiredModal').modal({backdrop: 'static', keyboard: false}).modal('show');
              }
          });

          $(document).on('click','#delete',function (e){
              e.preventDefault();
              let link = $(this).attr("href");
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
                      window.location.href = link;

                  }
              })
          });


      });
  </script>
</body>

</html>
