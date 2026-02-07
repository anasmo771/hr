@extends('admin.layout.master')

@section('title')
  <title>لوحة التحكم</title>
@endsection

@section('css')
<style>
  /* إزاحة من الأسفل حتى لا يلتصق المحتوى */
  .pc-content{ padding-bottom:48px; }

  /* ===== بطاقات الموجز ===== */
  .stat-card{
    position:relative; height:100%;
    border-radius:18px; padding:18px 20px 46px; /* مساحة إضافية لزر "عرض المزيد" */
    box-shadow:0 6px 14px rgba(0,0,0,.04); transition:.15s;
  }
  .stat-card:hover{ transform:translateY(-2px); box-shadow:0 10px 22px rgba(0,0,0,.06)}
  .stat-value{font-size:22px; font-weight:700}
  .stat-label{font-size:13px; color:#6b7280}
  .icon-wrap{
    width:56px; height:56px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-size:26px; box-shadow:inset 0 0 0 1px rgba(0,0,0,.04);
  }

  /* زر عرض المزيد — أسفل يسار وبنفس لون الأيقونة */
  .more-link{
    position:absolute; left:14px; bottom:10px;
    display:inline-flex; align-items:center; gap:6px;
    font-weight:700; font-size:12px; border-radius:10px;
    padding:.44rem .72rem; text-decoration:none;
  }
  .more-link i{font-size:16px}
  .more-link.primary{ background:#e8f0ff; color:#0d6efd; }
  .more-link.primary:hover{ background:#dbe7ff; color:#0a58ca; }
  .more-link.secondary{ background:#f2f2f2; color:#8c8c8c; }
  .more-link.secondary:hover{ background:#d9d9d9; color:#737373; }
  .more-link.success{ background:#e8f6ef; color:#198754; }
  .more-link.success:hover{ background:#dbf0e6; color:#146c43; }
  .more-link.warning{ background:#fff5dd; color:#f59f00; }
  .more-link.warning:hover{ background:#ffecc1; color:#b77900; }

  /* ===== كروت الرسوم ===== */
  .chart-card{border-radius:18px; box-shadow:0 6px 14px rgba(0,0,0,.04); height:100%}
  .chart-card .card-body{padding:18px 20px; display:flex; flex-direction:column; height:100%}
  .chart-area{ position:relative; height:220px; min-height:220px } /* أصغر لترك حيز للإجازات */
  @media (max-width:1199.98px){ .chart-area{ height:200px; min-height:200px } }
  .donut-wrap{max-width:420px; margin-inline:auto}
  .legend-item{ display:inline-flex; align-items:center; gap:6px; margin-inline:6px; font-weight:600; font-size:12px }
  .legend-dot{ width:10px; height:10px; border-radius:999px; display:inline-block }

  /* ===== الإجازات (3 صفوف + أعمدة متساوية) ===== */
  .vac-card{border-radius:18px; box-shadow:0 6px 14px rgba(0,0,0,.04)}
  .vac-card .card-body{display:flex; flex-direction:column}
  .vac-table-wrap{ max-height:182px; overflow-y:auto; border-radius:12px; }
  .vac-table{ width:100%; table-layout:fixed; } /* ثبات اتساع الأعمدة */
  .vac-table thead th, .vac-table tbody td{ vertical-align:middle; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .vac-table-wrap::-webkit-scrollbar{ width:8px }
  .vac-table-wrap::-webkit-scrollbar-thumb{ background:#e5e7eb; border-radius:999px }

  .badge-soft{ padding:.35rem .6rem; border-radius:8px; font-weight:600; font-size:12px; background:#eef2ff; color:#4f46e5 }
  .badge-pending{ background:#fff7ed; color:#c2410c }
  .btn-icon{ width:34px; height:34px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center }
</style>
@endsection

@section('content')
<div class="pc-container">
  <div class="pc-content">

    {{-- العنوان والمسار --}}
    <div class="page-header mb-0">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb mb-2">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item" aria-current="page">نظرة عامة</li>
            </ul>
          </div>
          <div class="col-12">
            <div class="page-header-title"><h2 class="mb-0">لوحة التحكم</h2></div>
          </div>
        </div>
      </div>
    </div>

    {{-- بطاقات الموجز --}}
    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-value">{{ number_format($cards['sections']) }}</div>
              <div class="stat-label">الإدارات والأقسام</div>
            </div>
            <div class="icon-wrap bg-primary-subtle"><i class="fas fa-building text-primary"></i></div>
          </div>
          <a href="{{ route('subSection.index') }}" class="more-link primary"><i class="mdi mdi-arrow-left"></i> عرض المزيد</a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-value">{{ number_format($cards['specialties']) }}</div>
              <div class="stat-label">التخصصات</div>
            </div>
            <div class="icon-wrap bg-warning-subtle"><i class="fas fa-user-graduate text-warning"></i></div>
          </div>
          <a href="{{ route('Specialties.index') }}" class="more-link warning"><i class="mdi mdi-arrow-left"></i> عرض المزيد</a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-value">{{ number_format($cards['courses']) }}</div>
              <div class="stat-label">الدورات</div>
            </div>
            <div class="icon-wrap bg-success-subtle"><i class="fas fa-book text-success"></i></div>
          </div>
          <a href="{{ route('courses.index') }}" class="more-link success"><i class="mdi mdi-arrow-left"></i> عرض المزيد</a>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card card">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="stat-value">{{ number_format($cards['tasks']) }}</div>
              <div class="stat-label">التكليفات</div>
            </div>
            <div class="icon-wrap bg-secondary-subtle"><i class="fas fa-tasks text-secondary"></i></div>
          </div>
          <a href="{{ route('tasks.index') }}" class="more-link secondary"><i class="mdi mdi-arrow-left"></i> عرض المزيد</a>
        </div>
      </div>
    </div>

    {{-- الرسوم --}}
    <div class="row g-3 mb-3">
      <div class="col-12 col-lg-5">
        <div class="card chart-card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="mb-0">إجمالي عدد الموظفين</h5>
              <div class="small text-muted">حسب النوع</div>
            </div>
            <div class="donut-wrap mt-3">
              <div class="chart-area"><canvas id="employeesDonut"></canvas></div>
              <div class="text-center mt-2">
                <span class="legend-item"><span class="legend-dot" style="background:#62a0ea"></span> ذكور: {{ $maleEmployees }}</span>
                <span class="legend-item"><span class="legend-dot" style="background:#bcd6f6"></span> إناث: {{ $femaleEmployees }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-7">
        <div class="card chart-card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="mb-0">أحصائية الحضور والغياب والتأخير (آخر 8 أشهر)</h5>
              <div class="small text-muted">شهريًا</div>
            </div>
            <div class="chart-area mt-2"><canvas id="attendanceBar"></canvas></div>
          </div>
        </div>
      </div>
    </div>

    {{-- الإجازات (3 أسماء كحد أقصى) --}}
    <div class="card vac-card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h5 class="mb-0">طلبات الإجازة بانتظار الاعتماد</h5>
          <a href="{{ route('vacation.index') }}" class="btn btn-sm btn-primary">عرض الكل</a>
        </div>

        @if($pendingVacations->isEmpty())
          <div class="text-center text-muted py-4">لا توجد طلبات معلّقة.</div>
        @else
          <div class="vac-table-wrap">
            <table class="table align-middle table-nowrap mb-0 vac-table">
              {{-- عرض الأعمدة ثابت ومتساوٍ تقريباً --}}
              <colgroup>
                <col style="width:6%">
                <col style="width:10%">
                <col style="width:16%">
                <col style="width:14%">
                <col style="width:24%">
                <col style="width:8%">
                <col style="width:16%">
                <col style="width:6%">
              </colgroup>
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>الموظف</th>
                  <th>القسم</th>
                  <th>نوع الإجازة</th>
                  <th>الفترة</th>
                  <th>الأيام</th>
                  <th>الحالة</th>
                  <th>إجراءات</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pendingVacations as $idx => $v)
                  @php
                    $days = 0;
                    if ($v->start_date && $v->end_date) {
                      $days = \Carbon\Carbon::parse($v->start_date)->diffInDays(\Carbon\Carbon::parse($v->end_date)) + 1;
                    } elseif (!is_null($v->days)) {
                      $days = (int) $v->days;
                    }
                  @endphp
                  <tr>
                    <td>{{ $idx+1 }}</td>
                    <td>{{ optional($v->employee->person)->name }}</td>
                    <td>{{ optional($v->employee->subSection)->name }}</td>
                    <td>{{ $v->type }}</td>
                    <td>{{ $v->start_date }} – {{ $v->end_date }}</td>
                    <td>{{ $days }}</td>
                    <td><span class="badge-soft badge-pending">بانتظار الاعتماد</span></td>
                    <td>
                      <a href="{{ route('vacations.show', $v->emp_id) }}" class="btn btn-sm btn-light btn-icon" title="عرض">
                        <i class="mdi mdi-eye"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const palette = {
    male:'#62a0ea', female:'#bcd6f6',
    present:'#62a0ea', late:'#f59f00', absent:'#ec6066'
  };

  // مركز "الإجمالي" في الدونات
  const centerText = {
    id:'centerText',
    afterDraw(chart){
      const total = chart?.options?.plugins?.centerText?.total ?? null;
      if(!total) return;
      const {ctx, chartArea:{left,right,top,bottom}} = chart;
      const cx = (left+right)/2, cy = (top+bottom)/2;
      ctx.save(); ctx.textAlign='center'; ctx.textBaseline='middle';
      ctx.fillStyle='#6b7280'; ctx.font='600 14px system-ui, "Cairo", sans-serif'; ctx.fillText('الإجمالي', cx, cy-12);
      ctx.fillStyle='#111827'; ctx.font='700 20px system-ui, "Cairo", sans-serif'; ctx.fillText(total, cx, cy+12);
      ctx.restore();
    }
  };

  /* Doughnut */
  new Chart(document.getElementById('employeesDonut'),{
    type:'doughnut',
    data:{
      labels:['ذكور','إناث'],
      datasets:[{ data:[{{ $maleEmployees }}, {{ $femaleEmployees }}], backgroundColor:[palette.male,palette.female], borderWidth:0, hoverOffset:4 }]
    },
    options:{
      responsive:true, maintainAspectRatio:false, cutout:'70%',
      plugins:{ legend:{display:false}, tooltip:{rtl:true, callbacks:{label:(c)=>`${c.label}: ${c.parsed} موظف`}}, centerText:{ total: {{ $totalEmployees }} } },
      layout:{ padding:8 }
    },
    plugins:[centerText]
  });

  /* Bar (Attendance) */
  const labels  = @json($attendanceSeries['labels']);
  const present = @json($attendanceSeries['present']);
  const late    = @json($attendanceSeries['late']);
  const absent  = @json($attendanceSeries['absent']);

  new Chart(document.getElementById('attendanceBar').getContext('2d'),{
    type:'bar',
    data:{
      labels,
      datasets:[
        {label:'حضور', data:present, backgroundColor:palette.present, borderRadius:8, barThickness:16, maxBarThickness:18},
        {label:'تأخير',data:late,    backgroundColor:palette.late,    borderRadius:8, barThickness:16, maxBarThickness:18},
        {label:'غياب', data:absent,  backgroundColor:palette.absent,  borderRadius:8, barThickness:16, maxBarThickness:18}
      ]
    },
    options:{
      responsive:true, maintainAspectRatio:false,
      interaction:{ mode:'index', intersect:false },
      plugins:{
        legend:{ position:'bottom', labels:{ usePointStyle:true } },
        tooltip:{ rtl:true, callbacks:{ label:(c)=> `${c.dataset.label}: ${c.parsed.y}` } }
      },
      scales:{
        x:{ grid:{display:false}, ticks:{ maxRotation:0, minRotation:0 } },
        y:{ beginAtZero:true, ticks:{ precision:0 }, grid:{ color:'rgba(0,0,0,.06)' } }
      }
    }
  });
</script>
@endsection
