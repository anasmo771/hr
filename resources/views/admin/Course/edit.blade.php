

            @extends('admin.layout.master')

            @section('title')
                <title> تـعديـل الدورة  <span style="color: blue;">{{$course->name_course}}</span>  </title>
            @endsection

            @section('css')
            <style>

                a.dark {
                    background-color: #0ea2bd;
                    border: 1px solid #0ea2bd;
                    border-radius: 3px;
                    color: #0ea2bd;
                    font-family: Montserrat, sans-serif;
                    font-weight: 500;
                    padding: 8px 20px;
                }

                a.dark.ghost {
                    background-color: transparent;
                    color: #0ea2bd;
                }

                        </style>
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
                                            <li class="breadcrumb-item" aria-current="page">تـعديـل الدورة  <span style="color: blue;">{{$course->name_course}}</span> </li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <div class="page-header-title">
                                            <h2 class="mb-0">تـعديـل الدورة  <span style="color: blue;">{{$course->name_course}}</span> </h2>
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

                                <h4 class="card-title">تـعديـل الـدورة</h4>

                                @include('includes.messages')

                                        <!-- <p class="card-title-desc">Fill all information below</p> -->

                                        <form action="{{route('courses.update',[$course->id])}}" method="post" enctype="multipart/form-data" id="form">
                                          @csrf
                                          {{ method_field('PATCH') }}
                                          <div class="row mt-3">
                                            <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="fullname">اســم الدورة</label>
                                                        <input id="fullname" value="{{$course->name_course}}" name="course_name" type="text" class="form-control" placeholder="اسم الدورة" required oninvalid="this.setCustomValidity('الرجاء ادخال اسم الدورة')"  oninput="this.setCustomValidity('')">
                                                        <span id="Name_text"></span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="worktitle">نـوع
                                                            الدورة</label>
                                                        <select name="course_type"class="form-control"
                                                            lang="ar" required
                                                            oninvalid="this.setCustomValidity('الرجاء تحديد نوع الدورة')"
                                                            oninput="this.setCustomValidity('')">
                                                            <option selected value="{{ $course->course_type }}">{{ $course->course_type }}</option>
                                                            <option value="داخلية">داخلية</option>
                                                            <option value="خارجية">خارجية</option>
                                                            <option value="ورشة عمل">ورشة عمل</option>
                                                            <option value="مؤتمر">مؤتمر</option>
                                                            <option value="ندوة">ندوة</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="email">تاريــخ البداية</label>
                                                        <input id="startDate" value="{{$course->from_date}}" name="from_date" type="text" class="form-control"  oninput="dateValidation(this)" placeholder="YYYY-MM-DD" required oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ البداية')" oninput="this.setCustomValidity('')">
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="email">تاريــخ النهاية</label>
                                                        <input id="earnDate" value="{{$course->to_date}}" name="to_date" type="text" class="form-control"  oninput="dateValidation(this)" placeholder="YYYY-MM-DD" required oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ النهاية')"  oninput="this.setCustomValidity('')">
                                                        <span id="startD_text"></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="email">الـجـهـة الـمـنـفـدة</label>
                                                        <input id="" name="agency" type="text" class="form-control" value="{{$course->agency}}" placeholder="الـجـهـة الـمنـفدة" required oninvalid="this.setCustomValidity('الرجاء ادخال الجهة المنفدة')"  oninput="this.setCustomValidity('')">
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="email">مـكـان الـدورة</label>
                                                        <input id="" name="place" type="text" class="form-control" value="{{$course->place}}" placeholder="مـكـان الـدورة" required oninvalid="this.setCustomValidity('الرجاء ادخال مـكـان الـدورة')"  oninput="this.setCustomValidity('')">
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="email">رقـم القــرار</label>
                                                        <input id="" name="number" type="text" class="form-control" value="{{$course->number}}" placeholder="رقـم القــرار" required oninvalid="this.setCustomValidity('الرجاء ادخال رقـم القــرار')"  oninput="this.setCustomValidity('')">
                                                    </div>
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="mb-3 ">
                                                        <label for="email">وثـيـقـة  (اختياري) </label>
                                                        <input name="files[]" type="file" class="form-control" multiple>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    <label for="worktitle" class="col-form-label" style="font-size:100%;">ملاحـظـات</label>
                                                    <textarea class="form-control" id="description" name="notes" rows="3" placeholder="ملاحظات">{{$course->notes}}</textarea>
                                                </div>



                                            </div>


                                            <div class="row mt-3 justify-content-center" style="text-align: center;">
                                                <div class="col-12">
                                                              <a onclick="addNewItem()"  style="color: rgb(255, 255, 255); " class="btn btn-warning">إضافة موظف</a>
                                                </div>
                                            </div>

                                            <div class="row mt-3 mb-3">
                                                <span id="addNewInputs">
                                                    @foreach ($course->employees as $employee)
                                                        <div class="row form-row">
                                                            <div class="col-sm-3">
                                                                <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;">الـموظـف</label>
                                                                <select name="emp_id[]" class="form-control js-example-basic-single"
                                                                    multiple="multiple" lang="ar" required
                                                                    oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                                                                    oninput="this.setCustomValidity('')">
                                                                    <option selected value="{{$employee->emp_id}}">{{$employee->emp->person->name}}</option>
                                                                    @foreach ($employees as $emp)
                                                                    <option value="{{ $emp->id }}">{{ $emp->person->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <div class="mb-3">
                                                                    <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;"> النتيجة</label>
                                                                    <input id="" name="result[]" value="{{$employee->result}}" type="text" class="form-control" placeholder="النتيجة">
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="worktitle" class="col-form-label" style="font-size:100%;"> ملاحظة (اختياري) </label>
                                                                <input class="form-control" type="text" value="{{$employee->notes}}" id="description" name="note[]" placeholder="ملاحظة">
                                                            </div>

                                                            <div class="col-3 col-sm-3" style="margin-top: 55px;">
                                                                <div class="form-group" style="font-family: 'cairo';">
                                                                    <label></label>
                                                                    <a href="#" class="btn btn-danger" onclick="removeItem(this)">حذف</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </span>
                                            </div>


                                            <div class="d-flex flex-wrap ">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">حـفـظ الـتعـديـلات</button>
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
@section('script')
  <script type="text/javascript">


function removeItem(element) {
    // Find the closest parent div with class 'row'
    var parentRow = element.closest('.row');

    // Check if the parent row exists
    if (parentRow) {
        // Find the select element within the row and get its selected value(s)
        var selectedValues = Array.from(parentRow.querySelectorAll('.js-example-basic-single option:checked'), option => option.value);

        // Remove the selected values from the selectedOptions array
        selectedValues.forEach(val => {
            var index = selectedOptions.indexOf(val);
            if (index > -1) {
                selectedOptions.splice(index, 1);
            }
        });

        // Update other select elements to re-enable the options
        updateSelectOptions();

        // Remove the parent row
        parentRow.remove();
    }
}


var selectedOptions = []; // Array to track selected options

function updateSelectOptions() {
    document.querySelectorAll('.js-example-basic-single').forEach(function(select) {
        select.querySelectorAll('option').forEach(function(option) {
            if (selectedOptions.includes(option.value) && !option.selected) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
}

function onSelectChange() {
    selectedOptions = []; // Reset the selected options array
    document.querySelectorAll('.js-example-basic-single').forEach(function(select) {
        Array.from(select.selectedOptions).forEach(function(option) {
            selectedOptions.push(option.value);
        });
    });
    updateSelectOptions();
}


function addNewItem() {
    // Create a new div element for the row
    var newRow = document.createElement('div');
    newRow.className = 'row form-row';

    // Add the HTML content to the new row
    newRow.innerHTML = `
        <div class="col-sm-3">
            <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;">الـموظـف</label>
            <select name="emp_id[]" class="form-control js-example-basic-single"
                multiple="multiple" lang="ar" required
                oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                oninput="this.setCustomValidity('')">
                @foreach ($employees as $emp)
                s<option value="{{ $emp->id }}">{{ $emp->person->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-3">
            <div class="mb-3">
                <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;"> النتيجة</label>
                <input id="" name="result[]" type="text" class="form-control" placeholder="النتيجة">
            </div>
        </div>

        <div class="col-sm-3">
            <label for="worktitle" class="col-form-label" style="font-size:100%;"> ملاحظة (اختياري) </label>
            <input class="form-control" type="text" id="description" name="note[]" placeholder="ملاحظة">
        </div>

        <div class="col-3 col-sm-3" style="margin-top: 55px;">
            <div class="form-group" style="font-family: 'cairo';">
                <label></label>
                <a href="#" class="btn btn-danger" onclick="removeItem(this)">حذف</a>
            </div>
        </div>`;

    // Append the new row to the 'addNewInputs' div
    document.getElementById('addNewInputs').appendChild(newRow);

    // Initialize Select2 on the new select element
    $(newRow).find('.js-example-basic-single').select2({
        // Add Select2 options here
        // Example: placeholder: 'Select an option',
        // language: 'ar' for Arabic
    }).on('change', onSelectChange);

    updateSelectOptions(); // Update select options to disable already selected ones

}


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

  function N_id_validation(){
    var form = document.getElementById("form");
    var N_id = document.getElementById("N_id").value;
    var text_N_id = document.getElementById("text_N_id");

    var pattern = "^[0-9]*$";


    if(N_id.length === 12 && N_id.match(pattern) ){
      console.log(N_id.length);
      form.classList.add("valid");
      form.classList.remove("invalid");

      text_N_id.innerHTML = "مــــوافق";
      text_N_id.style.color ="#00ff00";

    }else{
      console.log(N_id.length);
      form.classList.add("invalid");
      form.classList.remove("valid");

      text_N_id.innerHTML = "رقـــم وطـــني يجب ان يــكون 12 رقـــم";
      text_N_id.style.color ="#ff0000";

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

  function startDate_validation(){
    var form = document.getElementById("form");
    var startDate = document.getElementById("startDate").value;
    var earnDate = document.getElementById("earnDate").value;
    var text = document.getElementById("startD_text");
    console.log(startDate);
    console.log(earnDate);
    if(startDate <= earnDate){
      form.classList.add("valid");
      form.classList.remove("invalid");
      text.innerHTML = "مـــوافـــق";
      text.style.color ="#00ff00";
    }else{
      form.classList.add("invalid");
      form.classList.remove("valid");
      text.innerHTML = "تاريخ  لايمكن ان يكون قبل تاريخ البدء";
      text.style.color ="#ff0000";
    }
  }

  function dateValidation(inputElement) {
    var dateRegex = /^\d{1,2}-\d{1,2}-\d{4}$/; // Regex for date format DD-MM-YYYY
    var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // Regex for date format DD/MM/YYYY

    var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/; // Regex for date format DD-MM-YYYY
    var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // Regex for date format DD/MM/YYYY
    // Check if the input matches either of the date formats
    if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) || dateRegex3.test(inputElement.value) || dateRegex4.test(inputElement.value)) {
        // If the date does not match the format, show a custom validity message
        inputElement.setCustomValidity('');
    } else {
        inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');

        // If the date matches one of the formats, clear any custom validity message
    }
}

  </script>
@endSection
