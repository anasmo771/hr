@extends('admin.layout.master')

@section('title')
<title>تـكـلـيـف الموظف </title>
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
              <li class="breadcrumb-item" aria-current="page">
                تـعـديــل التـكـلـيـف الــمــوظــف
                <span style="color: blue;">{{$task->emp->person->name}}</span>
              </li>
            </ul>
          </div>
          <div class="col-6">
            <div class="page-header-title">
              <h2 class="mb-0">
                تـعـديــل التـكـلـيـف الــمــوظــف
                <span style="color: blue;">{{$task->emp->person->name}}</span>
              </h2>
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
            <h4 class="card-title">بـيـانـات الـمـوظـف</h4>

            <div class="row">
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>اســم الــموظــف</label>
                  <h4>{{$task->emp->person->name}}</h4>
                </div>
              </div>

              @if ($task->emp->person->N_id)
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>الرقــم الوطــني</label>
                    <h4>{{$task->emp->person->N_id}}</h4>
                  </div>
                </div>
              @else
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>رقم الإقامة او الجواز لغير الليبين</label>
                    <h4>{{$task->emp->person->non_citizen_ref_no}}</h4>
                  </div>
                </div>
              @endif

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>المؤهــل في القرار</label>
                  <h4>{{$task->emp->qualification}}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>التــخــصص</label>
                  @if($task->emp->specialty)
                    <h4>{{$task->emp->specialty->name}}</h4>
                  @else
                    <h4> - </h4>
                  @endif
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>تاريــخ الاستــحقاق</label>
                  <h4>{{$task->emp->due}}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>الدرجــة الحــالية</label>
                  <h4>{{$task->emp->degree}}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>تاريــخ المبــاشرة</label>
                  <h4 id="earnDate">{{$task->emp->start_date}}</h4>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>رقــم القــرار</label>
                  <h4>{{$task->emp->res_num}}</h4>
                </div>
              </div>

              <div class="col-sm-4 mb-3">
                <label class="control-label">الـقـسـم</label>
                <h4>{{$task->emp->section->name}}</h4>
              </div>

              <div class="col-sm-4 mb-3">
                <label class="control-label">جـنـس الـمـوظـف</label>
                @if($task->emp->gender == 0)
                  <h4>ذكر</h4>
                @else
                  <h4>انثي</h4>
                @endif
              </div>

              <div class="col-sm-4 mb-3">
                <label class="control-label">الـحـالـة الاجـتـمـاعـيـة للـمـوظـف</label>
                @if($task->emp->status == 0)
                  <h4>اعزب</h4>
                @else
                  <h4>متزوج</h4>
                @endif
              </div>

              <div class="col-sm-4">
                <div class="mb-3">
                  <label>تاريخ اخر تسوية وظيفية </label>
                  <h4>{{$task->emp->last_sett}}</h4>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h4 class="card-title mb-4"></h4>

            <form action="{{ route('tasks.update',[$task->id]) }}" enctype="multipart/form-data" method="post">
              @csrf
              @method('PATCH')
              <div class="row mb-4">

                <div class="col-lg-4">
                  <label for="number" class="col-form-label col-lg-4" style="font-size:100%;">الـرقـم الإشـاري</label>
                  <input id="number" value="{{ $task->number }}" name="number" type="text" class="form-control" required
                         oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الإشاري لتكليف')"
                         oninput="this.setCustomValidity('')"
                         placeholder="الـرقـم الإشـاري">
                </div>

                <div class="col-lg-4">
                  <label for="title" class="col-form-label col-lg-4" style="font-size:100%;">سـبـب تكليف</label>
                  <input id="title" value="{{ $task->title }}" name="title" type="text" class="form-control" required
                         oninvalid="this.setCustomValidity('الرجاء ادخال سـبـب تكليف')"
                         oninput="this.setCustomValidity('')"
                         placeholder="سـبـب تكليف">
                </div>

                <div class="col-lg-4">
                  <label for="date" class="col-form-label col-lg-4" style="font-size:100%;">تـاريـخ الـتـكـلـيـف</label>
                  <input id="date" value="{{ $task->date }}" name="date" type="date" oninput="dateValidation(this)"
                         placeholder="YYYY-MM-DD" class="form-control" required
                         oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ تكليف')"
                         oninput="this.setCustomValidity('')">
                </div>

                <!-- جديد: مصدر القرار task_res -->
                <div class="col-lg-4">
                  <label for="task_res" class="col-form-label col-lg-4" style="font-size:100%;">مــصــدر الـقــرار</label>
                  <input id="task_res" value="{{ $task->task_res }}" name="task_res" type="text" class="form-control"
                         placeholder="مثال: كتاب إداري / تعميم / بريد وارد ...">
                </div>

                <!-- جديد: ملاحظات note -->
                <div class="col-lg-8">
                  <label for="note" class="col-form-label col-lg-4" style="font-size:100%;">مــلاحظــات</label>
                  <textarea id="note" name="note" rows="2" class="form-control"
                            placeholder="ملاحظات إضافية (اختياري)">{{ $task->note }}</textarea>
                </div>

                <div class="col-sm-4 mt-4">
                  <div class="mb-3 ">
                    <label>وثـيـقـة  (اختياري)</label>
                    <input name="files[]" type="file" class="form-control" multiple>
                  </div>
                </div>

              </div>

              <div class="row justify-content-start">
                <div class="col-lg-12 mt-3">
                  <button type="submit" class="btn btn-primary">حـفـظ</button>
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
  var r1=/^\d{1,2}-\d{1,2}-\d{4}$/;
  var r2=/^\d{1,2}\/\d{1,2}\/\d{4}$/;
  var r3=/^\d{4}-\d{1,2}-\d{1,2}$/;
  var r4=/^\d{4}\/\d{1,2}\/\d{1,2}$/;
  if (r1.test(inputElement.value)||r2.test(inputElement.value)||r3.test(inputElement.value)||r4.test(inputElement.value)){
    inputElement.setCustomValidity('');
  } else {
    inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');
  }
}
</script>
@endsection
