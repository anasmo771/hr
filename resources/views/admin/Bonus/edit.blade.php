@extends('admin.layout.master')

@section('title')
  <title>تعديل علاوة</title>
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
              <li class="breadcrumb-item"><a href="{{ route('bonuses.index') }}">العلاوات</a></li>
              <li class="breadcrumb-item" aria-current="page">تعديل علاوة</li>
            </ul>
          </div>
          <div class="col-12">
            <h2 class="mb-0">
              تعديل علاوة — {{ optional(optional($bonus->emp)->person)->name ?? ('#'.$bonus->emp_id) }}
            </h2>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('bouns.update', $bonus->id) }}">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">رقم العلاوة</label>
              <input type="number" name="bonus_num" min="1"
                     class="form-control"
                     value="{{ old('bonus_num', $bonus->bonus_num) }}" required>
            </div>

            <div class="col-md-3">
              <label class="form-label">تاريخ الاستحقاق / العلاوة (date)</label>
              <input type="date" name="date" class="form-control"
                     value="{{ old('date', optional($bonus->date)->format('Y-m-d') ?? (is_string($bonus->date) ? \Illuminate\Support\Str::of($bonus->date)->limit(10, '') : '') ) }}"
                     required>
            </div>

            <div class="col-md-3">
              <label class="form-label">تاريخ القرار (bonus_date)</label>
              <input type="date" name="bonus_date" class="form-control"
                     value="{{ old('bonus_date', optional($bonus->bonus_date)->format('Y-m-d') ?? (is_string($bonus->bonus_date) ? \Illuminate\Support\Str::of($bonus->bonus_date)->limit(10, '') : '') ) }}">
            </div>

            <div class="col-md-3">
              <label class="form-label">الدرجة الوظيفية</label>
              <input type="number" name="degree" class="form-control"
                     value="{{ old('degree', $bonus->degree) }}">
            </div>

            <div class="col-md-6">
              <label class="form-label">التقدير (من تقرير الكفاية)</label>
              <input type="text" name="estimate" class="form-control"
                     value="{{ old('estimate', $bonus->estimate) }}" placeholder="مثال: ممتاز / جيد جدًا / جيد ...">
            </div>

            <div class="col-md-3 d-flex align-items-end">
              <div class="form-check">
                {{-- نضمن إرسال 0 عند عدم التحديد --}}
                <input type="hidden" name="accept" value="0">
                <input class="form-check-input" type="checkbox" name="accept" id="accept" value="1"
                  {{ (string)old('accept', (int)$bonus->accept) === '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="accept">معتمد</label>
              </div>
            </div>

            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-primary">حفظ</button>
              <a href="{{ route('bouns.show', $bonus->emp_id) }}" class="btn btn-light">رجوع</a>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
