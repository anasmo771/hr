
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


	<link rel="stylesheet" href="{{asset('temp/css/bootstrap.min.css')}}">

	<link rel="shortcut icon" type="image/x-icon" href="{{asset('temp/img/loggo.png')}}">

    <title>نـظـام إدارة شـؤون الـمـوظـفـيين </title>


    <style>
    input::placeholder
    {
        text-align: right;      /* for Chrome, Firefox, Opera */
    }

@import url(https://fonts.googleapis.com/earlyaccess/amiri.css);


body {
    color: #000;
    overflow-x: hidden;
    height: 100%;
    background-color: #555fcf;
    background-repeat: no-repeat;
}

.card {
    z-index: 0;
    background-color: #ECEFF1;
    padding-bottom: 20px;
    margin-top: 90px;
    margin-bottom: 90px;
    border-radius: 10px;
}

.top {
    padding-top: 40px;
    padding-left: 13% !important;
    padding-right: 13% !important;
}

/*Icon progressbar*/
#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: #455A64;
    padding-left: 0px;
    margin-top: 30px;
}

#progressbar li {
    list-style-type: none;
    font-size: 13px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400;
}

#progressbar .step0:before {
    font-family: FontAwesome;
    content: "\f10c";
    color: #fff;
}

#progressbar li:before {
    width: 40px;
    height: 40px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    background: #C5CAE9;
    border-radius: 50%;
    margin: auto;
    padding: 0px;
}

/*ProgressBar connectors*/
#progressbar li:after {
    content: '';
    width: 100%;
    height: 12px;
    background: #C5CAE9;
    position: absolute;
    left: 0;
    top: 16px;
    z-index: -1;
}

#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    position: absolute;
    left: -50%;
}

#progressbar li:nth-child(2):after, #progressbar li:nth-child(3):after {
    left: -50%;
}

#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    position: absolute;
    left: 50%;
}

#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

/*Color number of the step and the connector before it*/
#progressbar li.active:before, #progressbar li.active:after {
    background: #651FFF;
}

#progressbar li.active:before {
    font-family: FontAwesome;
    content: "\f00c";
}

.icon {
    width: 60px;
    height: 60px;
    margin-right: 15px;
}

.icon-content {
    padding-bottom: 20px;
}




.btn-dark {
    background-color: #555fcf;
    border: 1px solid #555fcf;
    border-radius: 50px;
    padding: 15px 40px;
    color: white;
text-decoration:none;
}









.hh-grayBox {
	margin-bottom: 20px;
	padding: 35px;
  margin-top: 20px;
}
.order-tracking{
	text-align: center;
	width: 33.33%;
	position: relative;
	display: block;
}
.order-tracking .is-complete{
	display: block;
	position: relative;
	border-radius: 50%;
	height: 30px;
	width: 30px;
	border: 0px solid #AFAFAF;
	background-color: #f7be16;
	margin: 0 auto;
	transition: background 0.25s linear;
	-webkit-transition: background 0.25s linear;
	z-index: 2;
}
.order-tracking .is-complete:after {
	display: block;
	position: absolute;
	content: '';
	height: 14px;
	width: 7px;
	top: -2px;
	bottom: 0;
	left: 5px;
	margin: auto 0;
	border: 0px solid #AFAFAF;
	border-width: 0px 2px 2px 0;
	transform: rotate(45deg);
	opacity: 0;
}
.order-tracking.completed .is-complete{
	border-color: #555fcf;
	border-width: 0px;
	background-color: #555fcf;
}


.order-tracking.completed .is-complete:after {
	border-color: #fff;
	border-width: 0px 3px 3px 0;
	width: 7px;
	left: 11px;
	opacity: 1;
}
.order-tracking p {
	color: #A4A4A4;
	font-size: 16px;
	margin-top: 8px;
	margin-bottom: 0;
	line-height: 20px;
}
.order-tracking p span{font-size: 14px;}
.order-tracking.completed p{color: #000;}
.order-tracking.canceld p{color: #000;}
.order-tracking::before {
	content: '';
	display: block;
	height: 3px;
	width: calc(100% - 40px);
	background-color: #f7be16;
	top: 13px;
	position: absolute;
	left: calc(-50% + 20px);
	z-index: 0;
}
.order-tracking:first-child:before{display: none;}
.order-tracking.completed:before{background-color: #555fcf;}
.order-tracking.canceld:before{background-color: red;}

#but button:hover::before {
	content: attr(for);
	font-family: Roboto, -apple-system, sans-serif;
	text-transform: capitalize;
	font-size: 20px;
	position: absolute;
	top: 170%;
	left: 0;
	right: 0;
	opacity: 0.75;
	background-color: #323232;
	color: #fff;
	padding: 4px;
	border-radius: 3px;
  display: block;
}

    </style>

<style type="text/css">
        @import url(https://fonts.googleapis.com/css2?family=Cairo:wght@500&display=swap);
        * {
          font-family: 'Cairo', sans-serif;
        }
      </style>

  </head>
  <body>

<div class="container px-1 px-md-4 py-5 mx-auto" dir="rtl" style="float:center !important;">


    <div class="card" style="text-align:center !important;">
{{--
    <div style="text-align: center; margin-top: -30px; margin-bottom: -80px;">
        <a href="{{route('login')}}"><img src="{{asset('loginForm/image/loggo.png')}}" alt="" style="width: 100px; height: 100px;" width="50" height="50" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm"></a>
    </div> --}}


        <div class="row d-flex justify-content-center">
            <div class="d-flex text-sm-center text-center" style="text-align: center !important; font-family: 'Amiri'; font-size: 30px; padding-top: 35px;" >
                <a href="#" style="text-decoration:none;"><p> <span style="color: #555fcf;" class="font-weight-bold">نـظـام إدارة شـؤون الـمـوظـفـيين </span></p></a><br>
            </div>
        </div>


        <div class="row d-flex justify-content-center">
            <div class="d-flex text-sm-center text-center" style="text-align: center !important; font-family: 'Amiri'; font-size: 25px;" >
               <p> مرحبآ الموظف <span style="color: #555fcf;"> {{$employee->name}} </span> </p> <br>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="d-flex text-sm-center text-center" style="text-align: center !important; font-family: 'Amiri'; font-size: 25px;" >
               <p> إشعار بالخصوص <span style="color: #555fcf;"> {{$title}} </span> </p> <br>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="d-flex text-sm-center text-center" style="text-align: center !important; font-family: 'Amiri'; font-size: 25px;" >

                @if($priority == 1)
                <p> الاهمية <span style="color: #555fcf;"> ضــعــيــف </span> </p> <br>
            @elseif($priority == 2)
                <p> الاهمية <span style="color: #555fcf;"> مــتــوســط </span> </p> <br>
            @else
                <p> الاهمية <span style="color: #555fcf;"> مـــهــــم </span> </p> <br>
            @endif

            </div>
        </div>

        <div class="row d-flex justify-content-center"style="padding-top: -50px;" >
            <div class="d-flex text-sm-center text-center" style="text-align: center !important; font-family: 'Amiri'; font-size: 25px;" >
                <p> {{$desc}} </p><br>
            </div>
        </div>

        {{-- <div class="row d-flex justify-content-center">
            <div class="d-flex text-sm-center text-center" style="text-align: center !important; font-family: 'Amiri'; font-size: 25px; padding-bottom: 50px; color: white;" >
				<a href="{{route('home')}}" class="btn btn-success float-center veiwbutton" style="font-family: 'Amiri'; font-size: 20px; color: white;">الدخول الان</a>
            </div>
        </div> --}}

    </div>




</div>

	<script src="{{asset('temp/js/jquery-3.5.1.min.js')}}"></script>
	<script src="{{asset('temp/js/bootstrap.min.js')}}"></script>
<script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
  </body>
</html>










