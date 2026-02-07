@extends('admin.layout.master')

@section('title')
    <title>تقرير الغياب لموظف</title>
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
              <li class="breadcrumb-item" aria-current="page">تقرير الغياب لموظف</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">تقرير الغياب لموظف</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card glass-card">
            <div>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#printModal">
                  <i class="fas fa-print"></i> طباعة تقرير سنوي
                </button>
              </div>
          <div class="card-body p-4">
            {{-- نموذج التقرير --}}
            <form id="absence-report-form" action="{{ route('attendance.report.generate') }}" method="GET" class="row g-3 align-items-end">
              <div class="col-md-6">
                <label for="employee_id" class="form-label">الموظف</label>
                <select name="employee_id" id="employee_id" class="form-select" required>
                  <option value="">-- اختر موظف --</option>
                  @foreach($employees as $employee)
                    <option value="{{ $employee->id }}"
                      {{ ( (int)request('employee_id') === (int)$employee->id ) ? 'selected' : '' }}>
                      {{ $employee->person->name ?? 'بدون اسم' }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-4">
                <label for="month" class="form-label">الشهر</label>
                <input type="month" name="month" id="month" class="form-control"
                       value="{{ request('month') ?? now()->format('Y-m') }}" required>
              </div>

              <div class="col-md-2 d-grid">
                <button class="btn btn-primary">عرض</button>
              </div>



            </form>
          </div>
        </div>

        {{-- عرض النتائج --}}
        @isset($reportData)
          <div class="card mt-3">
            <div class="card-body">
              <h5 class="mb-3">الموظف: {{ $reportData['employee']->person->name ?? 'بدون اسم' }}</h5>
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="p-3 border rounded bg-light-success">
                    <h6 class="text-muted">الشهر</h6>
                    <h3 class="mb-0">{{ $reportData['month'] }}</h3>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="p-3 border rounded bg-light-primary">
                    <h6 class="text-muted">أيام العمل</h6>
                    <h3 class="mb-0">{{ $reportData['workingDays'] }}</h3>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="p-3 border rounded bg-light-warning">
                    <h6 class="text-muted">أيام الحضور</h6>
                    <h3 class="mb-0">{{ $reportData['presentDays'] }}</h3>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="p-3 border rounded bg-light-danger">
                    <h6 class="text-muted">أيام الغياب</h6>
                    <h3 class="mb-0 text-danger">{{ $reportData['absenceDays'] }}</h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endisset
      </div>
    </div>
  </div>
</div>

<!-- Modal لاختيار السنة للطباعة -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      {{-- اجعل action فارغ وسيتم ملؤه بجافاسكريبت --}}
      <form method="GET" action="" target="_blank" id="printAnnualForm">
        <div class="modal-header">
          <h5 class="modal-title" id="printModalLabel">طباعة تقرير غياب سنوي</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
          {{-- نرسل employee_id أيضًا كـ query لدالتك الحالية (اختياري) --}}
          <input type="hidden" name="employee_id" id="print_employee_id" value="">
          <div class="mb-3">
            <label class="form-label">اختر السنة</label>
            <select name="year" id="print_year" class="form-select" required>
              @for($y = date('Y'); $y >= date('Y') - 10; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
              @endfor
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">طباعة</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        </div>
      </form>
    </div>
  </div>
</div>


<style>
  .glass-card {
    background: rgba(255,255,255,0.6);
    border: 1px solid rgba(0,0,0,0.06);
    backdrop-filter: blur(6px);
  }
</style>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Select2 (إن وُجد)
  if (window.$ && $.fn.select2) {
    $('#employee_id').select2({ placeholder: "-- اختر موظف --", width: '100%', dir: "rtl" });
  }

  // تعبئة month الافتراضي ومنطق الإرسال التلقائي (كما عندك)
  const hasEmployeeFromQuery = '{{ request()->filled("employee_id") ? "1" : "0" }}' === '1';
  const hasReportData = '{{ isset($reportData) ? "1" : "0" }}' === '1';
  if (hasEmployeeFromQuery && !hasReportData) {
    const monthInput = document.getElementById('month');
    if (!monthInput.value) {
      const now = new Date();
      monthInput.value = now.toISOString().slice(0,7); // YYYY-MM
    }
    if (window.$ && $.fn.select2) $('#employee_id').trigger('change');
    document.getElementById('absence-report-form').submit();
  }

  // تجهيز فورم الطباعة السنوي لملء {employee} داخل الـ URI
  const printForm   = document.getElementById('printAnnualForm');
  const selectEmp   = document.getElementById('employee_id');
  const hiddenEmp   = document.getElementById('print_employee_id');

  // عند فتح المودال، خزّن القيمة المختارة
  document.getElementById('printModal').addEventListener('show.bs.modal', function () {
    hiddenEmp.value = selectEmp?.value || '';
  });

  // قبل الإرسال: ابنِ الـ action بـ {employee} وتحقق من الاختيار
  printForm.addEventListener('submit', function (e) {
    const emp = selectEmp?.value;
    if (!emp) {
      e.preventDefault();
      alert('الرجاء اختيار موظف أولاً');
      return;
    }
    hiddenEmp.value = emp; // لإرسالها كـ query أيضًا (اختياري)

    // ابنِ مسار الطباعة السنوي: /attendance/absence-report/{employee}
    // استخدم url() لضمان البادئة الصحيحة للتطبيق
    printForm.action = `{{ url('/attendance/absence-report') }}/${emp}`;
  });
});
</script>
@endsection

