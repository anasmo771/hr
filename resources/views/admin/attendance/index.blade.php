@extends('admin.layout.master')

@section('title')
    <title>الحضور والانصراف</title>
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
              <li class="breadcrumb-item" aria-current="page">الحضور والانصراف</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">تسجيل الحضور والانصراف</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Date Card ] -->
    <div class="row justify-content-center g-3 mb-3">
      <div class="col-xl-8 col-lg-9">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">تاريخ اليوم</h5>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center">
              <i class="fas fa-calendar-day f-32 me-2 text-primary"></i>
              <div class="flex-grow-1">
                <h4 class="mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}</h4>
                <small class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- [ Main Form ] -->
    <div class="row justify-content-center">
      <div class="col-xl-8 col-lg-9">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">تسجيل اليوم</h5>
            <div class="d-flex gap-2">
              <a href="{{ route('attendance.monthly.report') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-table"></i> التقرير الشهري
              </a>
              <a href="{{ route('attendance.report.form') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-file-alt"></i> تقرير موظف
              </a>
            </div>
          </div>
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form id="attendance-form" action="#" method="POST" class="row g-3">
              @csrf

              <div class="col-12">
                <label for="employee_id" class="form-label">الموظف</label>
                <select name="employee_id" id="employee_id" class="form-select" required>
                  <option value="" disabled selected>-- اختر موظف --</option>
                  @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                      {{ $employee->person->name ?? 'بدون اسم' }}
                    </option>
                  @endforeach
                </select>
                @error('employee_id')<small class="text-danger">{{ $message }}</small>@enderror
              </div>

              <div class="col-12 d-flex gap-2 flex-wrap">
                <button type="button" id="check-in-btn" class="btn btn-success">
                  <i class="ti ti-login"></i> تسجيل حضور
                </button>
                <button type="button" id="check-out-btn" class="btn btn-danger">
                  <i class="ti ti-logout"></i> تسجيل انصراف
                </button>
              </div>

              <div class="col-12">
                <div class="alert alert-warning mb-0">
                  <i class="fas fa-info-circle"></i>
                  يُسمح بتسجيل <strong>الحضور</strong> بين <strong>07:30 — 10:30</strong>
                  (7:30–8:15 = حاضر، 8:15–10:30 = متأخر)
                  و<strong>الانصراف</strong> بين <strong>08:00 — 14:30</strong>
                  (08:00–10:30 = انصراف في النصف الأول، 10:31–13:59 = انصراف في النصف الثاني).
                  (الجمعة/السبت عطلة)
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- (اختياري) بطاقة إرشادات قصيرة -->
        <div class="card mt-3">
          <div class="card-body">
            <h6 class="text-muted mb-2">إرشادات</h6>
            <ul class="mb-0">
              <li>لا يمكن التسجيل في العطلة الأسبوعية (الجمعة والسبت).</li>
              <li>إذا تم تسجيل الحضور مرة لليوم، لن يُسمح بتسجيله مرة أخرى.</li>
              <li>الغياب يُحتسب تلقائيًا في التقرير الشهري عند عدم وجود حضور في يوم عمل.</li>
            </ul>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // تفعيل Select2 إن كان متاحًا في المشروع
    if (window.$ && $.fn.select2) {
      $('#employee_id').select2({
        placeholder: "-- اختر موظف --",
        width: '100%',
        dir: "rtl"
      });
    }

    const form = document.getElementById('attendance-form');
    const employeeSelect = document.getElementById('employee_id');

    function ensureEmployeeSelected() {
      if (!employeeSelect.value) {
        alert('الرجاء اختيار الموظف أولاً.');
        return false;
      }
      return true;
    }

    document.getElementById('check-in-btn').addEventListener('click', function () {
      if (!ensureEmployeeSelected()) return;
      form.action = "{{ route('attendance.checkin') }}";
      form.method = "POST";
      form.submit();
    });

    document.getElementById('check-out-btn').addEventListener('click', function () {
      if (!ensureEmployeeSelected()) return;
      form.action = "{{ route('attendance.checkout') }}";
      form.method = "POST";
      form.submit();
    });
  });
</script>
@endsection
