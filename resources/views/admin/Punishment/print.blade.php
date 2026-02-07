<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>العقوبات</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap');

    * { font-family: 'Cairo', sans-serif; box-sizing: border-box; }
    body { margin: 0; padding: 0; direction: rtl; background-color: #f4f4f4; }

    .container {
      width: 210mm;        /* A4 عرض */
      margin: 20px auto;
      padding: 25px 30px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }

    .header {
      display: flex; justify-content: space-between; align-items: center;
      padding-bottom: 15px; border-bottom: 1px solid #ccc; margin-bottom: 20px;
    }
    .header img { height: 60px; }
    .header h5 { font-size: 18px; margin: 0; flex-grow: 1; text-align: center; color: #333; }

    .info { text-align: center; margin-bottom: 10px; }
    .info span { font-size: 18px; font-weight: bold; color: #62a0ea; }

    .dates { display: flex; justify-content: space-between; font-size: 13px; color: #333; margin-bottom: 20px; }

    .details table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .details th { background-color: #62a0ea; color: white; padding: 8px; border: 1px solid #ccc; }
    .details td { padding: 6px; text-align: center; border: 1px solid #ddd; vertical-align: middle; }
    .details tr:nth-child(even) td { background-color: #f9f9f9; }
    .details .summary { background-color: #e2e2e2; font-weight: bold; }

    .footer { margin-top: 40px; text-align: center; font-size: 12px; padding-top: 10px; border-top: 2px solid #000; color: #444; }

    @media print {
      body { background: none; }
      .container { box-shadow: none; margin: 0; padding: 0; width: 100%; }
      .footer { border-top: 1px solid #000; }
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
      <span>العقوبات</span>
    </div>

    <div class="dates">
      <div>
        @if (!empty($query['month']))
          التاريخ:
          <strong>{{ $query['month'] }}</strong>
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
            <th style="width: 60px;">#</th>
            <th>نوع العقوبة</th>
            <th>الموظف</th>
            <th>تاريخ العقوبة</th>
            <th>رقم الكتاب</th>
            <th>الرقم الإشاري</th>
            <th>اسم موصي العقوبة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($punshes as $idx => $punsh)
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td>{{ $punsh->pun_type }}</td>
            <td>{{ optional(optional($punsh->emp)->person)->name ?? '-' }}</td>
            <td>{{ $punsh->pun_date }}</td>
            <td>{{ $punsh->book_num }}</td>
            <td>{{ $punsh->index }}</td>
            <td>{{ $punsh->penaltyName }}</td>
            <td>{{ $punsh->notes }}</td>
          </tr>
          @endforeach

          <tr class="summary">
            <td colspan="4">إجمالي عدد العقوبات</td>
            <td colspan="4" style="color: green;">{{ $punshes->count() }}</td>
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
