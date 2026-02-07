<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الإجازات</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500&display=swap');

    * {
      font-family: 'Cairo', sans-serif;
      box-sizing: border-box;
    }

    body {
      direction: rtl;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    .container {
      width: 210mm;
      margin: 20px auto;
      padding: 25px 30px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 15px;
      border-bottom: 1px solid #ccc;
      margin-bottom: 20px;
    }

    .header img {
      height: 60px;
    }

    .header h5 {
      font-size: 18px;
      margin: 0;
      flex-grow: 1;
      text-align: center;
      color: #333;
    }

    .info {
      text-align: center;
      margin-bottom: 10px;
    }

    .info span {
      font-size: 18px;
      font-weight: bold;
      color: #62a0ea;
    }

    .dates {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: #333;
      margin-bottom: 20px;
    }

    .details table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
    }

    .details th {
      background-color: #62a0ea;
      color: white;
      padding: 8px;
      border: 1px solid #ccc;
    }

    .details td {
      padding: 6px;
      text-align: center;
      border: 1px solid #ddd;
      vertical-align: middle;
    }

    .details tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    .details .summary {
      background-color: #e2e2e2;
      font-weight: bold;
    }

    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 12px;
      padding-top: 10px;
      border-top: 2px solid #000;
      color: #444;
    }

    @media print {
      body {
        background: none;
      }

      .container {
        box-shadow: none;
        margin: 0;
        padding: 0;
        width: 100%;
      }

      .footer {
        border-top: 1px solid #000;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ asset('assets/images/logo.png') }}" alt="LOGO">
      <h5>نظام إدارة شؤون الموظفيـن</h5>
    </div>

    <div class="info">
      <span>الإجازات</span>
    </div>

    <div class="dates">
      <div>
        @if (isset($query['startDate']) || isset($query['endDate']))
          التاريخ:
          <strong>
            {{ $query['startDate'] ?? '' }} - {{ $query['endDate'] ?? '' }}
          </strong>
        @endif
      </div>
      <div>
        تاريخ الطباعة:
        <strong>{{ now()->format('Y-m-d') }}</strong>
      </div>
    </div>

    <div class="details">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>اسم الموظف</th>
            <th>نوع الإجازة</th>
            <th>بداية الإجازة</th>
            <th>تاريخ المباشرة</th>
            <th>نهاية فعلية</th>
            <th>العد التنازلي</th>
            <th>عدد الأيام</th>
            <th>سبب الإجازة</th>
            <th>الموافقة</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($vacations as $vac)
          <tr>
            <td>{{ $vac->id }}</td>
            <td>{{ $vac->employee->person->name }}</td>
            <td>{{ $vac->type }}</td>
            <td>{{ $vac->start_date }}</td>
            <td>{{ $vac->end_date ?? '-' }}</td>
            <td>
                @if($vac->actual_end_date)
                    {{ \Carbon\Carbon::parse($vac->actual_end_date)->format('Y-m-d') }}
                @else
                    -
                @endif
            </td>


            @php
              $countdown = '-';
              if ($vac->end_date) {
                $endDate = \Carbon\Carbon::parse($vac->end_date);
                $daysRemaining = \Carbon\Carbon::now()->diffInDays($endDate, false);
                $countdown = $daysRemaining >= 0
                  ? "$daysRemaining يوم"
                  : "انتهت منذ " . abs($daysRemaining) . " يوم";
              }
            @endphp
            <td>{{ $countdown }}</td>

            <td>{{ $vac->days ? $vac->days . ' يوم' : '-' }}</td>
            <td>{{ $vac->reason }}</td>
            <td>
              @if($vac->accept)
                @if($vac->acceptFile)
                  <a href="{{ asset(Storage::url($vac->acceptFile)) }}" target="_blank" style="color: green;">تمت الموافقة</a>
                @else
                  <span style="color: green;">تمت الموافقة</span>
                @endif
              @else
                <span style="color: blue;">قيد الإجراء</span>
              @endif
            </td>
          </tr>
          @endforeach

          <tr class="summary">
            <td colspan="4">إجمالي الإجازات (تمت الموافقة)</td>
            <td colspan="5" style="color: green;">{{ $vacations->where('accept', true)->count() }}</td>
          </tr>
          <tr class="summary">
            <td colspan="4">إجمالي الإجازات (قيد الإجراء)</td>
            <td colspan="5" style="color: green;">{{ $vacations->where('accept', false)->count() }}</td>
          </tr>
          <tr class="summary">
            <td colspan="4">إجمالي العدد</td>
            <td colspan="5" style="color: green;">{{ $vacations->count() }}</td>
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
