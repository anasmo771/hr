
<!DOCTYPE html>

<html lang="ar" dir="ltr">
<!-- [Head] start -->

<head>
  <title>تسجيل الدخول</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Saidc Portal.">
  <meta name="keywords" content="Saidc Portal dashboard">
  <meta name="author" content="Saidc Portal">

  <style type="text/css">
    @import url(https://fonts.googleapis.com/css2?family=Cairo:wght@500&display=swap);
    * {
      font-family: 'Cairo', sans-serif;
    }

    #loading-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(255, 255, 255, 0.883);
          z-index: 9999; /* ensure the overlay appears on top of other elements */
          display: flex;
          justify-content: center;
          align-items: center;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

  </style>

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/New/images/logggo.jpeg')}}" type="image/x-icon">
 <!-- [Font] Family -->
<link rel="stylesheet" href="{{ asset('assets/New/fonts/inter/inter.css')}}" id="main-font-link" />

<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="{{ asset('assets/New/fonts/tabler-icons.min.css')}}" />
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="{{ asset('assets/New/fonts/feather.css')}}" />
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="{{ asset('assets/New/fonts/fontawesome.css')}}" />
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="{{ asset('assets/New/fonts/material.css')}}" />
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{ asset('assets/New/css/style.css')}}" id="main-style-link" />
<link rel="stylesheet" href="{{ asset('assets/New/css/style-preset.css')}}" />

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>

    <div id="loading-overlay">
        <img src="{{ asset('assets/New/images/favicon.png') }}" width="90" alt="" class="img-fluid spinner">
    </div>

  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v2">

        <!-- <div class="auth-sidecontent">
            <img src="{{asset('assets/New/images/poster.png')}}" alt="images"
              class="img-fluid img-auth-side">
          </div> -->

      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <div class="text-center">
              <a href="#"><img src="{{asset('assets/New/images/loggo.png')}}" width="250" alt="img"></a>

            </div>

            <div class="saprator my-3">
                <span>مـرحـبـآ</span>
              </div>

            <h4 class="text-center f-w-500 mb-3">تسجيل الدخول إلى نظام إدارة شؤون الموظفين</h4>

            @include('layouts.validation-messages')


            <form method="POST" action="{{ route('login') }}">
                @csrf

            <div class="form-group mb-3">
              <input type="text" name="name" class="form-control" id="floatingInput" required
              oninvalid="this.setCustomValidity('الرجاء ادخال اسم المستخدم او البريد الالكتروني')" oninput="this.setCustomValidity('')" placeholder="اسم المستخدم او البريد الالكتروني">
            </div>
            <div class="form-group mb-3">
              <input type="password" name="password" class="form-control" id="floatingInput1" required oninvalid="this.setCustomValidity('الرجاء ادخال كلمة المرور')" oninput="this.setCustomValidity('')" placeholder="كلمة المرور">
            </div>
            <div class="d-flex mt-1 justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                  <label class="form-check-label text-muted" for="customCheckc1">تذكرني</label>
                </div>
            </div>
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary" style="background-color: #282E4D; border-color:#282E4D;">تسجيل الدخول</button>
            </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->

  <!-- [Page Specific JS] start -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- <script src='{{ asset('assets/New/js/plugins/apexcharts.min.js') }}'></script> --}}
 {{-- <script src="{{ asset('assets/New/js/pages/dashboard-default.js')}}"></script> --}}

 <script src="{{ asset('assets/New/js/plugins/popper.min.js')}}"></script>
 <script src="{{ asset('assets/New/js/plugins/simplebar.min.js')}}"></script>
 <script src="{{ asset('assets/New/js/plugins/bootstrap.min.js')}}"></script>
 <script src="{{ asset('assets/New/js/fonts/custom-font.js')}}"></script>
 <script src="{{ asset('assets/New/js/config.js')}}"></script>
 <script src="{{ asset('assets/New/js/pcoded.js')}}"></script>
 <script src="{{ asset('assets/New/js/plugins/feather.min.js')}}"></script>

 <script src="{{ asset('assets/New/js/plugins/choices.min.js')}}"></script>


<script>
      window.addEventListener('load', function() {
  var loadingOverlay = document.getElementById('loading-overlay');
  loadingOverlay.style.display = 'none';
});
</script>
</body>
<!-- [Body] end -->

</html>
