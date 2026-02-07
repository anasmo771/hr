
        @extends('admin.layout.master')

        @section('title')
            <title> تعديل بـيـانـات المـسـتـخـدم {{$user->name}} </title>
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
                                        <li class="breadcrumb-item" aria-current="page">تعديل بـيـانـات المـسـتـخـدم {{$user->name}}</li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <div class="page-header-title">
                                        <h2 class="mb-0">تعديل بـيـانـات المـسـتـخـدم {{$user->name}}</h2>
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

                                <form action="{{route('users.update',[$user->id])}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="fullname">الإســم بـالـكـامـل</label>
                                                <input id="fullname" name="name" type="text" value="{{$user->name}}" class="form-control" placeholder="اسم المستخدم" required oninvalid="this.setCustomValidity('الرجاء ادخال اسم المستخدم')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="email">البــريــد الإلـكـترونـي</label>
                                                <input id="email" name="email" type="text"value="{{$user->email}} "class="form-control" placeholder="name@example.com" required oninvalid="this.setCustomValidity('الرجاء ادخال البريد الالكتروني')" oninput="this.setCustomValidity('')">
                                            </div>
                                       </div>

                                       <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="email">كــلـمـة مـرور جديدة</label>
                                                <input id="email" name="password" type="password" class="form-control" >
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="email">إعــادة كــلـمـة الــمرور</label>
                                                <input id="email" name="password_confirmation" type="password" class="form-control">
                                            </div>
                                        </div>



                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="control-label">الــوظــيفــة</label>
                                                    <select class="form-control select" name="roles">
                                                        @if ($user->roles->first())
                                                        <option value="{{$user->roles->first()->id}}" selected>{{$user->roles->first()->name}}</option>
                                                        @foreach ($roles as $role)
                                                            @if($role->id != $user->roles->first()->id)
                                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                                            @endif
                                                        @endforeach
                                                        @else
                                                        @foreach ($roles as $role)
                                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>



                                        <div class="mt-3">

                                            <div class="avatar-xs">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="{{ asset(Storage::url($user->image)) }}" width="50" alt="" class="rounded-circle avatar-xs">
                                                  </a>
                                              </div>

                                            <label for="formFile" class="form-label">صــورة شــخــصــيـة</label>
                                            <input class="form-control" type="file" id="formFile" name="image">
                                        </div>

                                    </div>

                                    <br>

                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn  waves-effect waves-light">حفظ التعديلات</button>
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

@endsection

@section('script')
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

</script>

@endsection
