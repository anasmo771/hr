@extends('admin.layout.master')

@section('title')
  <title>إضافة وحدة إدارية</title>
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
              <li class="breadcrumb-item" aria-current="page">إضافة وحدة</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title"><h2 class="mb-0">إضافة وحدة إدارية</h2></div>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header"><h5 class="mb-0">إنشاء جهة جذرية</h5></div>
          <div class="card-body">
            <form method="POST" action="{{ route('subSection.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label class="form-label">اسم الجهة</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label">شعار (اختياري)</label>
                <input type="file" name="logo" class="form-control">
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('subSection.index') }}" class="btn btn-light">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-header"><h5 class="mb-0">إضافة قسم تابع لجهة</h5></div>
          <div class="card-body">
            <form method="POST" action="{{ route('subSectionStore') }}">
              @csrf
              <div class="mb-3">
                <label class="form-label">الجهة الأم</label>
                <select class="form-select" name="section_id" required>
                  <option value="" selected disabled>اختر جهة</option>
                  @foreach($sections as $sec)
                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                  @endforeach
                </select>
                @error('section_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label">اسم القسم</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('subSection.index') }}" class="btn btn-light">إلغاء</a>
                <button type="submit" class="btn btn-primary">حفظ</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection