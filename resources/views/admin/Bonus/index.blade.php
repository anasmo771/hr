@extends('admin.layout.master')

@section('title')
  <title>العلاوات</title>
@endsection

@section('css')
<style>
  .card-shadow { box-shadow: 0 6px 18px rgba(0,0,0,.06); border-radius: 12px; }
  .table thead th { text-align:center; }
  .table tbody td { vertical-align: middle; text-align:center; }
  .btn-wide { min-width: 120px; }
  .search-bar { max-width: 360px; }

  /* الحاوية التي يتم التمرير بداخلها + تثبيت الرأس */
  .scroll-box {
    position: relative;   /* لازم للـ sticky */
    overflow-y: auto;
    overflow-x: hidden;
    border-radius: 8px;
  }
  .scroll-box table thead th {
    position: sticky;
    top: 0;
    z-index: 3;
    background-color: #fff !important; /* مهم لأن bootstrap يجعل الخلفية شفافة */
    box-shadow: 0 1px 0 rgba(0,0,0,.05);
  }

  .table .actions {
    display:flex; gap:.4rem; justify-content:center; align-items:center; flex-wrap:wrap;
  }
  /* احذف/تجاهل .scroll-box السابقة واستخدم هذا فقط */
  .limit-10{
    position: relative;          /* لازم للـ sticky */
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 8px;
  }
  .limit-10 table{ margin-bottom: 0; }

  .limit-10 thead th{
    position: sticky;
    top: 0;
    background: #fff !important; /* نفس لون بقية المحتوى */
    z-index: 4;
    box-shadow: 0 1px 0 rgba(0,0,0,.05);
  }
</style>
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
              <li class="breadcrumb-item" aria-current="page">العلاوات</li>
            </ul>
          </div>
          <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">العلاوات</h2>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <!-- ============ إشعارات الاستحقاق ============ -->
    <div class="row">
      <div class="col-12">
        <div class="card card-shadow mb-4">
          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
              <h4 class="mb-0">إشعارات الاستحقاق</h4>
              <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark">{{ count($eligibles ?? []) }} استحقاق متاح</span>
                <input id="eligibles-filter" type="text" class="form-control form-control-sm search-bar" placeholder="بحث في الإشعارات...">
              </div>
            </div>

            @if(!empty($eligibles) && count($eligibles))
              <div class="table-responsive limit-10">
                <table id="eligibles-table" class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>الموظف</th>
                    <th>تاريخ المباشرة</th>
                    <th>تاريخ الاستحقاق</th>     {{-- الجديد --}}
                    <th>تاريخ آخر علاوة</th>
                    <th>رقم السنة</th>
                    <th>التقدير (من تقرير الكفائة)</th>
                    <th>إجراء</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach($eligibles as $i => $row)
                    @php
                      // تاريخ المباشرة
                      $baseDate = $row['base_date'] ?? null;

                      // تاريخ الاستحقاق (الجديد)
                      $dueDate = null;
                      if (!empty($row['due_date'])) {
                        try { $dueDate = \Carbon\Carbon::parse($row['due_date'])->format('Y-m-d'); }
                        catch (\Throwable $e) { $dueDate = (string)$row['due_date']; }
                      }

                      // تاريخ آخر علاوة
                      $lastBonusDateVal = $row['last_bonus_date'] ?? null;
                      if (!$lastBonusDateVal) {
                        $lastBonusRow = ($bonuses ?? collect())
                          ->where('emp_id', $row['emp_id'])
                          ->sortByDesc(function($b){ return $b->bonus_date ?? $b->date ?? $b->created_at; })
                          ->first();
                        $lastBonusDateVal = $lastBonusRow ? ($lastBonusRow->bonus_date ?? $lastBonusRow->date ?? $lastBonusRow->created_at) : null;
                      }
                      $lastBonusDate = null;
                      if ($lastBonusDateVal) {
                        try { $lastBonusDate = \Carbon\Carbon::parse($lastBonusDateVal)->format('Y-m-d'); }
                        catch (\Throwable $e) { $lastBonusDate = (string)$lastBonusDateVal; }
                      }
                    @endphp

                    <tr>
                      <td>{{ $i+1 }}</td>
                      <td class="text-start">{{ $row['name'] }}</td>
                      <td>{{ $baseDate ?? '—' }}</td>
                      <td>{{ $dueDate ?? '—' }}</td>           {{-- الجديد بين المباشرة وآخر علاوة --}}
                      <td>{{ $lastBonusDate ?? '—' }}</td>
                      <td>{{ $row['years'] }}</td>
                      <td>{{ $row['estimate'] ?? '—' }}</td>
                      <td>
                        <form action="{{ route('bonuses.quickStore') }}" method="POST"
                              onsubmit="return confirm('تأكيد إضافة العلاوة للموظف {{ $row['name'] }}؟');">
                          @csrf
                          <input type="hidden" name="emp_id" value="{{ $row['emp_id'] }}">
                          <input type="hidden" name="due_date" value="{{ $row['due_date'] ?? now()->toDateString() }}">
                          <button type="submit" class="btn btn-sm btn-primary btn-wide">إضافة العلاوة</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>

                  </table>
              </div>
            @else
              <div class="text-center text-muted py-3">لا توجد استحقاقات حالية.</div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- ============ العلاوات المسجّلة ============ -->
    <div class="row">
      <div class="col-12">
        <div class="card card-shadow">
          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
              <h4 class="mb-0">العلاوات المسجّلة</h4>
              <input id="bonuses-filter" type="text" class="form-control form-control-sm search-bar" placeholder="بحث في العلاوات المسجّلة...">
            </div>

            <div class="table-responsive limit-10">
              <table id="bonuses-table" class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>الموظف</th>
                      <th>رقم العلاوة</th>
                      <th>تاريخ العلاوة</th>
                      <th>التقدير</th>
                      <th>أضيف بواسطة</th>
                      <th>إجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($bonuses as $index => $b)
                      @php
                        $dateCell = $b->bonus_date ?? $b->date ?? $b->created_at;
                        try { $dateCell = \Carbon\Carbon::parse($dateCell)->format('Y-m-d'); }
                        catch (\Throwable $e) { $dateCell = (string)$dateCell; }
                      @endphp
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">{{ $b->emp->person->name ?? ('#'.$b->emp_id) }}</td>
                        <td>{{ $b->bonus_num ?? '—' }}</td>
                        <td>{{ $dateCell ?? '—' }}</td>
                        <td>{{ $b->estimate ?? '—' }}</td>
                        <td>{{ $b->user->name ?? '—' }}</td>
                        <td class="actions">

                          <ul class="list-inline mb-0">
                          <!-- تعديل -->
                            <li class="list-inline-item">
                              <a href="{{ route('bouns.edit', $b->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i>
                              </a>
                            </li>
                          <!-- حذف -->
                            <li class="list-inline-item">
                              <form action="{{ route('bouns.destroy', $b->id) }}" method="POST"
                                onsubmit="return confirm('متأكد من حذف هذه العلاوة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                  <i class="fa-solid fa-trash"></i>
                                </button>
                              </form>
                            </li>
                          </ul> 
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted">لا توجد بيانات.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
            </div>

            {{-- لا Pagination --}}
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script>
  // فلترة بسيطة لكل جدول
  function attachFilter(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;

    input.addEventListener('input', function () {
      const q = this.value.trim().toLowerCase();
      const rows = table.tBodies[0].rows;
      for (let i = 0; i < rows.length; i++) {
        const rowText = rows[i].innerText.toLowerCase();
        rows[i].style.display = rowText.includes(q) ? '' : 'none';
      }
    });
  }

  // قيد العرض إلى 10 صفوف: (رأس + 10 × ارتفاع الصف)
  function clampTenRows(selector) {
    document.querySelectorAll(selector).forEach(wrap => {
      const table = wrap.querySelector('table');
      if (!table || !table.tBodies.length) return;

      const headRow = table.tHead ? table.tHead.rows[0] : null;
      const headH   = headRow ? headRow.getBoundingClientRect().height : 0;
      const firstRow = table.tBodies[0].rows[0];
      if (!firstRow) return;

      const rowH = firstRow.getBoundingClientRect().height || 48;
      const padding = 4;
      const target = Math.round(headH + rowH * 10 + padding);

      wrap.style.height = target + 'px';
      wrap.style.overflowY = 'auto';
      wrap.style.overflowX = 'hidden';
    });
  }

  // تشغيل
  attachFilter('eligibles-filter', 'eligibles-table');
  attachFilter('bonuses-filter', 'bonuses-table');

  function runClamp() { clampTenRows('.limit-10'); }
  window.addEventListener('load', runClamp);
  window.addEventListener('resize', runClamp);
  setTimeout(runClamp, 300);
</script>
@endsection
