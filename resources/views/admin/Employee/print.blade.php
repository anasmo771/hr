<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>طباعة الموظفين</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap');

    * { font-family: 'Cairo', sans-serif; box-sizing: border-box; }
    body { margin: 0; padding: 0; background-color: #f0f0f0; direction: rtl; }

    .container {
      width: 210mm;
      margin: 20px auto;
      padding: 25px 30px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }

    .header {
      display: flex; justify-content: space-between; align-items: center;
      padding-bottom: 15px; border-bottom: 2px solid #ccc; margin-bottom: 20px;
    }
    .header img { height: 60px; }
    .header h5 { font-size: 18px; margin: 0; flex-grow: 1; text-align: center; color: #333; }

    .info { text-align: center; margin-bottom: 10px; }
    .info span { font-size: 18px; font-weight: bold; color: #62a0ea; }

    .dates { display: flex; justify-content: space-between; font-size: 13px; color: #333; margin-bottom: 20px; }

    .details table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .details th { background-color: #62a0ea; color: #fff; padding: 8px; border: 1px solid #ccc; }
    .details td { padding: 6px; text-align: center; border: 1px solid #ddd; }
    .details tr:nth-child(even) td { background-color: #f9f9f9; }
    .details .summary { background-color: #e2e2e2; font-weight: bold; }
    .details td small { display: block; color: #555; }
    .details thead th, .details tr { page-break-inside: avoid; }

    .footer { margin-top: 40px; text-align: center; font-size: 12px; padding-top: 10px; border-top: 2px solid #000; color: #444; }

    @media print {
      body { background: none; }
      .container { box-shadow: none; margin: 0; padding: 0; width: 100%; }
      .footer { border-top: 1px solid #000; }
      h5 { font-size: 14px; }
    }
  </style>
</head>
<body>
@php use Carbon\Carbon; @endphp
  <div class="container">
    <div class="header">
      <img src="{{ asset('assets/images/logo.png') }}" alt="LOGO">
      <h5>نظام إدارة شؤون الموظفين</h5>
    </div>

    <div class="info">
      <span>
        قائمة الموظفين
        @isset($page)
          ({{ $page }})
        @endisset
      </span>
    </div>

    <div class="dates">
      <div>
        @if (isset($query['startDate']) || isset($query['endDate']))
          التاريخ:
          <strong>
            @if (isset($query['startDate'])) {{ $query['startDate'] }} @endif
            @if (isset($query['endDate'])) - {{ $query['endDate'] }} @endif
          </strong>
        @endif
      </div>
      <div>تاريخ الطباعة: <strong>{{ now()->format('Y-m-d') }}</strong></div>
    </div>

    <div class="details">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>الرقم الوطني</th>
            <th>الدرجة الحالية</th>
            <th>رصيد الإجازات (يوم)</th>
            <th>البلد</th>
            <th>تاريخ المباشرة</th>
            <th>نوع التوظيف</th>
            <th>رقم القرار</th>
            <th>الإدارة</th>
            <th>الحالة الوظيفية</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($employees as $emp)
          <tr>
            <td>{{ $loop->iteration }}</td>

            <td>{{ optional($emp->person)->name ?? '—' }}</td>

            @if (optional($emp->person)->N_id)
              <td>{{ $emp->person->N_id }}</td>
            @else
              <td>
                <span style="color: #1b6bca;">أجنبي الجنسية</span>
                <small>{{ optional($emp->person)->non_citizen_ref_no ?? '—' }}</small>
              </td>
            @endif

            <td>
              {{ $emp->degree ?? '—' }}
              @if($emp->degree_date)
                <br>
                <small>{{ Carbon::parse($emp->degree_date)->format('d-m-Y') }}</small>
              @endif
            </td>

            <td>{{ $emp->vacation_balance_days ?? 0 }}</td>

            <td>{{ optional($emp->person)->country ?? '—' }}</td>

            <td>
              {{ $emp->start_date ? Carbon::parse($emp->start_date)->format('d-m-Y') : '—' }}
            </td>

            <td>{{ $emp->type ?? '—' }}</td>

            <td>
              @if ($emp->type === 'ندب' || $emp->type === 'إعارة')
                {{ optional($emp->ndb->last())->ndb_transfer_decision ?? '—' }}
              @else
                {{ $emp->res_num ?? '—' }}
              @endif
            </td>

            <td>
              {{ optional($emp->section)->name ?? '—' }}
              @if ($emp->subSection)
                - {{ optional($emp->subSection)->name }}
              @endif
            </td>

            <td>
              {{ $emp->status ?? '—' }}
              @if ($emp->status === 'مستقيل')
                @if ($emp->startout_data)
                  <small>تاريخ الاستقالة: {{ Carbon::parse($emp->startout_data)->format('d-m-Y') }}</small>
                @endif
                @if ($emp->archive_char || $emp->archive_num)
                  <small>
                    الأرشفة:
                    {{ $emp->archive_char }}{{ ($emp->archive_char && $emp->archive_num) ? '-' : '' }}{{ $emp->archive_num }}
                  </small>
                @endif
              @endif
            </td>
          </tr>
          @endforeach

          @if (($page ?? '') === 'الكل')
            <tr class="summary">
              <td colspan="6">عدد الموظفين (يعمل)</td>
              <td colspan="5" style="color: green;">{{ $employees->where('status', 'يعمل')->count() }}</td>
            </tr>
            <tr class="summary">
              <td colspan="6">عدد الموظفين (مستقيل)</td>
              <td colspan="5" style="color: green;">{{ $employees->where('status', 'مستقيل')->count() }}</td>
            </tr>
            <tr class="summary">
              <td colspan="6">عدد الموظفين (منقطع)</td>
              <td colspan="5" style="color: green;">{{ $employees->where('status', 'منقطع')->count() }}</td>
            </tr>
            <tr class="summary">
              <td colspan="6">عدد الموظفين (أجنبي الجنسية)</td>
              <td colspan="5" style="color: green;">
                {{ $employees->filter(fn($e) => optional($e->person)->non_citizen_ref_no !== null)->count() }}
              </td>
            </tr>
          @endif

          <tr class="summary">
            <td colspan="6">إجمالي العدد</td>
            <td colspan="5" style="color: green;">{{ $employees->count() }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="footer">
      جميع الحقوق محفوظة © {{ now()->year }}
    </div>
  </div>
</body>
</html>
