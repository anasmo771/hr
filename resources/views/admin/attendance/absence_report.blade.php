@extends('admin.layout.master')

@section('title')
    <title>تقرير الغياب السنوي</title>
@endsection

@section('content')
<div class="pc-container">
  <div class="pc-content">
    <div class="card">
      <div class="card-body">
        <h4 class="mb-4">تقرير الغياب السنوي للموظف: {{ $employee->person->name ?? 'بدون اسم' }}</h4>

        <form method="GET" action="{{ route('attendance.absence.report', ['employee' => request()->route('employee')])
             }}" class="row g-3 align-items-end mb-4">
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            
            <div class="col-md-4">
                <label for="year" class="form-label">اختر السنة:</label>
                <select name="year" id="year" class="form-select" required>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">عرض التقرير</button>
            </div>
        </form>

        @if(isset($report))
          <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>الشهر</th>
                        <th>عدد أيام الحضور</th>
                        <th>عدد أيام الغياب</th>
                        <th>عدد أيام التأخير</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report as $month => $data)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ $data['present'] }}</td>
                        <td>{{ $data['absent'] }}</td>
                        <td>{{ $data['late'] }}</td>
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
