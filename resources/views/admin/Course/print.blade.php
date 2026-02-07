<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الدورات</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap');

    * {
      font-family: 'Cairo', sans-serif;
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #f0f0f0;
      direction: rtl;
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
      border-bottom: 2px solid #ccc;
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
      font-size: 12px;
    }

    .details th {
      background-color: #62a0ea;
      color: #fff;
      padding: 8px;
      border: 1px solid #ccc;
    }

    .details td {
      padding: 7px;
      text-align: center;
      border: 1px solid #ddd;
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

      a {
        color: black !important;
        text-decoration: none;
        pointer-events: none;
        cursor: default;
      }

      i {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{asset('assets/images/logo.png')}}" alt="LOGO">
      <h5>نظام إدارة شؤون الموظفيـن</h5>
    </div>

    <div class="info">
      <span>الدورات</span>
    </div>

    <div class="dates">
      <div>
        @if (isset($query['startDate']) || isset($query['endDate']))
        التاريخ:
        <strong>
          @if (isset($query['startDate']))
            - {{ $query['startDate'] }}
          @endif
          @if (isset($query['endDate']))
            - {{ $query['endDate'] }}
          @endif
        </strong>
        @endif
      </div>

      <div>
        تاريخ الطباعة: <strong>{{ now()->format('Y-m-d') }}</strong>
      </div>
    </div>

    <div class="details">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>اســم الـدورة</th>
            <th>نـوع الـدورة</th>
            <th>عدد الموظفين</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>الـوثـيـقـة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($courses as $course)
          <tr>
            <td>{{ $course->id }}</td>
            <td>{{ $course->name_course }}</td>
            <td>{{ $course->course_type }}</td>
            <td>
              {{ $course->employees->count() }}
            </td>
            <td>{{ $course->from_date }}</td>
            <td>{{ $course->to_date }}</td>
            <td>
              @if ($course->files->count() > 0)
                ✔️
              @else
                -
              @endif
            </td>
            <td>{{ $course->notes }}</td>
          </tr>
          @endforeach

          <tr class="summary">
            <td colspan="4">إجمالي العدد</td>
            <td colspan="4" style="color: green;">{{ $courses->count() }}</td>
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
