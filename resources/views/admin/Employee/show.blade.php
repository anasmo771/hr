@extends('admin.layout.master')

@section('title')
<title>بـيـانـات الموظف</title>
@endsection

@section('css')
<style>
    .onlyTable { counter-reset: rowNumber; }
    .onlyTable tr { counter-increment: rowNumber; }
    .onlyTable tr td:first-child::before {
        content: counter(rowNumber);
        min-width: 1em;
        margin-right: 0.5em;
    }
</style>
@endsection

@section('content')
@php use Carbon\Carbon; @endphp
<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('employees.edit', [$emp->id]) }}">تـعديـل</a></li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h3 class="mb-0">بيانات الموظف
                                <span style="color: #1b6bca;">{{ optional($emp->person)->name }}</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        @include('admin.layout.validation-messages')

        <!-- [ Top quick actions ] -->
        <div class="row text-center">
            <div class="col-12">
                @if(Auth::user()->role_id != 3)
                <ul class="list-inline m-0">
                    @can('feedback-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('feedback.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">تقرير الكفائة</a>
                    </li>
                    @endcan
                    @can('attendance-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('attendance.report.form', ['employee_id' => $emp->id]) }}" class="btn btn-primary fs-5 text-white">الغـيـاب</a>
                    </li>
                    @endcan
                    @can('course-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('courses.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">الـدورات</a>
                    </li>
                    @endcan
                    @can('punishment-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('punishments.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">العقوبات</a>
                    </li>
                    @endcan
                    @can('vacation-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('vacations.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">الإجازات</a>
                    </li>
                    @endcan
                    <li class="list-inline-item p-1">
                        <a href="{{ route('archivesEmployee.show', ['id' => $emp->id, 'type' => 0]) }}" class="btn btn-primary fs-5 text-white">الارشـيـف</a>
                    </li>
                    @can('task-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('tasks.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">التكليفات</a>
                    </li>
                    @endcan
                    @can('promotion-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('promotion.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">الـتـرقـيـات</a>
                    </li>
                    @endcan
                    @can('bonus-list')
                    <li class="list-inline-item p-1">
                        <a href="{{ route('bouns.show', [$emp->id]) }}" class="btn btn-primary fs-5 text-white">الـعـلاوات</a>
                    </li>
                    @endcan
                </ul>
                @endif
            </div>
        </div>
        <!-- end page title -->

        <div class="row mt-3">
            <div class="col-12">

                <!-- البطاقة: البيانات الشخصية -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">الـبـيـانـات الـشـخـصـيـة</h4>
                        @include('includes.messages')

                        <div class="row mt-3">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>اســم الــموظــف</label>
                                    <h4>{{ optional($emp->person)->name ?? '—' }}</h4>
                                </div>
                            </div>

                            @if(optional($emp->person)->N_id)
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الرقــم الوطــني</label>
                                    <h4>{{ $emp->person->N_id }}</h4>
                                </div>
                            </div>
                            @else
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>رقم الإقامة او الجواز لغير الليبين</label>
                                    <h4>{{ optional($emp->person)->non_citizen_ref_no ?? '—' }}</h4>
                                </div>
                            </div>
                            @endif

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>البريد الالكتروني</label>
                                    <h4>{{ optional($emp->person)->email ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>رقــم الـهـاتـف</label>
                                    <h4>{{ optional($emp->person)->phone ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>تاريــخ الـمـيـلاد</label>
                                    <h4>
                                        {{ optional($emp->person)->birth_date ? Carbon::parse($emp->person->birth_date)->format('d-m-Y') : '—' }}
                                    </h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الـجـنـس</label>
                                    <h4>{{ optional($emp->person)->gender ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الحالة الاجتماعية</label>
                                    <h4>{{ optional($emp->person)->marital_status ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الـبـلـد</label>
                                    <h4>{{ optional($emp->person)->country ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الـمـديـنـة</label>
                                    <h4>{{ optional($emp->person)->city ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الـمـنـطـقـة</label>
                                    <h4>{{ optional($emp->person)->street_address ?? '—' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- البطاقة: البيانات الوظيفية -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">الـبـيـانـات الـوظـيـفـيـة</h4>

                        <div class="row mt-3">

                            <div class="col-sm-4 mb-3">
                                <label class="control-label">الإدارة</label>
                                <h4>
                                    {{ optional($emp->section)->name ?? '—' }}
                                    @if($emp->subSection) - {{ optional($emp->subSection)->name }} @endif
                                </h4>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>حـالـة الـمـوظـف</label>
                                    <h4>{{ $emp->status }}</h4>
                                    @if($emp->status === 'مستقيل')
                                        @if($emp->startout_data)
                                            <h6 class="mt-2 mb-0"><small>تاريخ الاستقالة:
                                                {{ Carbon::parse($emp->startout_data)->format('d-m-Y') }}</small>
                                            </h6>
                                        @endif
                                        @if($emp->archive_char || $emp->archive_num)
                                            <h6 class="mb-0"><small>الأرشفة:
                                                {{ $emp->archive_char }}{{ ($emp->archive_char && $emp->archive_num) ? '-' : '' }}{{ $emp->archive_num }}
                                            </small></h6>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>نوع التوظيف</label>
                                    <h4>{{ $emp->type }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>رقــم القــرار</label>
                                    <h4>
                                        @if(in_array($emp->type, ['ندب','إعارة']))
                                            {{ optional(optional($emp->ndb)->last())->ndb_transfer_decision ?? '—' }}
                                        @else
                                            {{ $emp->res_num ?? '—' }}
                                        @endif
                                    </h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الوظيفة</label>
                                    <h4>
                                        {{ optional($emp->unitStaffing)->name ?? '—' }}
                                        @if(!empty($emp->staffing_name_via_unit))
                                            <small class="d-block text-muted">{{ $emp->staffing_name_via_unit }}</small>
                                        @elseif(optional($emp->staff)->name)
                                            <small class="d-block text-muted">{{ $emp->staff->name }}</small>
                                        @endif
                                    </h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>تاريــخ المبــاشرة</label>
                                    <h4>{{ $emp->start_date ? Carbon::parse($emp->start_date)->format('d-m-Y') : '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>الدرجــة الحــالية</label>
                                    <h4>{{ $emp->degree ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>تاريخ الحصول علي الدرجة الحالية</label>
                                    <h4>{{ $emp->degree_date ? Carbon::parse($emp->degree_date)->format('d-m-Y') : '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>رصيد الإجازات</label>
                                    <h4>{{ $emp->vacation_balance_days ?? 0 }} يوم</h4>
                                </div>
                            </div>

                            @if($emp->futureBonus)
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>تاريخ استحقاق العلاوة القادمة</label>
                                    <h4>{{ Carbon::parse($emp->futureBonus)->format('d-m-Y') }}</h4>
                                </div>
                            </div>
                            @endif

                            @if($emp->futurepromotion)
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>تاريخ استحقاق الترقية القادمة</label>
                                    <h4>{{ Carbon::parse($emp->futurepromotion)->format('d-m-Y') }}</h4>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- البطاقة: بيانات المؤهل العلمي -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">بـيـانـات الـمـؤهـل الـعـلـمـي</h4>

                        <div class="row mt-3">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>المؤهــل في القرار</label>
                                    <h4>{{ $emp->qualification ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>التــخــصص</label>
                                    <h4>{{ optional($emp->specialty)->name ?? '—' }}</h4>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>تاريــخ الحــصول على المؤهل</label>
                                    <h4>{{ $emp->due ? Carbon::parse($emp->due)->format('d-m-Y') : '—' }}</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- جدول الندب / الإعارة -->
                @if(optional($emp->ndb)->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">سجل الندب / الإعارة</h4>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap table-hover" id="tab1">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col" class="text-center">ندب / إعارة</th>
                                        <th scope="col" class="text-center">رقم القرار</th>
                                        <th scope="col" class="text-center">وثيقة القرار</th>
                                        <th scope="col" class="text-center">تاريخ بداية القرار</th>
                                        <th scope="col" class="text-center">تاريخ نهاية القرار</th>
                                        <th scope="col" class="text-center">مصدر القرار</th>
                                        <th scope="col" class="text-center">قيود القرار</th>
                                        <th scope="col" class="text-center">مكان العمل</th>
                                        <th scope="col" class="text-center">الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody class="onlyTable text-center">
                                    @foreach ($emp->ndb as $index => $ndb)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $ndb->is_ndb ? 'نـدب' : 'إعـارة' }}</td>
                                        <td>{{ $ndb->ndb_transfer_decision }}</td>

                                        @if (optional($ndb->files)->count() > 0)
                                        <td>
                                            <a href=".showFile" data-bs-toggle="modal"
                                               onclick='showItem(@json($ndb->files))' class="text-primary">
                                                <i class="bx bx-file" style="font-size:25px;margin-top:5px"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td class="text-center"> — </td>
                                        @endif

                                        <td>{{ $ndb->ndb_start ? Carbon::parse($ndb->ndb_start)->format('d-m-Y') : '—' }}</td>
                                        <td>{{ $ndb->ndb_end ? Carbon::parse($ndb->ndb_end)->format('d-m-Y') : '—' }}</td>
                                        <td>{{ $ndb->dec_source ?? '—' }}</td>
                                        <td>{{ $ndb->dec_constraints ?? '—' }}</td>
                                        <td>{{ $ndb->ndb_workplace ?? '—' }}</td>
                                        <td class="text-center">—</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
        <!-- end row -->

        <!-- Modal عرض الملفات -->
        <div class="row" dir="rtl">
            <div class="col-12">
                <div class="modal fade showFile" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl">صورة من الوثيقة
                                    <span id="arrName"></span> </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="print">
                                <div dir="rtl">
                                    <div class="table-responsive" dir="rtl">
                                        <table class="table table-hover table-center">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">عرض</th>
                                                    <th class="text-center">تنزيل</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyrow"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection

@section('script')
<script>
function showItem(images) {
    var table = document.getElementById("bodyrow");
    table.innerHTML = "";
    if (!images || !images.length) return;

    for (var i = 0; i < images.length; i++) {
        var row = table.insertRow(-1);

        // #
        var cell = row.insertCell();
        cell.textContent = (i + 1);
        cell.style.textAlign = "center";

        // عرض
        cell = row.insertCell();
        cell.innerHTML = "<a href='/storage/" + images[i]['path'] + "' target='_blank' class='text-primary'><i class='bx bx-show' style='font-size:25px;margin-top:5px'></i></a>";
        cell.style.textAlign = "center";

        // تنزيل
        cell = row.insertCell();
        var idd = images[i]['id'];
        cell.innerHTML = "<a href='/downloadFile/" + idd + "' class='text-primary'><i class='bx bx-download' style='font-size:25px;margin-top:5px'></i></a>";
        cell.style.textAlign = "center";
    }
}
</script>
@endsection
