@extends('admin.layout.master')

@section('title')
<title>عـقـوبـة جـديـدة</title>
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
              <li class="breadcrumb-item" aria-current="page">العـقـوبـات</li>
            </ul>
          </div>
          <div class="col-6">
            <div class="page-header-title">
              <h2 class="mb-0">إضــافـة عـقـوبـة لـلـمـوظــف</h2>
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
            <h4 class="card-title">تـقـديـم عـقـوبـة جـديـدة</h4>

            <form action="{{ route('punishments.store', ['id' => 0]) }}" method="post" enctype="multipart/form-data" id="form">
              @csrf
              <div class="row">

                <div class="col-12 p-3">
                  <h4 class="mb-sm-0 font-size-18" style="color: blue; text-align: center;" id="descriptionNote"></h4>
                </div>

                <div class="col-sm-4">
                  <label class="col-form-label col-lg-4" style="font-size:100%;">الـمـوظــف
                    <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                  </label>
                  <select name="emp_id" class="form-control js-example-basic-single" lang="ar" required
                          oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                          oninput="this.setCustomValidity('')"
                          id="empSelect">
                    @foreach ($employees as $e)
                      <option value="{{ $e->id }}" data-degree="{{ (int)($e->degree ?? 0) }}">
                        {{ $e->person->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-sm-4 mt-4">
                  <div class="mb-3">
                    <label>نوع العقوبة</label>
                    <select name="pun_type" id="typeOf" onchange="showTypes()" class="form-control" required
                            oninvalid="this.setCustomValidity('الرجاء اختيار نوع العقوبة')"
                            oninput="this.setCustomValidity('')">
                      <option value="لفت نظر" selected>لفت نظر</option>
                      <option value="الإنذار" id="warnOption">الإنذار</option>
                      <option value="اللوم">اللوم</option>
                      <option value="الخصم من المرتب">الخصم من المرتب</option>
                      <option value="الحرمان من العلاوات">الحرمان من العلاوات</option>
                      <option value="خفض الدرجة">خفض الدرجة</option>
                      <option value="العزل من الخدمة">العزل من الخدمة</option>
                      <option value="بلا عقوبة">بلا عقوبة</option>
                      {{-- محذوف: إيقاف مؤقت / فصل --}}
                    </select>
                    <span id="Name_text"></span>
                  </div>
                </div>

                <div class="col-sm-4 mt-4">
                  <div class="mb-3">
                    <label for="reason">سـبـب الـعـقـوبـة</label>
                    <input id="reason" name="reason" type="text" class="form-control" placeholder="سبب العقوبة" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال سبب العقوبة')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label class="col-form-label col-lg-4" style="font-size:100%;">تاريــخ العقوبة</label>
                    <input name="pun_date" type="date" placeholder="YYYY-MM-DD" class="form-control" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ العقوبة')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label class="col-form-label col-lg-4" style="font-size:100%;">رقم الكتاب</label>
                    <input name="book_num" type="text" class="form-control" placeholder="رقم الكتاب" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال رقم الكتاب')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label class="col-form-label col-lg-4" style="font-size:100%;">الرقم الإشاري</label>
                    <input name="index" type="text" class="form-control" placeholder="الرقم الإشاري" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الإشاري')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label class="col-form-label col-lg-4" style="font-size:100%;">من اوصي بالعقوبة</label>
                    <input name="penaltyName" type="text" class="form-control" placeholder="من اوصي بالعقوبة" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال اسم من اوصي بالعقوبة')">
                  </div>
                </div>

                <div class="col-sm-4 mt-4">
                  <div class="mb-3 ">
                    <label>وثـيـقـة (اختياري)</label>
                    <input name="files[]" type="file" class="form-control" multiple>
                  </div>
                </div>

                <div class="col-sm-4">
                  <label class="col-form-label" style="font-size:100%;">ملاحـظـات</label>
                  <textarea class="form-control" id="description" name="notes" rows="3" placeholder="ملاحظات"></textarea>
                </div>

              </div>

              <br>

              <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary waves-effect waves-light">حـفـظ</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
    <!-- end row -->
  </div>
</div>
<!-- End Page-content -->
@endsection

@section('script')
<script type="text/javascript">
function getSelectedEmpDegree() {
  var sel = document.getElementById('empSelect');
  var opt = sel.options[sel.selectedIndex];
  return parseInt(opt?.dataset?.degree || '0', 10);
}

function showTypes() {
  var selType = document.getElementById('typeOf');
  var empDegree = getSelectedEmpDegree();

  // تحكم في "الإنذار" حسب الدرجة (تعطيل لمن درجته > 10)
  var warnOption = document.getElementById('warnOption');
  if (warnOption) {
    warnOption.disabled = empDegree > 10;
    if (empDegree > 10 && selType.value === 'الإنذار') {
      selType.value = 'لفت نظر';
    }
  }

  // ملاحظات توضيحية
  if (selType.value === 'الإنذار') {
    document.getElementById('descriptionNote').innerHTML =
      "الإنذار من العقوبات التأديبية التي يجوز توقيعها على الموظفين الذين يشغلون وظائف الدرجة العاشرة فأقل.";
  } else if (selType.value === 'الخصم من المرتب') {
    if (empDegree <= 10) {
      document.getElementById('descriptionNote').innerHTML =
        "الخصم من المرتب بما لا يجاوز ستين يوماً في السنة، ولا يجوز أن يجاوز الخصم ربع المرتب شهرياً بعد الربع الجائز الحجز عليه أو التنازل عنه قانوناً.";
    } else {
      document.getElementById('descriptionNote').innerHTML =
        "الخصم من المرتب بما لا يجاوز تسعين يوماً في السنة، ولا يجوز أن يجاوز الخصم ربع المرتب شهرياً بعد الربع الجائز الحجز عليه أو التنازل عنه قانوناً.";
    }
  } else if (selType.value === 'الحرمان من العلاوات') {
    document.getElementById('descriptionNote').innerHTML =
      "الحرمان من الترقية مدة لا تقل عن سنة ولا تزيد عن ثلاث سنوات.";
  } else {
    document.getElementById('descriptionNote').innerHTML = "";
  }
}

// اربط تغيّر الموظف بتحديث خيارات العقوبة/الوصف
document.addEventListener('DOMContentLoaded', function () {
  var empSel = document.getElementById('empSelect');
  empSel.addEventListener('change', showTypes);
  showTypes(); // أولي
});


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
