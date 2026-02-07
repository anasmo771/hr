@extends('admin.layout.master')

@section('title')
<title>عـقـوبـة تعديل</title>
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
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">بـيـانـات الـمـوظـف</h4>

            <div class="row">
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>اســم الــموظــف</label>
                  <h4>{{ $emp->person->name }}</h4>
                </div>
              </div>

              @if ($emp->person->N_id)
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>الرقــم الوطــني</label>
                    <h4>{{ $emp->person->N_id }}</h4>
                  </div>
                </div>
              @else
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>رقم الإقامة او الجواز لغير الليبين</label>
                    <h4>{{ $emp->person->non_citizen_ref_no }}</h4>
                  </div>
                </div>
              @endif

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>المؤهــل في القرار</label>
                  <h4>{{ $emp->qualification }}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>التــخــصص</label>
                  @if($emp->specialty)
                    <h4>{{ $emp->specialty->name }}</h4>
                  @else
                    <h4>-</h4>
                  @endif
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>تاريــخ الاستــحقاق</label>
                  <h4>{{ $emp->due }}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>الدرجــة الحــالية</label>
                  <h4>{{ $emp->degree }}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>تاريــخ المبــاشرة</label>
                  <h4 id="earnDate">{{ $emp->start_date }}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>رقــم القــرار</label>
                  <h4>{{ $emp->res_num }}</h4>
                </div>
              </div>

              <div class="col-sm-4 mb-3">
                <label class="control-label">الإدارة</label>
                @if($emp->subSection)
                  <h4>{{ $emp->section->name }} - {{ $emp->subSection->name }}</h4>
                @else
                  <h4>{{ $emp->section->name }}</h4>
                @endif
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>الـجـنـس</label>
                  <h4>{{ $emp->person->gender }}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>الحالة الاجتماعية</label>
                  <h4>{{ $emp->person->marital_status }}</h4>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">تعديل عـقـوبـة</h4>

            {{-- استخدم معرّف العقوبة هنا، مش معرّف الموظف --}}
            <form action="{{ route('punishments.update', [$punshe->id]) }}" method="post" enctype="multipart/form-data" id="form">
              @csrf
              {{ method_field('PATCH') }}

              <div class="row">

                <div class="col-12 p-3">
                  <h4 class="mb-sm-0 font-size-18" style="color: blue; text-align: center;" id="descriptionNote"></h4>
                </div>

                @php
                  $allowedTypes = [
                    'لفت نظر',
                    'الإنذار',
                    'اللوم',
                    'الخصم من المرتب',
                    'الحرمان من العلاوات',
                    'خفض الدرجة',
                    'العزل من الخدمة',
                    'بلا عقوبة',
                  ];
                  $current = in_array($punshe->pun_type, $allowedTypes, true) ? $punshe->pun_type : null;
                @endphp

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>نوع العقوبة</label>
                    <select name="pun_type" id="typeOf" onchange="showTypes()" class="form-control" required
                            oninvalid="this.setCustomValidity('الرجاء اختيار نوع العقوبة')"
                            oninput="this.setCustomValidity('')">
                      <option value="لفت نظر" {{ $current === 'لفت نظر' ? 'selected' : '' }}>لفت نظر</option>

                      {{-- الإنذار متاح فقط لدرجة <= 10 --}}
                      @if((int)($emp->degree ?? 0) <= 10)
                        <option value="الإنذار" {{ $current === 'الإنذار' ? 'selected' : '' }}>الإنذار</option>
                      @endif

                      <option value="اللوم" {{ $current === 'اللوم' ? 'selected' : '' }}>اللوم</option>
                      <option value="الخصم من المرتب" {{ $current === 'الخصم من المرتب' ? 'selected' : '' }}>الخصم من المرتب</option>
                      <option value="الحرمان من العلاوات" {{ $current === 'الحرمان من العلاوات' ? 'selected' : '' }}>الحرمان من العلاوات</option>
                      <option value="خفض الدرجة" {{ $current === 'خفض الدرجة' ? 'selected' : '' }}>خفض الدرجة</option>
                      <option value="العزل من الخدمة" {{ $current === 'العزل من الخدمة' ? 'selected' : '' }}>العزل من الخدمة</option>
                      <option value="بلا عقوبة" {{ $current === 'بلا عقوبة' ? 'selected' : '' }}>بلا عقوبة</option>

                      {{-- محذوف: إيقاف مؤقت / فصل --}}
                    </select>
                    <span id="Name_text"></span>

                    @if(!$current)
                      <small class="text-danger d-block mt-1">
                        نوع العقوبة الحالي "{{ $punshe->pun_type }}" غير مسموح به الآن. الرجاء اختيار نوع معتمد.
                      </small>
                    @endif
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="reason">سـبـب الـعـقـوبـة</label>
                    <input id="reason" name="reason" value="{{ $punshe->reason }}" type="text" class="form-control"
                           placeholder="سبب العقوبة" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال سبب العقوبة')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>تاريــخ العقوبة</label>
                    <input name="pun_date" value="{{ $punshe->pun_date }}" type="date"
                           placeholder="YYYY-MM-DD" class="form-control" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ العقوبة')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>رقم الكتاب</label>
                    <input name="book_num" value="{{ $punshe->book_num }}" type="text" class="form-control"
                           placeholder="رقم الكتاب" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال رقم الكتاب')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>الرقم الإشاري</label>
                    <input name="index" type="text" value="{{ $punshe->index }}" class="form-control"
                           placeholder="الرقم الإشاري" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الإشاري')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>اسم من اوصي بالعقوبة</label>
                    <input name="penaltyName" type="text" value="{{ $punshe->penaltyName }}" class="form-control"
                           placeholder="اسم من اوصي بالعقوبة" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال اسم من اوصي بالعقوبة')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3 ">
                    <label>وثـيـقـة (اختياري)</label>
                    <input name="files[]" type="file" class="form-control" multiple>
                  </div>
                </div>

                <div class="col-sm-4">
                  <label for="description" class="col-form-label" style="font-size:140%;">ملاحـظـات</label>
                  <textarea class="form-control" id="description" name="notes" rows="3" placeholder="ملاحظات...">{{ $punshe->notes }}</textarea>
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

  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function showTypes() {
  var sel = document.getElementById('typeOf');
  var empDegree = {{ (int)($emp->degree ?? 0) }};

  if (sel.value === "الإنذار" && empDegree > 10) {
    // أمان إضافي في الواجهة — ولو السيرفر أصلاً يمنع
    sel.value = "لفت نظر";
  }

  if (sel.value === "الإنذار") {
    document.getElementById('descriptionNote').innerHTML =
      "الإنذار من العقوبات التأديبية التي يجوز توقيعها على الموظفين الذين يشغلون وظائف الدرجة العاشرة فأقل.";
  } else if (sel.value === "الخصم من المرتب") {
    if (empDegree <= 10) {
      document.getElementById('descriptionNote').innerHTML =
        "الخصم من المرتب بما لا يجاوز ستين يوماً في السنة ولا يجوز أن يجاوز الخصم تنفيذاً لهذه العقوبة ربع المرتب شهرياً بعد الربع الجائز الحجز عليه أو التنازل عنه قانوناً.";
    } else {
      document.getElementById('descriptionNote').innerHTML =
        "الخصم من المرتب بما لا يجاوز تسعين يوماً في السنة ولا يجوز أن يجاوز الخصم تنفيذاً لهذه العقوبة ربع المرتب شهرياً بعد الربع الجائز الحجز عليه أو التنازل عنه قانوناً.";
    }
  } else if (sel.value === "الحرمان من العلاوات") {
    document.getElementById('descriptionNote').innerHTML =
      "الحرمان من الترقية مدة لا تقل عن سنة ولا تزيد عن ثلاث سنوات.";
  } else {
    document.getElementById('descriptionNote').innerHTML = "";
  }
}

// تشغيل أولي
document.addEventListener('DOMContentLoaded', showTypes);
</script>
@endsection
