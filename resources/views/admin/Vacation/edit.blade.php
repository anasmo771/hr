@extends('admin.layout.master')

@section('title')
  <title>تعديل إجازة: {{ $employee->person->name ?? '—' }}</title>
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
              <li class="breadcrumb-item"><a href="{{ route('vacations.show', [$vac->emp_id]) }}">إجازات الموظف</a></li>
              <li class="breadcrumb-item" aria-current="page">تعديل إجازة</li>
            </ul>
          </div>
          <div class="col-md-12 d-flex align-items-center justify-content-between">
            <h2 class="mb-0">تعديل إجازة: {{ $employee->person->name ?? '—' }}</h2>
            <div class="text-muted">الرصيد الحالي: {{ $vacationBalance ?? 0 }} يوم</div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <div class="row">
      <div class="col-lg-10 mx-auto">
        <div class="card">
          <div class="card-body">
            @php
              // أنواع الإجازات الاحتياطية إذا لم تُمرر من الكنترولر
              $types = $types ?? [
                'إجازة سنوية',
                'إجازة مرضية',
                'إجازة وضع',
                'إجازة زواج',
                'إجازة حج',
                'إجازة وفاة الزوج',
                'إجازة بدون مرتب',
              ];
              $start = \Carbon\Carbon::parse($vac->start_date)->format('Y-m-d');
              $end   = \Carbon\Carbon::parse($vac->end_date)->format('Y-m-d');
            @endphp

            <form action="{{ route('vacations.update.form', [$vac->id]) }}" method="POST" novalidate>
              @csrf
              @method('PUT')

              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">نوع الإجازة</label>
                  <select name="type" class="form-control" required>
                    @foreach($types as $t)
                      <option value="{{ $t }}" {{ old('type', $vac->type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label">تاريخ البداية</label>
                  <input id="startDate" type="date" name="start_date"
                         value="{{ old('start_date', $start) }}" class="form-control" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label">تاريخ المباشرة</label>
                  <input id="endDate" type="date" name="end_date"
                         value="{{ old('end_date', $end) }}" class="form-control" required>
                </div>

                <div class="col-md-8">
                  <label class="form-label">سبب الإجازة (اختياري)</label>
                  <input type="text" name="reason" value="{{ old('reason', $vac->reason) }}" class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="person" id="person" value="1"
                           {{ old('person', $vac->companion ? 1 : 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="person">مرافق</label>
                  </div>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                  <a href="{{ route('vacations.show', [$vac->emp_id]) }}" class="btn btn-secondary">رجوع</a>
                </div>
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
<script>
  // اضبط أقل تاريخ للمباشرة ليكون >= تاريخ البداية
  (function () {
    var s = document.getElementById('startDate');
    var e = document.getElementById('endDate');
    if (s && e) {
      function syncMin(){ if(s.value){ e.min = s.value; } }
      s.addEventListener('change', syncMin);
      syncMin();
    }
  })();
</script>
@endsection
