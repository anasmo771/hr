@extends('admin.layout.master')

@section('title')
  <title>ترقيات الموظف</title>
@endsection

@section('content')
<div class="pc-container">
  <div class="pc-content">

    <!-- breadcrumb -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item">الترقيات</li>
              <li class="breadcrumb-item active">
                ترقيات: <a href="{{ route('EmployeeDetails', [$emp->id]) }}">
                  <span style="color: blue;">{{ $emp->person->name ?? ('#'.$emp->id) }}</span>
                </a>
              </li>
            </ul>
          </div>
          <div class="col-md-12">
            <h2 class="mb-0">
              ترقيات الموظف: <a href="{{ route('EmployeeDetails', [$emp->id]) }}">
                <span style="color: blue;">{{ $emp->person->name ?? ('#'.$emp->id) }}</span>
              </a>
            </h2>
          </div>
            <div class="d-flex justify-content-start mb-3">
                <a href="{{ route('createPromotion', $emp->id) }}" class="btn btn-primary">
                    إضافة ترقية
                </a>
            </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    <div class="card">
      <div class="card-body" id="print2">

        @if($promotions->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover table-nowrap align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th>#</th>
                  <th>نوع الترقية</th>
                  <th>رقم القرار</th>
                  <th>الدرجة السابقة</th>
                  <th>الدرجة الجديدة</th>
                  <th>تاريخ منح الترقية</th>
                  <th>أضيف بواسطة</th>
                  <th>إجراءات</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @php
                  $typeMap = ['regular'=>'نظامية','exceptional'=>'استثنائية','acting'=>'ندب على درجة'];
                @endphp
                @foreach($promotions as $i => $p)
                  <tr>
                    <td>{{ $i + 1 + ($promotions->currentPage()-1)*$promotions->perPage() }}</td>
                    <td>{{ $typeMap[$p->type] ?? $p->type }}</td>
                    <td>{{ $p->num ?? '—' }}</td>
                    <td>{{ $p->prev_degree }}</td>
                    <td>{{ $p->new_degree }}</td>
                    <td>{{ optional($p->date)->format('Y-m-d') ?? optional($p->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $p->user->name ?? '—' }}</td>
                    <td class="text-center">
                      <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                          <a href="{{ route('promotion.edit', $p->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                        </li>
                        <li class="list-inline-item">
                          <form action="{{ route('promotion.destroy', $p->id) }}" method="POST"
                                onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                          </form>
                        </li>
                      </ul>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-3 d-flex justify-content-center">
            {{ $promotions->links('pagination::bootstrap-4') }}
          </div>
        @else
          <div class="text-center text-muted py-3">لا توجد ترقيات مسجلة لهذا الموظف.</div>
        @endif

      </div>
    </div>

  </div>
</div>
@endsection
