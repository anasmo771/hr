<!DOCTYPE html>

@if (session()->get('locale') == 'en')
    <html lang="en">
@else
    <html lang="ar" dir="rtl">
@endif
<!-- [Head] start -->
<head>

    @yield('title')
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="SHOP AND GO PORTAL">
    <meta name="keywords" content="SHOP AND GO PORTAL">
    <meta name="author" content="SHOP AND GO PORTAL">

    <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

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

    @include('admin.layout.style')

        {{-- ملفك المخصص (يحمل :root) لازم يكون بعد bootstrap والثيم --}}
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?v={{ filemtime(public_path('assets/css/custom.css')) }}">

        {{-- ادعم النوعين: صفحات قديمة تستخدم css، وصفحات جديدة تستخدم style --}}
        @yield('style')
        @yield('css')

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


    <!-- [ Sidebar Menu ] start -->
                <nav class="pc-sidebar">
                    <div class="navbar-wrapper">
            <div class="m-header justify-content-center" style="text-align: center; display: flex; justify-content: center; align-items: center; height: 70px; margin-top: 20px;">
                <a href="{{ route('home') }}" class="b-brand text-info" style="text-decoration: none;">
                    <!-- ========   Change your logo from here   ============ -->
                    <img src="{{ asset('assets/New/images/logo.png') }}" width="80" alt="Logo" style="height: auto; object-fit: contain;" />
                </a>
            </div>

            <div class="navbar-content">
                {{-- @include('admin.layout.navbar_profile') --}}

                @include('admin.layout.menu')

            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('admin.layout.header')

    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    @yield('content')

    <!-- [ Main Content ] end -->
    {{-- @include('admin.layout.footer') --}}


    @include('admin.layout.script')
    @yield('script')

    <script src="{{asset('assets\libs\select2\js\select2.full.min.js')}}" charset="utf-8"></script>
    <script src="{{asset('assets/libs/select2/js/select2.min.js')}}"></script>
    <!-- dropzone plugin -->

    <!-- init js -->
    <script src="{{asset('assets/js/pages/ecommerce-select2.init.js')}}"></script>


    <script>
                    $( document ).ready(function() {
        $('.js-example-basic-single').select2({
          dir: "rtl",
          maximumSelectionLength: 1 ,

        });
        $('.js-example-basic-multiple').select2();

        document.getElementById("rtl-mode-switch").trigger('click');
});
    </script>
    <script>
    $(document).ready(function () {
        // single
        $('.js-example-basic-single').select2({
        dir: "rtl",
        width: '100%',
        placeholder: 'اختر',
        allowClear: true
        });

        // multiple (إن احتجت حدًا لعدد الاختيارات فاجعله هنا فقط)
        $('.js-example-basic-multiple').select2({
        dir: "rtl",
        width: '100%'
        // maximumSelectionLength: 1  // استخدمها هنا فقط لو فعلاً محتاج تمنع أكثر من اختيار
        });
    });
    </script>

</body>
<!-- [Body] end -->

</html>
