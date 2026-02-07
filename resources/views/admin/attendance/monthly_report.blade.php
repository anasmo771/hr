@extends('admin.layout.master')

@section('title')
  <title>التقرير الشهري للحضور والانصراف</title>
@endsection

@php
use Carbon\Carbon;

// قراءة month من الطلب
$reqMonth = request('month'); // "2025-08" أو "08"
$reqYear  = request('year');

$y = now()->year;
$m = now()->month;

if ($reqMonth && preg_match('/^\d{4}-\d{2}$/', $reqMonth)) {
    [$y, $m] = array_map('intval', explode('-', $reqMonth));
} elseif ($reqMonth) {
    $m = (int) $reqMonth;
    if ($reqYear) $y = (int) $reqYear;
} elseif ($reqYear) {
    $y = (int) $reqYear;
}

// حماية
if ($m < 1 || $m > 12)  $m = now()->month;
if ($y < 1000 || $y > 3000) $y = now()->year;

// الأهم: استخدم قيم الكنترولر إن وُجدت لضمان التطابق مع stateGrid
if (isset($date['year'], $date['month'])) {
  $y = (int)$date['year'];
  $m = (int)$date['month'];
}

$monthForInput = sprintf('%04d-%02d', $y, $m);
$start = Carbon::create($y, $m, 1)->startOfMonth();
$end   = $start->copy()->endOfMonth();
$daysCount = $end->day;

// دالة مساعدة للتلميحات
if (!function_exists('hmsToMinutes')) {
  function hmsToMinutes(?string $hms): int {
    if (!$hms) return 0;
    [$h,$mi,$s] = array_map('intval', explode(':', $hms));
    return $h*60 + $mi + (int) floor($s/60);
  }
}

// قيم الكنترولر (تهيئة آمنة)
$stateGrid    = $stateGrid    ?? [];  // ['empId']['Y-m-d'] => 'P'/'L'/'V'/'A'
$attMap       = $attMap       ?? [];  // ['empId']['Y-m-d'] => Attendance
$weekendDays  = $weekendDays  ?? [Carbon::FRIDAY, Carbon::SATURDAY];
$uiTimes      = $uiTimes      ?? [];
$cutoff       = $cutoff       ?? Carbon::now()->startOfDay();
$vacDaysByEmp = $vacDaysByEmp ?? [];
@endphp

@section('content')
<div class="pc-container">
  <div class="pc-content">

    {{-- رأس الصفحة + الفلاتر (بدون تغيير) --}}
    <div class="card mb-3 shadow-sm border-0">
      <div class="card-body py-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="d-flex align-items-center gap-3">
            <h4 class="mb-0">التقرير الشهري للحضور</h4>
            @isset($stats['attendance_percentage'])
              <span class="badge bg-primary-subtle text-primary fw-semibold">
                {{ $stats['attendance_percentage'] }}% حضور إجمالي
              </span>
            @endisset
          </div>

          <form method="GET" class="row g-2 align-items-center m-0 flex-grow-1" style="max-width:1000px">
            <div class="col-auto">
              <input type="month" name="month" value="{{ $monthForInput }}"
                     class="form-control form-control-sm shadow-sm border-primary" style="min-width: 140px">
            </div>

            <div class="col-auto">
              <select name="sub_section_id" class="form-select form-select-sm shadow-sm border-primary" style="min-width: 170px">
                <option value="">كل الأقسام</option>
                @foreach($subSections as $s)
                  <option value="{{ $s->id }}" {{ (string)request('sub_section_id') === (string)$s->id ? 'selected' : '' }}>
                    {{ $s->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-auto">
              <select name="employment_type" class="form-select form-select-sm shadow-sm border-primary" style="min-width: 150px">
                <option value="">كل الأنواع</option>
                @foreach($employmentTypes as $t)
                  <option value="{{ $t }}" {{ (string)request('employment_type') === (string)$t ? 'selected' : '' }}>
                    {{ $t }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md d-flex">
              <div class="input-group input-group-sm shadow-sm">
                <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="employee_search" placeholder="ابحث بالاسم..."
                       value="{{ request('employee_search') }}">
              </div>
            </div>

            <div class="col-auto">
              <button class="btn btn-sm btn-primary shadow-sm px-3">تطبيق</button>
              <a class="btn btn-sm btn-outline-secondary"
                 href="{{ route('attendance.monthly.report', ['month' => $monthForInput]) }}">
                إعادة تعيين
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- دليل الألوان (بدون تغيير) --}}
    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
      <span class="legend l-p">P</span><small class="me-3">حضور</small>
      <span class="legend l-l">L</span><small class="me-3">متأخر</small>
      <span class="legend l-a">A</span><small class="me-3">غياب</small>
      <span class="legend l-v">V</span><small class="me-3">إجازة</small>
      <span class="legend l-wk">WK</span><small class="me-3">عطلة أسبوعية</small>
      <span class="legend l-r">R</span><small>مستقيل</small>
      <div class="ms-auto small text-muted">الحضور 07:30–10:30 | الانصراف 08:00–14:30</div>
      <div class="d-flex gap-2">
          <div class="ms-auto small text-muted">
            <a href="{{ route('attendance.report.form') }}" class="btn btn-sm btn-primary shadow-sm px-3">
            <i class="fas fa-file-alt"></i> تقرير موظف
          </a>
        </div>
        <div class="ms-auto small text-muted">
          <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-primary shadow-sm px-3">
            <i class="fas fa-user-check"></i> تسجيل الحضور والانصراف
          </a>
        </div>
      </div>
    </div>

    {{-- الجدول --}}
    <div class="scroll-container border rounded">
      <div class="scroll-sync" id="scrollBottom">
        <table class="table table-bordered align-middle table-monthly m-0">
          <thead>
            <tr>
              <th style="min-width: 240px" class="sticky-col bg-white">الموظفين</th>
              @for($d=1; $d <= $daysCount; $d++)
                @php
                  $dd = Carbon::create($y,$m,$d);
                  $isToday = $dd->isToday();
                @endphp
                <th class="text-center th-day {{ in_array($dd->dayOfWeek, $weekendDays ?? [], true) ? 'th-wk' : '' }} {{ $isToday ? 'th-today' : '' }}">
                  <div class="fw-semibold">{{ $dd->format('d') }}</div>
                  <small class="text-muted">{{ $dd->format('D') }}</small>
                </th>
              @endfor
            </tr>
          </thead>
          <tbody>
            @if($employees->isEmpty())
              <tr>
                <td colspan="{{ $daysCount + 1 }}" class="text-center text-muted py-5">
                  <i class="bi bi-info-circle me-2"></i>
                  لا يوجد موظفون مطابقون لمعايير الفلترة الحالية
                </td>
              </tr>
            @else
              @foreach($employees as $emp)
                @php
                  $empName    = $emp->person->name ?? 'بدون اسم';
                  $isResigned = !empty($emp->startout_data);
                  $vacDays    = $vacDaysByEmp[$emp->id] ?? [];
                @endphp
                <tr>
                  <td class="employee-cell sticky-col bg-white">
                    <div class="d-flex align-items-center gap-2">
                      <div class="avatar bg-light rounded-circle shadow-sm" style="width:34px;height:34px"></div>
                      <div>
                        <div class="fw-semibold">{{ $empName }}</div>
                        @if($isResigned)
                          <small class="badge bg-secondary">مستقيل</small>
                        @endif
                      </div>
                    </div>
                  </td>

                  @for($d=1; $d <= $daysCount; $d++)
                    @php
                      $cellDate = Carbon::create($y,$m,$d);
                      $dateStr  = $cellDate->toDateString();
                      $isToday  = $cellDate->isToday();
                      $wk       = in_array($cellDate->dayOfWeek, $weekendDays ?? [], true);

                      // القراءة الصحيحة فقط من stateGrid / attMap
                      
                      $state = $stateGrid[$emp->id][$dateStr] ?? null;   // الحالة النهائية
                      $rec   = $attMap[$emp->id][$dateStr]    ?? null;   // سجل اليوم (للتلميح)
                      $cellText = ''; $cellClass=''; $title='';
                    @endphp

                    @if($wk)
                      @php $cellText='WK'; $cellClass='c-wk'; $title='عطلة أسبوعية'; @endphp

                    @elseif(in_array($dateStr, $vacDays, true) || $state === 'V')
                      @php $cellText='V'; $cellClass='c-v'; $title='إجازة مقبولة'; @endphp

                    @elseif(isset($cutoff) && $cellDate->gt($cutoff))
                      @php $cellText=''; $cellClass=''; $title='تاريخ مستقبلي'; @endphp

                    @elseif($isToday)
                      @php
                        if ($state === 'P' || $state === 'L') {
                          $cellText = $state;
                          $cellClass = ($state === 'L') ? 'c-l' : 'c-p';
                          $title = ($state === 'L') ? 'متأخر اليوم' : 'حاضر اليوم';
                        } elseif ($state === 'V') {
                          $cellText='V'; $cellClass='c-v'; $title='إجازة اليوم';
                        } else {
                          $cellText=''; $cellClass='c-pending'; $title='بانتظار تسجيل الحضور';
                        }
                      @endphp

                    @elseif($state === 'P' || $state === 'L')
                      @php
                        $cellText = $state;
                        $cellClass = ($state === 'L') ? 'c-l' : 'c-p';

                        $in  = $rec->check_in_time ?? null;
                        $out = $rec->check_out_time ?? null;

                        $presentLimit = $uiTimes['check_in_present_limit'] ?? '08:15:00';
                        $workEnd      = $uiTimes['work_end']               ?? '14:00:00';

                        $lateMin     = ($in && $in > $presentLimit) ? max(hmsToMinutes($in)-hmsToMinutes($presentLimit),0) : 0;
                        $earlyOutMin = ($out && $out < $workEnd)     ? max(hmsToMinutes($workEnd)-hmsToMinutes($out),0)   : 0;

                        $outLabel = 'لا يوجد تسجيل انصراف';
                        if ($out) {
                          if     ($out <= ($uiTimes['check_out_first_end']  ?? '10:30:00')) $outLabel = 'انصراف النصف الأول';
                          elseif ($out <= ($uiTimes['check_out_second_end'] ?? '13:59:59')) $outLabel = 'انصراف النصف الثاني';
                          elseif ($out <= ($uiTimes['check_out_deadline']   ?? '14:30:00')) $outLabel = 'انصراف طبيعي';
                        }

                        $title = "IN: ".($in ?: '-')." • OUT: ".($out ?: '-')
                                .($lateMin ? " • Late: {$lateMin}m" : "")
                                .($earlyOutMin ? " • EarlyOut: {$earlyOutMin}m" : "")
                                ." • ".$outLabel;
                      @endphp

                    @else
                      @php $cellText='A'; $cellClass='c-a'; $title='غياب (لا يوجد حضور)'; @endphp
                    @endif

                    @php
                      // العطل الرسمية من config/attendance.php
                      $isHolidayConfig = in_array($dateStr, (array) config('attendance.holidays', []), true);

                      // اليوم ويكند؟
                      $isWeekend = $wk;

                      // اليوم مُعلّم كإجازة (سواء من vacDays أو stateGrid أو النص داخل الخلية)
                      $isVacationDay = in_array($dateStr, $vacDays, true) || $state === 'V' || $cellText === 'V';
                    @endphp

                    {{-- مستقيل --}}
                    @if($isResigned
                        && $emp->startout_data
                        && $cellDate->gte(\Carbon\Carbon::parse($emp->startout_data))
                        && !$isWeekend
                        && !$isHolidayConfig
                        && !$isVacationDay
                        && (!isset($cutoff) || $cellDate->lte($cutoff)) )  {{-- اختياري: لا تلوّن المستقبل --}}
                      @php
                        $cellText='R'; $cellClass='c-r'; $title='مستقيل';
                      @endphp
                    @endif

                    <td class="text-center cell {{ $cellClass }} {{ $isToday ? 'td-today' : '' }}"
                        data-bs-toggle="tooltip"
                        data-bs-title="{{ $title }}"
                        style="min-width: 40px">
                      <span>{{ $cellText }}</span>
                    </td>
                  @endfor
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
    </div>

    <div style="height:6px"></div>
  </div>
</div>
@endsection

@section('style')
<style>
  .table-monthly th.th-day{background:#f8fafc}
  .table-monthly th.th-day.th-wk{background:#eef3ff}
  .employee-cell{white-space:nowrap}
  .cell{cursor:default;font-weight:600}
  .th-today { background:#ffeeba !important; border-bottom:2px solid #ffc107 !important; }
  .td-today { background:#fff3cd !important; box-shadow: inset 0 0 0 2px #ffc107; }
  .c-p { background:#e9f7ef;color:#1e7e34 }
  .c-l { background:#fff4e5;color:#b35c00 }
  .c-a { background:#fdecea;color:#a71d2a }
  .c-v { background:#e8f1ff;color:#0b5ed7 }
  .c-wk{ background:#f1f3f5;color:#6c757d }
  .c-r { background:#eee;color:#6c757d }
  .c-pending { background:#ffffff; color:#6c757d; }
  .legend{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;font-weight:700}
  .l-p{background:#e9f7ef;color:#1e7e34}
  .l-l{background:#fff4e5;color:#b35c00}
  .l-a{background:#fdecea;color:#a71d2a}
  .l-v{background:#e8f1ff;color:#0b5ed7}
  .l-wk{background:#f1f3f5;color:#6c757d}
  .l-r{background:#eee;color:#6c757d}
  .scroll-container{overflow-x:auto; overflow-y:hidden;}
  .scroll-sync{overflow-x:auto; overflow-y:hidden; white-space:nowrap;}
  .sticky-col{ position: sticky; left: 0; z-index: 3; box-shadow: 2px 0 0 rgba(0,0,0,.03); }

.table-monthly .cell.c-p,
.table-monthly .cell.c-l,
.table-monthly .cell.c-a,
.table-monthly .cell.c-v,
.table-monthly .cell.c-wk,
.table-monthly .cell.c-r {
  background: transparent !important;
  color: inherit;
}

.table-monthly .cell > span{
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 28px;
  height: 28px;
  padding: 0 6px;
  border-radius: 6px;
  font-weight: 700;
  line-height: 1;
}

/* خفِ الحاوية إذا الخلية فاضية (مستقبل/بانتظار تسجيل) */
.table-monthly .cell > span:empty{ display:none; }

.table-monthly .cell.c-p > span{ background:#e9f7ef; color:#1e7e34; } /* حضور */
.table-monthly .cell.c-l > span{ background:#fff4e5; color:#b35c00; } /* متأخر */
.table-monthly .cell.c-a > span{ background:#fdecea; color:#a71d2a; } /* غياب */
.table-monthly .cell.c-v > span{ background:#e8f1ff; color:#0b5ed7; } /* إجازة */
.table-monthly .cell.c-wk> span{ background:#f1f3f5; color:#6c757d; } /* عطلة */
.table-monthly .cell.c-r > span{ background:#eee;    color:#6c757d; } /* مستقيل */

/*تمييز اليوم الحالي */
.table-monthly .td-today { background:#fff3cd !important; box-shadow: inset 0 0 1px 0 #ffc107; }

</style>
@endsection

@section('script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el, {container: 'body', trigger: 'hover', placement: 'auto'});
    });
  });
</script>
@endsection
