

    <!doctype html>
<html lang="en" dir="rtl" >

    <head>

        <meta charset="utf-8" />
        @yield('title')

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="منطومة إدارة شؤون الموظفين" name="description" />
        <meta content="Themesbrand" name="Malik Al Nabouli" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">
        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap-rtl.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app-rtl.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- select2 css -->
        <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- dropzone css -->
        <link href="{{asset('assets/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />

        <style>

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
  background-color: rgba(255, 255, 255, 0.774);
  z-index: 9999; /* ensure the overlay appears on top of other elements */
  display: flex;
  justify-content: center;
  align-items: center;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid cornflowerblue; /* color of the spinner */
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

td, th{
    font-size: 18px !important;
}

label{
    font-size: 20px !important;
}


        </style>

@yield('css')


    </head>

    <body data-sidebar="dark">

        <div id="loading-overlay">
            <div class="spinner"></div>
          </div>

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">




            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box"  style="background-color: #343a40;">
                            <a href="{{route('home')}}" class="logo logo-dark">

                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="" height="100">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="" height="100">
                                </span>
                            </a>

                                <a href="{{route('home')}}" class="logo logo-light">

                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="" height="50">
                                </span>
                                <span class="logo-lg mt-5">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="" height="80">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <!-- App Search-->

                    </div>

                    <div class="d-flex">

                        @can('employee-list')
                        <div class="dropdown ms-2">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">


                                <form action="{{route('searchEmployee')}}" method="get" class="p-3">
                                    @csrf
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="name" placeholder="البحث عن موظف" aria-label="Recipient's username" required oninvalid="this.setCustomValidity('الرجاء ادخال اسم او رقم الوطني')" oninput="this.setCustomValidity('')">
                                            <div class="input-group-append">
                                                <button class="btn btn-dark" type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endcan



                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                                <i class="bx bx-fullscreen"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block">
                            @if(Auth::user()->notifications->count() > 0)
                                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @else
                                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown">
                            @endif
                                <i class="bx bx-bell bx-tada"></i>
                                @if(Auth::user()->notifications->where('read', 0)->count() > 0)
                                <span class="badge bg-danger rounded-pill">{{Auth::user()->notifications->where('read', 0)->count()}}</span>
                                @else
                                <span class="badge bg-danger rounded-pill"></span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0" key="t-notifications"> الإشــعــارات </h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{route('notifications.index')}}" class="small" key="t-view-all"> عــرض الـكـل</a>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    @foreach (Auth::user()->notifications->take(5)->sortByDesc('created_at') as $notif)
                                    <a href="{{route('notifications.index')}}" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <img src="{{asset('assets/images/logo.png')}}"
                                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" key="t-your-order">{{$notif->title}}</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1" key="t-grammer">{{$notif->desc}}</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">{{$notif->created_at->diffForHumans()}}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach

                                </div>
                                <div class="p-2 border-top d-grid">
                                    <a class="btn btn-sm btn-link font-size-14 text-center" href="{{route('notifications.index')}}">
                                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">عرض المزيد..</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->image == "user.png")
                                <img class="rounded-circle header-profile-user" src="{{asset('assets/images/users/user.png')}}"
                                    alt="Header Avatar">
                            @else
                                <img class="rounded-circle header-profile-user" src="{{asset(Storage::url(Auth::user()->image))}}"
                                    alt="Header Avatar">
                            @endif

                                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{Auth::user()->name}}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <!-- <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">شخصي</span></a> -->
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">تسجيل خروج</span></a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>



                    </div>
                </div>
            </header>



            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu" style="background-color: #343a40;">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">

                            <li class="menu-title" key="t-menu" >عام</li>
                            <li>
                                <a href="{{route('home')}}" class="waves-effect">
                                    <i class="bx bx-home-circle"></i>
                                    <span class="badge rounded-pill bg-dark float-end"></span>
                                    <span key="t-file-manager " style="font-size: 130%">الرئـــيــســـيـــة</span>
                                </a>
                            </li>

                            <li class="menu-title" key="t-apps">القائمة</li>

                            @can('employee-list')
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="fa fa-users"></i>
                                        <span key="t-dashboards" style="font-size: 130%">إدارة الموظفين</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                    @can('employee-list')
                                      <li><a href="{{route('employees.index')}}" key="t-full-calendar">كل الموظفين</a></li>
                                    @endcan
                                    @can('employee-create')
                                        <li><a href="{{route('employees.create')}}" key="t-full-calendar">إضافة موظف جديد</a></li>
                                    @endcan
                                    @can('archive-list')
                                      <li><a href="{{route('archives.index',['id'=>0])}}" key="t-inbox">الارشيف الوظـيـفـي</a></li>
                                    @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('vacation-list')
                            <li>
                                <a href="{{route('vacations.index')}}" class="waves-effect">
                                    <i class="bx bx-task"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة الإجــازات</span>
                                </a>
                            </li>
                            @endcan


                            @can('absent-list')
                            <li>
                                <a href="{{route('absents.index')}}" class="waves-effect">
                                    <i class="bx bx-user"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة الـــغـــياب</span>
                                </a>
                            </li>
                            @endcan

                            @can('feedback-list')
                                <li>
                                    <a href="{{route('feedback.index')}}" class="waves-effect">
                                        <i class="fa fa-star-half"></i>
                                        <span key="t-ecommerce" style="font-size: 130%">إدارة تـقـاريـر الـكـفـايـة</span>
                                    </a>
                                </li>
                            @endcan

                            @can('course-list')
                            <li>
                                <a href="{{route('courses.index')}}" class="waves-effect">
                                    <i class="fa fa-graduation-cap"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة الــدورات</span>
                                </a>
                            </li>
                            @endcan

                            @can('punishment-list')
                            <li>
                                <a href="{{route('punishments.index')}}" class="waves-effect">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة العـقـوبـات</span>
                                </a>
                            </li>
                            @endcan

                            @can('task-list')
                            <li>
                                <a href="{{route('tasks.index')}}" class="waves-effect">
                                    <i class="fa fa-tasks"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة الـتـكـلـيـفـاتـ </span>
                                </a>
                            </li>
                            @endcan

                            @can('promotion-list')
                            <li>
                                <a href="{{route('promotion.index')}}" class="waves-effect">
                                    <i class="fa fa-angle-double-up"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة الـتـرقـيـات </span>
                                </a>
                            </li>
                            @endcan


                            @can('bonus-list')
                            <li>
                                <a href="{{route('bouns.index')}}" class="waves-effect">
                                    <i class="fa fa-plus-circle"></i>
                                    <span key="t-ecommerce" style="font-size: 130%">إدارة الـعـلاوات </span>
                                </a>
                            </li>
                            @endcan


                            @can('notification-list')
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-envelope"></i>
                                    <span key="t-notifications" style="font-size: 130%">إدارة الإشــعــارات</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{route('notifications.index')}}" key="t-inbox">كــل الإشــعــارات</a></li>
                                    @can('notification-create')
                                    <li><a href="{{route('notifications.create')}}" key="t-read-email">إرســال إشــعــار</a></li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan


                            @can('system-list')
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-cog"></i>
                                    <span key="t-notifications" style="font-size: 130%">اعددادت النظام</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{route('models.index')}}" key="t-inbox">النماذج</a></li>
                                    <li><a href="{{route('subSection.index')}}" key="t-inbox">الإدارات</a></li>
                                    <li><a href="{{route('Staffing.index')}}" key="t-inbox">الملاك الوظيفي</a></li>
                                    <li><a href="{{route('banks.index')}}" key="t-inbox">المـصـارف</a></li>
                                    <li><a href="{{route('Specialties.index')}}" key="t-inbox">التخصـصـات</a></li>
                                    <li><a href="{{route('admin.logs')}}" key="t-inbox">الحــركــات</a></li>
                                </ul>
                            </li>
                            @endcan

                            @can('user-list')
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bxs-user-detail"></i>
                                    <span key="t-contacts" style="font-size: 130%">إدارة الــمــسـتــخـدمـيـن</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('user-list')
                                    <li><a href="{{route('users.index')}}">كــل المـستـخـدمـيـن</a></li>
                                    @endcan

                                    @can('user-create')
                                    <li><a href="{{route('users.create')}}" key="t-user-list">إضـافـة مـسـتـخـدم جـديـد</a></li>
                                    @endcan

                                    @can('role-list')
                                    <li><a href="{{route('roles.index')}}" key="t-inbox">الصلاحيات</a></li>
                                    @endcan

                                </ul>
                            </li>
                            @endcan





                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->





            @if(auth()->user()->notifications->where('show',0)->count() > 0)
            @foreach (auth()->user()->notifications->where('show',0) as $not)
                <script>
                    var title = {!! json_encode($not->title) !!};
                    var desc = {!! json_encode($not->desc) !!};

                    let permission = Notification.permission;
                    if(permission === "granted") {
                       showNotification(title,desc);
                    } else if(permission === "default"){
                       requestAndShowPermission(title,desc);
                    }
                    function showNotification(title,desc) {
                       var title = title;
                       icon = "{{asset('assets/images/logo.png')}}"
                       if(desc == null){
                          var body = "";
                       }else{
                          var body = desc;
                       }
                       var notification = new Notification(title, { body, icon });
                       notification.onclick = () => {
                              notification.close();
                              window.parent.focus();
                       }
                   }
                   function requestAndShowPermission(title,desc) {
                      Notification.requestPermission(function (permission) {
                         if (permission === "granted") {
                               showNotification(title,desc);
                         }
                      });
                   }

                </script>
            @endforeach

            <script>
                var url = "{{ route('changeShowNotification') }}";
                $.ajax({
                    url: url,
                    type: "get",
                    data:{
                        _token:'{{ csrf_token() }}'
                    },
                    cache: false,
                    dataType: 'json',
                });
            </script>

            @endif


            <script>
		setInterval(function()
		{
			var url = "{{route('notificationsCheck')}}";
			$.ajax({
				url: url,
				type: "get",
				data:{
					_token:'{{ csrf_token() }}'
				},
				cache: false,
				dataType: 'json',
				success: function(dataResult){
					var resultData = dataResult.data;
					var count = dataResult.count;
					// if(count == 0){
					// 	document.getElementById("billing").style.display = "none";
					// 	document.getElementById("notifCount").style.display = "none";
					// }else{
					// 	show2 = document.getElementById("notifCount");
					// 	show = document.getElementById("billing");
					// 	show.style.display = "block";
					// 	show.innerHTML = count;
					// 	show2.style.display = "block";
					// 	show2.innerHTML = count;
					// }

					if(resultData.length == 0){
						return;
					}else{
						var data = resultData;
						let permission = Notification.permission;
						for(i=0; i < data.length; i++){
							if(permission === "granted") {
							   showNotification(data[i]['title'],data[i]['desc']);
							} else if(permission === "default"){
							   requestAndShowPermission(data[i]['title'],data[i]['desc']);
							}
						}

						function showNotification(title,desc) {
						   var title = title;
						   icon = "{{asset('assets/images/logo.png')}}"
                           if(desc == null){
                                var body = "";
                            }else{
                                var body = desc;
                            }
						   var notification = new Notification(title, { body, icon });
						   notification.onclick = () => {
								  notification.close();
								  window.parent.focus();
						   }
						}
						function requestAndShowPermission(title,desc) {
						   Notification.requestPermission(function (permission) {
							  if (permission === "granted") {
									showNotification(title,desc);
							  }
						   });
						}
						var url = "{{ route('changeShowNotification') }}";
						$.ajax({
							url: url,
							type: "get",
							data:{
								_token:'{{ csrf_token() }}'
							},
							cache: false,
							dataType: 'json',
						});

					}
				}
			});
		}, 120000);
            </script>


            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">



                @yield('content')



                @include('includes.footer')


            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        @yield('modals')

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center px-3 py-4">

                    <h5 `class="m-0 me-2">مـــظــهـــر</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">أخــتــيــار مــظــهــر</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-3.jpg" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice active" type="checkbox" id="rtl-mode-switch" checked>
                        <label class="form-check-label" for="rtl-mode-switch">إضــائــة</label>
                    </div>

                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-4.jpg" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-5">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-rtl-mode-switch">
                        <label class="form-check-label" for="dark-rtl-mode-switch">مــعــتــم</label>
                    </div>


                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

        <!-- apexcharts -->
        <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Saas dashboard init -->
        <script src="{{asset('assets/js/pages/saas-dashboard.init.js')}}"></script>

        <!-- select 2 plugin -->
        <script src="{{asset('assets\libs\select2\js\select2.full.min.js')}}" charset="utf-8"></script>
        <script src="{{asset('assets/libs/select2/js/select2.min.js')}}"></script>
        <!-- dropzone plugin -->
        <script src="{{asset('assets/libs/dropzone/min/dropzone.min.js')}}"></script>

        <!-- init js -->
        <script src="{{asset('assets/js/pages/ecommerce-select2.init.js')}}"></script>

        <script src="{{asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

        <script>
            $( document ).ready(function() {
                document.getElementById("rtl-mode-switch").trigger('click');
        });
        </script>
        <script src="{{asset('assets/js/app.js')}}"></script>

        <script>
            window.addEventListener('load', function() {
              var loadingOverlay = document.getElementById('loading-overlay');
              loadingOverlay.style.display = 'none';
            });

            $( document ).ready(function() {
        $('.js-example-basic-single').select2({
          dir: "rtl",
          maximumSelectionLength: 1 ,

        });
        $('.js-example-basic-multiple').select2();

        document.getElementById("rtl-mode-switch").trigger('click');
});

        </script>

@yield('js')

    </body>
</html>
