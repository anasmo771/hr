@extends('admin.layout.master')

@section('title')
<title>إضافة إجازة</title>
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
                                <li class="breadcrumb-item" aria-current="page">إضــافــة إجــازة جـديـدة</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="page-header-title">
                                <h2 class="mb-0">إضــافــة إجــازة جـديـدة</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">تـقـديـم إجـازة للـمـوظـف</h4>

                            <form action="{{ route('saveVacation', ['id' => 0]) }}" onSubmit="return lastCheck()"
                                  method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-12 pt-3">
                                        <h4 class="mb-sm-0 font-size-18" style="color: blue; text-align: center;"
                                            id="description"></h4>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;">الموظف
                                            <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                                        </label>
                                        <select name="emp_id" style="width: 100%;" class="form-control js-example-basic-single"
                                                multiple="multiple" lang="ar" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                                                oninput="this.setCustomValidity('')">
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->person->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;">نوع الإجازة</label>
                                        <select onchange="something()" name="type" class="form-control" id="typeOf"
                                                lang="ar" required
                                                oninvalid="this.setCustomValidity('الرجاء تحديد نوع الاجازة')"
                                                oninput="this.setCustomValidity('')">
                                            <option selected disabled>الرجاء تحديد نوع الإجازة</option>
                                            <option value="إجازة سنوية">إجازة سنوية</option>
                                            <option value="إجازة مرضية">إجازة مرضية</option>
                                            <option value="إجازة وضع">إجازة وضع</option>
                                            <option value="إجازة زواج">إجازة زواج</option>
                                            <option value="إجازة حج">إجازة حج</option>
                                            <option value="إجازة وفاة الزوج">إجازة وفاة الزوج</option>
                                            <option value="إجازة بدون مرتب">إجازة بدون مرتب</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4" style="display: none;" id="days">
                                        <label for="dayys" class="col-form-label col-lg-4" style="font-size:100%;">عــدد الأيــام</label>
                                        <input id="dayys" name="days" type="number" min="0"
                                               onchange="setDate()" value="1" class="form-control"
                                               placeholder="عدد الايام">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="startDate" class="col-form-label col-lg-4" style="font-size:100%;">تـاريـخ الـبـداية</label>
                                        <input id="startDate" name="start_date" type="date" onchange="callDate(this)"
                                               class="form-control" placeholder="تاريخ البداية" required
                                               oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ بداية الإجازة')"
                                               oninput="this.setCustomValidity('')">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="endDate" class="col-form-label col-lg-4" style="font-size:100%;">تـاريـخ الـمـباشـرة</label>
                                        <input id="endDate" name="end_date" type="date" class="form-control"
                                               oninput="dateValidation(this)" placeholder="YYYY-MM-DD">
                                    </div>

                                    <div class="col-lg-4" id="work_title">
                                        <label for="worktitle" class="col-form-label col-lg-4" style="font-size:100%;">ســبـب الإجــازة</label>
                                        <input id="worktitle" name="reason" type="text" class="form-control"
                                               placeholder="سبب الإجازة (اختياري)">
                                    </div>

                                    <div class="col-lg-4" style="display: none;" id="person">
                                        <label for="personInput" class="col-form-label col-lg-4" style="font-size:100%;">مــرافــق</label>
                                        <input id="personInput" name="person" type="checkbox"
                                               style="margin-top: 20px; width: 20px; height: 20px;">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="personInput2" class="col-form-label col-lg-4" style="font-size:100%;">الموافقة مسبقآ</label>
                                        <input id="personInput2" name="approve" onchange="checkVecation()" type="checkbox"
                                               style="margin-top: 20px; width: 20px; height: 20px;">
                                    </div>

                                    <div class="col-sm-4" style="display: none; margin-top: 20px;" id="showFiles">
                                        <div class="mb-3">
                                            <label for="email">صـورة من الاجازة (اختياري)</label>
                                            <input name="files[]" type="file" class="form-control" multiple>
                                        </div>
                                    </div>

                                </div>

                                <div class="row content-start">
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary">حـفـظ الإجــازة</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection

@section('script')
<script>
    function checkVecation() {
        if (document.getElementById('personInput2').checked) {
            document.getElementById('showFiles').style.display = "block";
        } else {
            document.getElementById('showFiles').style.display = "none";
        }
    }

    function something() {
        var sel = document.getElementById('typeOf');
        if (sel.value == "إجازة سنوية") {
            document.getElementById('description').innerHTML = "ملاحظة الاجازة السنوية علي حسب رصيد إجازات الموظف";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "blue";
            document.getElementById('days').style.display = "block";
            document.getElementById('person').style.display = "none";
            document.getElementById('personInput').checked = false;
        } else if (sel.value == "إجازة وضع") {
            document.getElementById('description').innerHTML = "ملاحظة إجازة الوضع تمنح 3 أشهر فقط";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "blue";
            document.getElementById('days').style.display = "none";
            document.getElementById('person').style.display = "none";
            document.getElementById('personInput').checked = false;
        } else if (sel.value == "إجازة مرضية") {
            document.getElementById('description').innerHTML = "ملاحظة الاجازة المرضية علي حسب المدة المعطاة من الدكتور";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "blue";
            document.getElementById('days').style.display = "none";
            document.getElementById('person').style.display = "none";
            document.getElementById('personInput').checked = false;
        } else if (sel.value == "إجازة زواج") {
            document.getElementById('description').innerHTML = "ملاحظة إجازة الزواج تمنح اسبوعين فقط";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "blue";
            document.getElementById('days').style.display = "none";
            document.getElementById('person').style.display = "none";
            document.getElementById('personInput').checked = false;
        } else if (sel.value == "إجازة حج") {
            document.getElementById('description').innerHTML = "ملاحظة إجازة الحج بيت الله تمنح 3 أشهر فقط";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "blue";
            document.getElementById('days').style.display = "none";
            document.getElementById('work_title').style.display = "none";
            document.getElementById('person').style.display = "block";
            document.getElementById('personInput').checked = false;
        } else if (sel.value == "إجازة وفاة الزوج") {
            document.getElementById('description').innerHTML = "ملاحظة إجازة وفاة الزوج تمنح 4 أشهر و 10 أيام فقط";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "none";
            document.getElementById('days').style.display = "none";
            document.getElementById('work_title').style.display = "none";
            document.getElementById('person').style.display = "none";
            document.getElementById('personInput').checked = false;
        } else if (sel.value == "إجازة بدون مرتب") {
            document.getElementById('description').innerHTML = "ملاحظة الاجازة بدون مرتب تمنح سنة فقط لغير المرافق";
            document.getElementById('startDate').value = "";
            document.getElementById('endDate').value = "";
            document.getElementById('description').style.color = "blue";
            document.getElementById('days').style.display = "none";
            document.getElementById('person').style.display = "none";
        }
    }

    function setDate() {
        var days = document.getElementById('dayys');
        document.getElementById('startDate').value = "";
        document.getElementById('endDate').value = "";
        if (days.value == 0) {
            alert("عدد الايام علي الاقل 1");
            days.value = 1;
            return;
        }
    }

    function callDate(inputElement) {
        var dateRegex  = /^\d{1,2}-\d{1,2}-\d{4}$/;
        var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
        var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/;
        var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/;

        if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) ||
            dateRegex3.test(inputElement.value) || dateRegex4.test(inputElement.value)) {
            inputElement.setCustomValidity('');
        } else {
            inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');
            return;
        }

        function addDays2(days, startDate) {
            var endDate = new Date(startDate);
            while (days > 0) {
                endDate.setDate(endDate.getDate() + 1);
                if (endDate.getDay() !== 5 && endDate.getDay() !== 6) { // تخطي الجمعة والسبت
                    days--;
                }
            }
            return endDate;
        }

        var start = document.getElementById('startDate');
        var sel   = document.getElementById('typeOf');
        var days, endD;

        if (sel.value == "إجازة سنوية") {
            days = document.getElementById('dayys');
            if (days.value < 1) {
                alert("عدد الايام علي الاقل 1");
                days.value = 1;
            }
            endD = addDays2(days.value, new Date(start.value));
        } else if (sel.value == "إجازة وضع") {
            endD = addDays(90 - 1, new Date(start.value));
        } else if (sel.value == "إجازة مرضية") {
            // لا حساب تلقائي
        } else if (sel.value == "إجازة زواج") {
            endD = addDays(14 - 1, new Date(start.value));
        } else if (sel.value == "إجازة حج") {
            endD = addDays(90 - 1, new Date(start.value));
        } else if (sel.value == "إجازة وفاة الزوج") {
            endD = addDays(130 - 1, new Date(start.value));
        } else if (sel.value == "إجازة بدون مرتب") {
            endD = addDays(365 - 1, new Date(start.value));
        } else {
            start.value = "";
            document.getElementById('description').style.color = "red";
            document.getElementById('description').innerHTML = "الرجاء تحديد نوع الاجازة قبل تحديد التاريخ";
        }

        if (endD) {
            document.getElementById('endDate').value = endD.toISOString().split('T')[0];
        }
    }

    function addDays(days, date = new Date()) {
        date.setDate(date.getDate() + parseInt(days));
        return date;
    }

    function lastCheck() {
        var sel = document.getElementById('typeOf');
        if (sel.value == "الرجاء تحديد نوع الإجازة") {
            document.getElementById('description').style.color = "red";
            document.getElementById('description').innerHTML = "الرجاء تحديد نوع الاجازة";
            return false;
        }
        return true;
    }

    function dateValidation(inputElement) {
        var dateRegex  = /^\d{1,2}-\d{1,2}-\d{4}$/;
        var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
        var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/;
        var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/;

        if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) ||
            dateRegex3.test(inputElement.value) || dateRegex4.test(inputElement.value)) {
            inputElement.setCustomValidity('');
        } else {
            inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');
        }
    }
</script>
@endsection
