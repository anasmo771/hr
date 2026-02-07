@extends('admin.layout.master')

@section('title')
  <title>تعديل وحدة إدارية</title>
@endsection

@section('content')
<div class="pc-container" dir="rtl">
  <div class="pc-content">
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item"><a href="{{ route('subSection.index') }}">الهيكل الإداري</a></li>
              <li class="breadcrumb-item" aria-current="page">تعديل وحدة</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title"><h2 class="mb-0">تعديل وحدة إدارية</h2></div>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    <div class="card">
      <div class="card-body">
        <form method="POST" action="{{ route('subSection.update', $sub->id) }}" enctype="multipart/form-data">
          @csrf @method('PUT')

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">اسم الوحدة</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $sub->name) }}" required>
              @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">تغيير الشعار (اختياري)</label>
              <input type="file" name="logo" class="form-control">
              @if(!empty($sub->logo))
                <div class="form-text mt-1">الحالي: <a href="{{ asset('storage/'.$sub->logo) }}" target="_blank">عرض</a></div>
              @endif
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('subSection.index') }}" class="btn btn-light">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection