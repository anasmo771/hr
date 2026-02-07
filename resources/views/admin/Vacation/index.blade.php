@extends('admin.layout.master')

@section('title')
  <title>سجل الإجازات</title>
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
              <li class="breadcrumb-item" aria-current="page">سجل الإجازات</li>
            </ul>
          </div>
          <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div class="page-header-title">
              <h2 class="mb-0">سجل الإجازات</h2>
            </div>
            <div>
              <a href="{{ route('vacations.createNew') }}" class="btn btn-primary">
                إضافة إجازة جديدة
              </a>
              <a href="{{ route('vacation.print') }}" target="_blank" class="btn btn-primary">طباعة</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-12">
        <div class="card glass-card">
          <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr class="text-center">
                  <th>#</th>
                  <th>الموظف</th>
                  <th>نوع الإجازة</th>
                  <th>تاريخ البداية</th>
                  <th>تاريخ المباشرة</th>
                  <th>نهاية فعلية</th>
                  <th>الأيام المحتسبة</th>
                  <th>الحالة</th>
                  <th>عدّ تنازلي</th>
                  <th>إجراءات</th>
                </tr>
              </thead>
              <tbody>
                @php use Carbon\Carbon; @endphp
                @forelse($vacations as $vac)
                  @php
                    $start   = $vac->start_date ? Carbon::parse($vac->start_date) : null;
                    $end     = $vac->end_date ? Carbon::parse($vac->end_date) : null;
                    $actual  = $vac->actual_end_date ? Carbon::parse($vac->actual_end_date) : null;
                    $today   = Carbon::today();
                    $isDuring = $vac->accept && $start && $end && !$actual
                              && $start->toDateString() <= $today->toDateString()
                              && $today->toDateString() <= $end->toDateString();
                    $daysRemaining = $end ? $today->diffInDays($end, false) : null;
                  @endphp
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                      @if(isset($vac->employee->person->name))
                        <a href="{{ route('vacations.show', [$vac->emp_id]) }}">
                          {{ $vac->employee->person->name }}
                        </a>
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center">{{ $vac->type ?? '-' }}</td>
                    <td class="text-center">{{ $start ? $start->format('Y-m-d') : '-' }}</td>
                    <td class="text-center">{{ $end ? $end->format('Y-m-d') : '-' }}</td>
                    <td class="text-center">{{ $actual ? $actual->format('Y-m-d') : '-' }}</td>
                    <td class="text-center">{{ is_null($vac->days) ? '-' : (int)$vac->days }}</td>
                    <td class="text-center">
                      @if($vac->accept)
                        <span class="badge bg-primary">معتمدة</span>
                      @else
                        <span class="badge bg-warning text-dark">بانتظار الاعتماد</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($actual)
                        انتهت فعليًا
                      @elseif($end)
                        @if($daysRemaining >= 0)
                          {{ $daysRemaining }} يوم
                        @else
                          انتهت منذ {{ abs($daysRemaining) }} يوم
                        @endif
                      @else
                        -
                      @endif
                    </td>

                    <td class="text-center">
                      {{-- اعتماد + ملف موافقة (إن لم تكن معتمدة) --}}
                      @can('vacation-approve')
                        @if(!$vac->accept)
                          <form action="{{ route('vacations.update') }}" method="POST" enctype="multipart/form-data" class="d-inline-flex gap-2 align-items-center">
                            @csrf
                            <input type="hidden" name="id" value="{{ $vac->id }}">
                            <label title="إرفاق ملف" class="btn btn-sm btn-outline-secondary mb-0">
                              <i class="fa-solid fa-file-arrow-up"></i>
                              <input type="file" name="file" class="d-none" onchange="this.closest('form').submit()">
                            </label>
                            <button type="submit" class="btn btn-sm btn-outline-success" title="اعتماد الأجازة">
                              <i class="fa-solid fa-calendar-check"></i>
                            </button>
                          </form>
                        @endif
                      @endcan

                      {{-- الرجوع من الإجازة أثناء الفترة --}}
                      @can('vacation-approve')
                        @if($isDuring)
                          <a href="{{ route('endVecation', [$vac->id]) }}"
                            title="الرجوع من الإجازة"
                            class="btn btn-sm btn-outline-warning"
                            onclick="return confirm('تأكيد: تسجيل الرجوع من الإجازة اليوم؟ سيتم احتساب الأيام الفعلية فقط.');">
                            <i class="fa-solid fa-person-arrow-down-to-line"></i>
                          </a>
                        @endif
                      @endcan

                      {{-- تعديل --}}
                      @can('vacation-edit')
                        <a href="{{ route('vacations.edit', $vac->id) }}" title="تعديل" class="btn btn-sm btn-outline-primary">
                          <i class="fa-solid fa-pen"></i>
                        </a>
                      @endcan

                      {{-- حذف --}}
                      @can('vacation-delete')
                        <form action="{{ route('vacations.destroy', $vac->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الإجازة؟');">
                          @csrf
                          @method('DELETE')
                          <button title="حذف" type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-trash"></i>
                          </button>
                        </form>
                      @endcan
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center" colspan="10">لا توجد بيانات.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          @if(method_exists($vacations, 'links'))
            <div class="card-footer">
              {{ $vacations->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
  </div>
</div>
@endsection
