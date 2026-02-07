@extends('admin.layout.master')

@section('title')
  <title>إضافة ترقية</title>
@endsection

@section('css')
<style>
  /* توحيد شكل select2 مع form-control */
  .select2-container { width: 100% !important; }
  .select2-container--default .select2-selection--single{
    height: calc(2.5rem + 2px);
    border: 1px solid #ced4da;
    border-radius: .5rem;
    display: flex;
    align-items: center;
    padding-inline-start: .75rem;
    padding-inline-end: 2rem;
    background-color: #fff;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 1.6;
    padding: 0;
    color: #212529;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow{
    height: 100%;
    inset-inline-end: .75rem;
  }
  /* إخفاء زر الإزالة (×) للحفاظ على شكل الحقول */
  .select2-selection__clear{ display: none !important; }
</style>
@endsection

@section('content')
<div class="pc-container">
  <div class="pc-content">
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item">الترقيات</li>
              <li class="breadcrumb-item active">إضافة</li>
            </ul>
          </div>
          <div class="col-md-12">
            <h2 class="mb-0">إضافة ترقية</h2>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    <div class="card">
      <div class="card-body">
        <form action="{{ route('promotion.store') }}" method="POST">
          @csrf
          <div class="row g-3">

            <!-- الموظف (select2) -->
            <div class="col-lg-4">
              <label class="form-label">الموظف</label>
              <select name="emp_id" class="form-select" required>
                @if (empty($employee))
                  <option value="" disabled selected>اختر الموظف</option>
                @endif
                @foreach($employees as $e)
                  <option value="{{ $e->id }}" {{ optional($employee)->id == $e->id ? 'selected' : '' }}>
                    {{ $e->person->name ?? '#'.$e->id }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- رقم القرار -->
            <div class="col-lg-4">
              <label class="form-label">رقم القرار</label>
              <input type="text" name="num" class="form-control" placeholder="اختياري">
            </div>

            <!-- نوع الترقية -->
            <div class="col-lg-4">
              <label class="form-label">نوع الترقية</label>
              <select name="type" class="form-select" required>
                <option disabled selected>اختر النوع</option>
                <option value="regular">نظامية</option>
                <option value="exceptional">استثنائية</option>
                <option value="acting">ندب على درجة</option>
              </select>
            </div>

            <!-- تاريخ منح الترقية -->
            <div class="col-lg-4">
              <label class="form-label">تاريخ منح الترقية</label>
              <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}">
            </div>

            <!-- الدرجة السابقة -->
            <div class="col-lg-4">
              <label class="form-label">الدرجة السابقة</label>
              <input type="number" name="prev_degree" class="form-control" required>
              @isset($bonusesCount)
                <small class="text-muted d-block mt-1">عدد العلاوات الحالية: {{ $bonusesCount }}</small>
              @endisset
            </div>

            <!-- الدرجة الجديدة -->
            <div class="col-lg-4">
              <label class="form-label">الدرجة الجديدة</label>
              <input type="number" name="new_degree" class="form-control" required>
              <small class="text-muted d-block mt-1">* بعد تنفيذ الشروط (مثلًا +1 للترقية النظامية/الاستثنائية)</small>
            </div>

          </div>

          <div class="mt-4">
            <button class="btn btn-primary">حفظ</button>
            <a href="{{ route('promotions.index') }}" class="btn btn-light">رجوع</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script>
  // نضمن التهيئة بنفس الستايل ودون زر الإزالة
  (function(){
    try {
      if ($('.js-emp-select').data('select2')) {
        $('.js-emp-select').select2('destroy');
      }
    } catch(e){}
    $('.js-emp-select').select2({
      dir: "rtl",
      width: '100%',
      placeholder: 'اختر الموظف',
      allowClear: false
    });
  })();
</script>
@endsection
