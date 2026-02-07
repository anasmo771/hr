

        @extends('admin.layout.master')

        @section('title')
            <title> اضافة مستخدم </title>
        @endsection

        @section('content')
            <div class="pc-container">
                <div class="pc-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
                                        <li class="breadcrumb-item" aria-current="page">اضافة مستخدم</li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <div class="page-header-title">
                                        <h2 class="mb-0">اضافة مستخدم</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->

                    @include('admin.layout.validation-messages')

                    <!-- [ Main Content ] start -->




                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">بـيـانـات</h4>


                                <!-- <p class="card-title-desc">Fill all information below</p> -->

                                <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data" id="form">
                                  @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="fullname">الإســم بـالـكـامـل</label>
                                                <input id="fullname" name="name" type="text" class="form-control" onkeyup="name_validation()" placeholder="اسم المستخدم" required oninvalid="this.setCustomValidity('الرجاء ادخال اسم المستخدم')" oninput="this.setCustomValidity('')">
                                                <span id="Name_text"></span>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email">كــلـمـة الــمرور</label>
                                                <input id="pw" name="password" type="password" class="form-control" onkeyup="pw_validation()" required oninvalid="this.setCustomValidity('الرجاء ادخال كلمة المرور')" oninput="this.setCustomValidity('')">
                                                <span id="pw_text"></span>
                                            </div>

                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="email">البــريــد الإلـكـترونـي</label>
                                                <input id="email" name="email" type="text" class="form-control" onkeyup="validation()" placeholder="name@example.com" required oninvalid="this.setCustomValidity('الرجاء ادخال البريد الالكتروني')" oninput="this.setCustomValidity('')">
                                                <span id="text"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email">إعــادة كــلـمـة الــمرور</label>
                                                <input id="rePw" name="password_confirmation" type="password" onkeyup="repw_validation()" class="form-control" required oninvalid="this.setCustomValidity('الرجاء اعادة ادخال كلمة المرور')" oninput="this.setCustomValidity('')">
                                                <span id="repw_text"></span>
                                            </div>


                                        </div>
                                        <div class="col-sm-6 mb-3">
                                                <label class="control-label">الـصـلاحـيـة</label>
                                                <select class="form-control select" name="roles">
                                                    <option selected disabled>اخــتــيـار</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        <div class="col-sm-6">
                                            <label for="formFile" class="form-label">صــورة شــخــصــيـة</label>
                                            <input class="form-control" type="file" id="formFile" name="image">
                                        </div>

                                    </div>

                                    <br>

                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">حـفـظ</button>
                                        <!-- <button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button> -->
                                    </div>
                                </form>

                            </div>
                        </div>

                        <!-- <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3">Product Images</h4>

                                <form action="/" method="post" class="dropzone">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>

                                    <div class="dz-message needsclick">
                                        <div class="mb-3">
                                            <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                        </div>

                                        <h4>Drop files here or click to upload.</h4>
                                    </div>
                                </form>
                            </div>

                        </div>  -->
                        <!-- end card-->


                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        @endSection


        @section('js')
<!-- /Right-bar -->
<script type="text/javascript">

function validation(){
  var form = document.getElementById("form");
  var email = document.getElementById("email").value;
  var text = document.getElementById("text");

  var pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

  if(email.match(pattern)){
    form.classList.add("valid");
    form.classList.remove("invalid");

    text.innerHTML = "ايــمــيــل صــحــيــح";
    text.style.color ="#00ff00";

  }else{
    form.classList.add("invalid");
    form.classList.remove("valid");

    text.innerHTML = "ايميل يجب ان يحتوي علي @ و .com";
    text.style.color ="#ff0000";

  }


}

function pw_validation(){
  var form = document.getElementById("form");
  var Pw = document.getElementById("pw").value;
  var text = document.getElementById("pw_text");

  if(Pw.length >= 8){
    form.classList.add("valid");
    form.classList.remove("invalid");

    text.innerHTML = "باسورد صحيح ";
    text.style.color ="#00ff00";

  }else{
    form.classList.add("invalid");
    form.classList.remove("valid");

    text.innerHTML = "بــاسورد يجب ان يكون 8 خانات علي الأقل";
    text.style.color ="#ff0000";

  }


}

function repw_validation(){
  var form = document.getElementById("form");
  var Pw = document.getElementById("pw").value;
  var rePw = document.getElementById("rePw").value;
  var text = document.getElementById("repw_text");

  if(Pw === rePw){
    form.classList.add("valid");
    form.classList.remove("invalid");

    text.innerHTML = "باسورد صحيح ";
    text.style.color ="#00ff00";

  }else{
    form.classList.add("invalid");
    form.classList.remove("valid");

    text.innerHTML = "يجب اعادة ادخال نفس الباسورد";
    text.style.color ="#ff0000";

  }


}

function name_validation(){

  var form = document.getElementById("form");
  var email = document.getElementById("fullname").value;
  var text = document.getElementById("Name_text");

  var pattern = /^([^0-9]*)$/ ;

if(email.match(pattern)){
form.classList.add("valid");
form.classList.remove("invalid");

text.innerHTML = " اسم صــحــيــح";
text.style.color ="#00ff00";

}else{
form.classList.add("invalid");
form.classList.remove("valid");

text.innerHTML = "الاسم لايمكن ان يحتوي علي أرقام";
text.style.color ="#ff0000";

}

}
</script>

@endSection
