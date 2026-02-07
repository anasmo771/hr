@extends('admin.layout.master')

@section('title')
    <title>كل الموظفين</title>
@endsection

@section('css')
<style>
  /* حاوية التمرير */
  .limit-10{
    position: relative;      /* ضروري لتثبيت الرأس */
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #f1f1f1;
  }
  .limit-10 table{ margin-bottom: 0; }

  /* تثبيت صف العناوين */
  .limit-10 thead th{
    position: sticky;
    top: 0;
    background: #fff !important;
    z-index: 3;
    box-shadow: 0 1px 0 rgba(0,0,0,.05);
    text-align: center;
  }

  .table tbody td{ vertical-align: middle; text-align: center; }
  .avatar-sm{ height: 34px; width: 34px; object-fit: cover; }
</style>
@endsection

@section('content')
@php use Carbon\Carbon; @endphp

<div class="pc-container">
  <div class="pc-content">

    <!-- Breadcrumb -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item active" aria-current="page">قائمة الموظفين</li>
            </ul>
          </div>
          <div class="col-md-12">
            <h2 class="page-header-title mb-0">قائمة الموظفين</h2>
          </div>
        </div>
      </div>
    </div>
    <!-- End Breadcrumb -->

    @include('admin.layout.validation-messages')

    <!-- Main Content -->
    @if ($employees->count() > 0)
      <div class="row">
        <div class="col-lg-12">
          <div class="card">

            {{-- بحث --}}
            <form action="{{ route('searchEmployee') }}" method="get" class="px-3 py-2">
              @csrf
              <div class="row g-2 align-items-center">

                <div class="col-lg-4">
                  <select name="type" class="form-select rounded-pill">
                    <option value="name" {{ (request('type') ?? 'name')==='name' ? 'selected' : '' }}>الاسم</option>
                    <option value="id"   {{ request('type')==='id' ? 'selected' : '' }}>الرقم الوطني</option>
                    <option value="degree" {{ request('type')==='degree' ? 'selected' : '' }}>الدرجة الوظيفية</option>
                    <option value="res_num" {{ request('type')==='res_num' ? 'selected' : '' }}>رقم القرار</option>
                  </select>
                </div>

                <div class="col-lg-4">
                  <input type="text" name="name" value="{{ request('name') }}" class="form-control rounded-pill" placeholder="البحث عن موظف">
                </div>

                <div class="col-lg-4">
                  <div class="input-group input-group-sm">
                    <select name="section_id" class="form-select rounded-start-pill">
                      <option disabled value="0" {{ request('section_id')===null ? 'selected' : '' }}>اختر الإدارة</option>
                      <option value="all" {{ request('section_id')==='all' ? 'selected' : '' }}>عرض الكل</option>
                      @foreach ($subsection1 as $su)
                        <option value="{{ $su->id }}" {{ request('section_id')==$su->id ? 'selected' : '' }}>
                          {{ $su->name }}
                        </option>
                      @endforeach
                    </select>
                    <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                      <i class="mdi mdi-magnify"></i>
                    </button>
                  </div>
                </div>

              </div>
            </form>

            <div class="card-body">

              <div class="col-lg-2">
                <div class="form-group m-0 p-0 d-inline-block mb-3">
                  <a href=".printTable" data-bs-toggle="modal" class="btn btn-primary btn-sm rounded-pill">
                    <i class="mdi mdi-printer me-1"></i> طباعة
                  </a>
                </div>
              </div>

              <div id="print2">
                <!-- جدول مع رأس ثابت و10 صفوف مرئية -->
                <div class="table-responsive limit-10">
                  <table class="table table-hover table-nowrap align-middle" id="tab1">
                    <thead class="table-light">
                      <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الرقم الوطني</th>
                        <th>الدرجة الحالية</th>
                        <th>رصيد الإجازات (يوم)</th>
                        <th>تاريخ المباشرة</th>
                        <th>نوع التوظيف</th>
                        <th>رقم القرار</th>
                        <th>الإدارة</th>
                        <th>الحالة الوظيفية</th>
                        <th>المنشئ</th>
                        <th>عرض التفاصيل</th>
                        <th class="">الإجراءات</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($employees as $index => $emp)
                        <tr>
                          <td>{{ $index + 1 }}</td>

                          <td class="text-start">
                            @if (optional($emp->person)->image)
                              <img src="{{ asset(Storage::url($emp->person->image)) }}" class="rounded-circle avatar-sm me-2" alt="user-pic">
                            @endif
                            {{ optional($emp->person)->name ?? '-' }}
                          </td>

                          <td>
                            @if (optional($emp->person)->N_id)
                              {{ $emp->person->N_id }}
                            @else
                              <span class="text-primary d-block">أجنبي الجنسية</span>
                              {{ $emp->person->non_citizen_ref_no ?? '-' }}
                            @endif
                          </td>

                          <td>
                            {{ $emp->degree ?? '-' }} <br>
                            <small class="text-muted">
                              {{ $emp->degree_date ? Carbon::parse($emp->degree_date)->format('d-m-Y') : '-' }}
                            </small>
                          </td>

                          <td>{{ $emp->vacation_balance_days ?? 0 }}</td>

                          <td>{{ $emp->start_date ? Carbon::parse($emp->start_date)->format('d-m-Y') : '-' }}</td>

                          <td>{{ $emp->type }}</td>

                          <td>
                            @if ($emp->type == 'ندب' || $emp->type == 'إعارة')
                              رقم قرار {{ $emp->type }}<br>
                              {{ optional($emp->ndb->last())->ndb_transfer_decision ?? '-' }}
                            @else
                              {{ $emp->res_num ?? '-' }}
                            @endif
                          </td>

                          <td class="text-start">
                            {{ optional($emp->section)->name ?? '-' }}
                            @if ($emp->subSection)
                              - {{ $emp->subSection->name }}
                            @endif
                          </td>

                          <td>{{ $emp->status }}</td>

                          <td>{{ optional($emp->user)->name ?? '-' }}</td>

                          <td>
                            <a href="{{ route('EmployeeDetails', [$emp->id]) }}" class="text-decoration-none">
                              <i class="fa-solid fa-bars"></i>
                            </a>
                          </td>

                          <td class="text-center">
                            @if (!$emp->startout_data && (auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
                              <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                  <a href="{{ route('employees.edit', [$emp->id]) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fa-solid fa-pen"></i>
                                  </a>
                                </li>
                                <li class="list-inline-item">
                                  <a href="{{ route('resignation.edit', [$emp->id]) }}" class="btn btn-sm btn-outline-danger" title="استقالة">
                                    <i class="fa-solid fa-user-xmark"></i>
                                  </a>
                                </li>
                              </ul>
                            @else
                              -
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                {{-- لا نعرض Pagination لأننا نستخدم تمرير داخلي لعرض أول 10 --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="row">
        <div class="col-lg-12">
          <h4 class="text-center fs-5">لا يوجد موظفين</h4>
        </div>
      </div>
    @endif
    <!-- End Main Content -->

  </div>
</div>

<!-- Print Modal -->
<div class="modal fade printTable" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true" dir="rtl">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('employees.print') }}" target="_blank" method="GET">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="printModalLabel">طباعة الموظفين</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
        </div>
        <div class="modal-body">
          <label for="select_type" class="form-label">حالة الموظفين</label>
          <select name="type" id="select_type" class="form-control" required
                  oninvalid="this.setCustomValidity('الرجاء اختيار حالة الموظفين المراد طباعتهم')"
                  oninput="this.setCustomValidity('')">
            <option value="الكل" selected>الكل</option>
            <option value="الاجانب">الأجانب</option>
            <option value="المستقلين">المستقلين</option>
            <option value="المنقطعين">المنقطعين</option>
            <option value="عقود">عقود</option>
            <option value="تعيينات">تعيينات</option>
            <option value="إعارة">إعارة</option>
            <option value="ندب">ندب</option>
          </select>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-primary">طباعة</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">إغلاق</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Print Modal -->
@endsection

@section('script')
<script>
/* ضبط ارتفاع الحاوية لعرض 10 صفوف (رأس + 10 صفوف) */
function clampTenRows(selector){
  document.querySelectorAll(selector).forEach(function(wrap){
    const table = wrap.querySelector('table');
    if(!table || !table.tBodies.length) return;

    const headRow = table.tHead ? table.tHead.rows[0] : null;
    const headH   = headRow ? headRow.getBoundingClientRect().height : 0;

    const firstRow = table.tBodies[0].rows[0];
    if(!firstRow) return;

    const rowH    = firstRow.getBoundingClientRect().height || 48;
    const padding = 4;
    const target  = Math.round(headH + rowH * 10 + padding);

    wrap.style.height    = target + 'px';
    wrap.style.overflowY = 'auto';
  });
}

window.addEventListener('load',  ()=>clampTenRows('.limit-10'));
window.addEventListener('resize',()=>clampTenRows('.limit-10'));
setTimeout(()=>clampTenRows('.limit-10'), 300);

/* الطباعة كما كانت */
function printDiv() {
  // إزالة عمود الإجراءات وعمود التفاصيل قبل الطباعة
  const t = document.getElementById('tab1');
  if(!t) return;
  // آخر عمود = الإجراءات
  Array.from(t.querySelectorAll('tr')).forEach(tr => tr.lastElementChild && tr.removeChild(tr.lastElementChild));
  // الآن آخر عمود = عرض التفاصيل
  Array.from(t.querySelectorAll('tr')).forEach(tr => tr.lastElementChild && tr.removeChild(tr.lastElementChild));
  // أول عمود = الترقيم
  Array.from(t.querySelectorAll('tr')).forEach(tr => tr.firstElementChild && tr.removeChild(tr.firstElementChild));

  const printContent    = document.getElementById("print2").innerHTML;
  const originalContent = document.body.innerHTML;
  document.body.innerHTML = printContent;
  window.print();
  document.body.innerHTML = originalContent;
  window.location.reload();
}

/* تصدير (يتطلب XLSX محمّلًا في التخطيط العام) */
function exportData(type, name) {
  const t = document.getElementById('tab1');
  if(!t) return;

  Array.from(t.querySelectorAll('tr')).forEach(tr => tr.lastElementChild && tr.removeChild(tr.lastElementChild));
  Array.from(t.querySelectorAll('tr')).forEach(tr => tr.lastElementChild && tr.removeChild(tr.lastElementChild));
  Array.from(t.querySelectorAll('tr')).forEach(tr => tr.firstElementChild && tr.removeChild(tr.firstElementChild));

  var file = XLSX.utils.table_to_book(t, { sheet: "الموظفين" });
  XLSX.writeFile(file, name + '.' + type);
  window.location.reload();
}
</script>
@endsection
