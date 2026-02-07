@extends('admin.layout.master')

@section('title')
  <title>تعديل ترقية</title>
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
              <li class="breadcrumb-item active">تعديل</li>
            </ul>
          </div>
          <div class="col-md-12">
            <h2 class="mb-0">تعديل ترقية: {{ $promotion->emp->person->name ?? '#'.$promotion->emp_id }}</h2>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    <div class="card">
      <div class="card-body">
        <form action="{{ route('promotions.update', $promotion->id) }}" method="POST">
          @csrf @method('PUT')
          <div class="row g-3">

            <div class="col-lg-4">
              <label class="form-label">رقم القرار</label>
              <input type="text" name="num" class="form-control" value="{{ old('num',$promotion->num) }}">
            </div>

            <div class="col-lg-4">
              <label class="form-label">نوع الترقية</label>
              <select name="type" class="form-control" required>
                <option value="regular" @selected($promotion->type==='regular')>نظامية</option>
                <option value="exceptional" @selected($promotion->type==='exceptional')>استثنائية</option>
                <option value="acting" @selected($promotion->type==='acting')>ندب على درجة</option>
              </select>
            </div>

            <div class="col-lg-4">
              <label class="form-label">تاريخ منح الترقية</label>
              <input type="date" name="date" class="form-control" value="{{ old('date', optional($promotion->date)->format('Y-m-d')) }}">
            </div>

            <div class="col-lg-6">
              <label class="form-label">الدرجة السابقة</label>
              <input type="number" name="prev_degree" class="form-control" value="{{ old('prev_degree',$promotion->prev_degree) }}" required>
            </div>

            <div class="col-lg-6">
              <label class="form-label">الدرجة الجديدة</label>
              <input type="number" name="new_degree" class="form-control" value="{{ old('new_degree',$promotion->new_degree) }}" required>
            </div>

            @if(!empty($promotion->consumed_bonus_ids))
              <div class="col-12">
                <label class="form-label">العلاوات المستهلكة</label>
                <div class="form-control bg-light">
                  {{ implode(', ', $promotion->consumed_bonus_ids) }}
                </div>
              </div>
            @endif

          </div>

          <div class="mt-4">
            <button class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('promotions.index') }}" class="btn btn-light">رجوع</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
