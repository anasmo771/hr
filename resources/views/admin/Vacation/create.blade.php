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
                                <li class="breadcrumb-item" aria-current="page">إضــافــة إجــازة للــمــوظــف</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="page-header-title">
                                <h4 class="mb-sm-0 font-size-18">
                                    إضــافــة إجــازة للــمــوظــف
                                    <a href="{{ route('EmployeeDetails', [$employee->id]) }}">
                                        <span style="color: blue;">{{ $employee->person->name }}</span>
                                    </a>
                                </h4>
                                <h4 class="mb-sm-0 font-size-18">
                                    رصــيــد المــوظــف
                                    <span style="color: blue;">{{ $vacationBalance }} يــوم</span>
                                </h4>
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

                            <h4 class="card-title">بـيـانـات الـمـوظـف</h4>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="fullname">اســم الــموظــف</label>
                                        <h4>{{ $employee->person->name }}</h4>
                                    </div>
                                </div>

                                @if ($employee->person->N_id)
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="email">الرقــم الوطــني</label>
                                            <h4>{{ $employee->person->N_id }}</h4>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="email">رقم الإقامة او الجواز لغير الليبين</label>
                                            <h4>{{ $employee->person->non_citizen_ref_no }}</h4>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">المؤهــل في القرار</label>
                                        <h4>{{ $employee->qualification }}</h4>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">التــخــصص</label>
                                        @if($employee->specialty)
                                            <h4>{{ $employee->specialty->name }}</h4>
                                        @else
                                            <h4> - </h4>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">تاريــخ الاستــحقاق</label>
                                        <h4>{{ $employee->due }}</h4>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">الدرجــة الحــالية</label>
                                        <h4>{{ $employee->degree }}</h4>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">تاريــخ المبــاشرة</label>
                                        <h4 id="earnDate">{{ $employee->start_date }}</h4>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">رقــم القــرار</label>
                                        <h4>{{ $employee->res_num }}</h4>
                                    </div>
                                </div>

                                <div class="col-sm-4 mb-3">
                                    <label class="control-label">الإدارة</label>
                                    <h4>
                                        @if($employee->subSection && optional($employee->subSection)->parent)
                                            {{ optional($employee->subSection->parent)->name }} - {{ $employee->subSection->name }}
                                        @elseif($employee->subSection)
                                            {{ $employee->subSection->name }}
                                        @else
                                            -
                                        @endif
                                    </h4>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">الـجـنـس</label>
                                        <h4>{{ $employee->person->gender }}</h4>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label for="email">الحالة الاجتماعية</label>
                                        <h4>{{ $employee->person->marital_status }}</h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">تـقـديـم إجـازة للـمـوظـف</h4>
                            @include('includes.messages')

                            <form enctype="multipart/form-data"
                                  action="{{ route('saveVacation', ['id' => $employee->id]) }}"
                                  onSubmit="return lastCheck()"
                                  method="post">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-12 pt-3">
                                        <h4 class="mb-sm-0 font-size-18" style="color: blue; text-align: center;" id="description"></h4>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="worktitle" class="col-form-label" style="font-size:100%;">نوع الإجازة</label>
                                        <select onchange="something()" style="width: 100%;" name="type" class="form-control" id="typeOf"
                                                lang="ar" required
                                                oninvalid="this.setCustomValidity('الرجاء تحديد نوع الاجازة')"
                                                oninput="this.setCustomValidity('')">
                                            <option selected disabled>الرجاء تحديد نوع الإجازة</option>

                                            @if ($vacationBalance - $employee->vacations()->sum('days') > 0)
                                                <option value="إجازة سنوية">إجازة سنوية</option>
                                            @endif

                                            <option value="إجازة مرضية">إجازة مرضية</option>

                                            @if (optional($employee->person)->gender === 'انثى' && $employee->status === 'يعمل')
                                                <option value="إجازة وضع">إجازة وضع</option>
                                            @endif

                                            @if ($employee->vacations()->where('type', 'إجازة زواج')->count() == 0 && $employee->status === 'يعمل')
                                                <option value="إجازة زواج">إجازة زواج</option>
                                            @endif

                                            @if ($employee->vacations()->where('type', 'إجازة حج')->count() == 0)
                                                <option value="إجازة حج">إجازة حج</option>
                                            @endif

                                            @if (optional($employee->person)->gender === 'انثى' && $employee->status === 'يعمل')
                                                <option value="إجازة وفاة الزوج">إجازة وفاة الزوج</option>
                                            @endif

                                            <option value="إجازة بدون مرتب">إجازة بدون مرتب</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4" style="display: none;" id="days">
                                        <label for="dayys" class="col-form-label col-lg-4" style="font-size:140%;">عــدد أيــام</label>
                                        <input id="dayys" name="days" type="number" min="0" onchange="setDate()" value="1"
                                               class="form-control" placeholder="عدد الايام">
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
                                               style="margin-top: 30px; width: 30px; height: 30px;">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="personInput2" class="col-form-label col-lg-4" style="font-size:100%;">الموافقة مسبقآ</label>
                                        <input id="personInput2" name="approve" onchange="checkVecation()" type="checkbox"
                                               style="margin-top: 30px; width: 30px; height: 30px;">
                                    </div>

                                    <div class="col-sm-4" style="display: none;" id="showFiles">
                                        <div class="mb-3">
                                            <label for="email">صـورة من الاجازة (اختياري)</label>
                                            <input name="files[]" type="file" class="form-control" multiple>
                                        </div>
                                    </div>

                                </div>

                                <div class="row justify-content-start">
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
        var days = {{ $vacationBalance }};
        document.getElementById('description').innerHTML = "ملاحظة الاجازة السنوية علي حسب رصيد إجازات الموظف";
        document.getElementById('startDate').value = "";
        document.getElementById('endDate').value = "";
        document.getElementById('description').style.color = "blue";
        document.getElementById('days').style.display = "block";
        document.getElementById('person').style.display = "none";
        document.getElementById('personInput').checked = false;
        document.getElementById('dayys').max = days; // تصحيح وضع الحد الأقصى على حقل الأيام
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
        document.getElementById('description').innerHTML = "ملاحظة إجازة  إجازة وفاة الزوج  تمنح 4 أشهر و 10 أيام فقط";
        document.getElementById('startDate').value = "";
        document.getElementById('endDate').value = "";
        document.getElementById('description').style.color = "none";
        document.getElementById('days').style.display = "none";
        document.getElementById('work_title').style.display = "none";
        document.getElementById('person').style.display = "none";
        document.getElementById('personInput').checked = false;
    } else if (sel.value == "إجازة بدون مرتب") {
        document.getElementById('description').innerHTML = " ملاحظة الاجازة  بدون مرتب تمنح سنة فقط لغير المرافق";
        document.getElementById('startDate').value = "";
        document.getElementById('endDate').value = "";
        document.getElementById('description').style.color = "blue";
        document.getElementById('days').style.display = "none";
        document.getElementById('person').style.display = "none";
    }
}

function setDate() {
    var days = document.getElementById('dayys');
    var maxDays = {{ $vacationBalance }};
    document.getElementById('startDate').value = "";
    document.getElementById('endDate').value = "";
    if (days.value == 0) {
        alert("عدد الايام علي الاقل 1");
        days.value = 1;
        return;
    }
    if (days.value > maxDays) {
        alert("لقد تجاوزت الحد الاقصي لايام الاجازة المتوفرة");
        days.value = maxDays;
        return;
    }
}

function callDate(inputElement) {
    var dateRegex = /^\d{1,2}-\d{1,2}-\d{4}$/; // DD-MM-YYYY
    var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // DD/MM/YYYY
    var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/; // YYYY-MM-DD
    var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // YYYY/MM/DD
    if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) || dateRegex3.test(inputElement.value) || dateRegex4.test(inputElement.value)) {
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
    var sel = document.getElementById('typeOf');
    var days, maxDays, endD;

    if (sel.value == "إجازة سنوية") {
        days = document.getElementById('dayys');
        maxDays = {{ $vacationBalance }};

        if (days.value < 1) {
            alert("عدد الايام علي الاقل 1");
            days.value = 1;
        } else if (days.value > maxDays) {
            alert("لقد تجاوزت الحد الاقصي لايام الاجازة المتوفرة");
            days.value = maxDays;
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
    var dateRegex = /^\d{1,2}-\d{1,2}-\d{4}$/; // DD-MM-YYYY
    var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // DD/MM/YYYY
    var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/; // YYYY-MM-DD
    var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // YYYY/MM/DD
    if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) || dateRegex3.test(inputElement.value) || dateRegex4.test(inputElement.value)) {
        inputElement.setCustomValidity('');
    } else {
        inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');
    }
}
</script>
@endsection
