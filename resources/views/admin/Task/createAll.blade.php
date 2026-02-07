@extends('admin.layout.master')

@section('title')
<title>إضافة تـكـلـيـف </title>
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
              <li class="breadcrumb-item" aria-current="page">إضــافــة تـكـلـيـف جـديـد </li>
            </ul>
          </div>
          <div class="col-6">
            <div class="page-header-title">
              <h2 class="mb-0">إضــافــة تـكـلـيـف جـديـد </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">تـقـديـم التـكـلـيـف للـمـوظـف</h4>

            <form action="{{ route('saveTask', ['id' => 0]) }}" enctype="multipart/form-data" method="post">
              @csrf
              <div class="row mb-4">
                <div class="col-12 pt-3">
                  <h4 class="mb-sm-0 font-size-18" style="color: blue; text-align: center;" id="description"></h4>
                </div>

                <div class="col-lg-4">
                  <label class="col-form-label col-lg-4" style="font-size:100%;">الموظف
                    <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                  </label>
                  <select name="emp_id" class="form-control js-example-basic-single" multiple="multiple" lang="ar" required
                          oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')" oninput="this.setCustomValidity('')">
                    @foreach ($employees as $emp)
                      <option value="{{ $emp->id }}">{{ $emp->person->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-lg-4">
                  <label for="number" class="col-form-label col-lg-4" style="font-size:100%;">الـرقـم الإشـاري</label>
                  <input id="number" name="number" type="text" class="form-control" value="{{ old('number') }}" required
                         oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الإشاري لتكليف')" oninput="this.setCustomValidity('')"
                         placeholder="الـرقـم الإشـاري">
                </div>

                <div class="col-lg-4">
                  <label for="title" class="col-form-label col-lg-4" style="font-size:100%;">سـبـب التكليف</label>
                  <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required
                         oninvalid="this.setCustomValidity('الرجاء ادخال سـبـب تكليف')" oninput="this.setCustomValidity('')"
                         placeholder="سـبـب تكليف">
                </div>

                <div class="col-lg-4">
                  <label for="date" class="col-form-label col-lg-4" style="font-size:100%;">تـاريـخ الـتـكـلـيـف</label>
                  <input id="date" name="date" type="date" class="form-control" value="{{ old('date') }}" required
                         oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ تكليف')" oninput="this.setCustomValidity('')"
                         placeholder="YYYY-MM-DD">
                </div>

                <!-- جديد: مصدر القرار -->
                <div class="col-lg-4">
                  <label for="task_res" class="col-form-label col-lg-4" style="font-size:100%;">مــصــدر الـقــرار</label>
                  <input id="task_res" name="task_res" type="text" class="form-control" value="{{ old('task_res') }}"
                         placeholder="مثال: كتاب إداري / تعميم / بريد وارد ...">
                </div>

                <!-- جديد: ملاحظات -->
                <div class="col-lg-8">
                  <label for="note" class="col-form-label col-lg-4" style="font-size:100%;">مــلاحظــات</label>
                  <textarea id="note" name="note" rows="2" class="form-control" placeholder="ملاحظات إضافية (اختياري)">{{ old('note') }}</textarea>
                </div>

                <div class="col-sm-4 mt-4">
                  <div class="mb-3">
                    <label>وثـيـقـة (اختياري)</label>
                    <input name="files[]" type="file" class="form-control" multiple>
                  </div>
                </div>
              </div>

              <div class="row justify-content-start">
                <div class="col-lg-12 mt-3">
                  <button type="submit" class="btn btn-primary">حـفـظ التـكـلـيـف</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
    <!-- end row -->
  </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection

@section('script')
<script>
function dateValidation(inputElement) {
  var r1 = /^\d{1,2}-\d{1,2}-\d{4}$/;
  var r2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
  var r3 = /^\d{4}-\d{1,2}-\d{1,2}$/;
  var r4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/;
  if (r1.test(inputElement.value) || r2.test(inputElement.value) || r3.test(inputElement.value) || r4.test(inputElement.value)) {
    inputElement.setCustomValidity('');
  } else {
    inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');
  }
}
</script>
@endsection
