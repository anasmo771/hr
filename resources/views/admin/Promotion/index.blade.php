@extends('admin.layout.master')

@section('title')
  <title>الترقيات</title>
@endsection

@section('css')
<style>
  .card-shadow { box-shadow:0 6px 18px rgba(0,0,0,.06); border-radius:12px; }
  .table thead th { text-align:center; position:sticky; top:0; background:#fff; z-index:5; }
  .table tbody td { vertical-align: middle; text-align:center; }
  .scroll-box { max-height: calc(48px * 11); overflow-y:auto; overflow-x:hidden; border-radius:8px; }
  .actions { display:flex; gap:.4rem; justify-content:center; align-items:center; flex-wrap:wrap; }
  .search-bar { max-width:360px; }
  .badge-soft { padding:.35rem .6rem; border-radius:8px; }
  .badge-soft-info { background:#e9f4ff; color:#1273de; }
</style>
@endsection

@section('content')
<div class="pc-container">
  <div class="pc-content">

    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item" aria-current="page">الترقيات</li>
            </ul>
          </div>
          <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">الترقيات</h2>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    {{-- إشعارات الاستحقاق --}}
    <div class="card card-shadow mb-4">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
          <h4 class="mb-0">إشعارات الاستحقاق</h4>
          <div class="d-flex align-items-center gap-3">
            <span class="badge badge-soft-info">{{ count($eligibles ?? []) }} استحقاق</span>
            <input id="eligibles-filter" type="text" class="form-control form-control-sm search-bar" placeholder="بحث...">
          </div>
        </div>

        @if(!empty($eligibles))
          <div class="scroll-box">
            <div class="table-responsive mb-0">
              <table id="eligibles-table" class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>الموظف</th>
                    <th>نوع الاستحقاق</th>
                    <th>ملاحظة</th>
                    <th>إجراء</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($eligibles as $i => $row)
                    <tr>
                      <td>{{ $i+1 }}</td>
                      <td class="text-start">{{ $row['name'] }}</td>
                      <td>
                        @if($row['type']==='regular') ترقية نظامية
                        @elseif($row['type']==='exceptional') ترقية استثنائية
                        @elseif($row['type']==='acting') ندب على درجة
                        @else — @endif
                      </td>
                      <td>{{ $row['note'] }}</td>
                      <td>
                        <form method="POST" action="{{ route('promotion.quickStore') }}"
                              onsubmit="return confirm('تنفيذ ترقية {{ $row['type']==='regular'?'نظامية':'استثنائية' }} للموظف {{ $row['name'] }}؟');">
                          @csrf
                          <input type="hidden" name="emp_id" value="{{ $row['emp_id'] }}">
                          <input type="hidden" name="ptype" value="{{ $row['type'] }}">
                          <button type="submit" class="btn btn-sm btn-primary">تنفيذ الترقية</button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @else
          <div class="text-center text-muted py-3">لا توجد استحقاقات.</div>
        @endif
      </div>
    </div>

    {{-- الترقيات المسجلة --}}
    <div class="card card-shadow">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
          <h4 class="mb-0">الترقيات المسجلة</h4>
          <input id="promotions-filter" type="text" class="form-control form-control-sm search-bar" placeholder="بحث...">
        </div>

        <div class="scroll-box">
          <div class="table-responsive mb-0">
            <table id="promotions-table" class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>الموظف</th>
                  <th>النوع</th>
                  <th>القرار</th>
                  <th>الدرجة السابقة</th>
                  <th>الدرجة الجديدة</th>
                  <th>تاريخ المنح</th>
                  <th>أضيف بواسطة</th>
                  <th>إجراءات</th>
                </tr>
              </thead>
              <tbody>
                @forelse($promotions as $index => $p)
                  <tr>
                    <td>{{ $index+1 }}</td>
                    <td class="text-start">{{ $p->emp->person->name ?? '#'.$p->emp_id }}</td>
                    <td>
                      @if($p->type==='regular') نظامية
                      @elseif($p->type==='exceptional') استثنائية
                      @elseif($p->type==='acting') ندب على درجة
                      @else — @endif
                    </td>
                    <td>{{ $p->num ?? '—' }}</td>
                    <td>{{ $p->prev_degree }}</td>
                    <td>{{ $p->new_degree }}</td>
                    <td>{{ optional($p->date)->format('Y-m-d') ?? optional($p->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $p->user->name ?? '—' }}</td>
                    <td class="actions">
                      <a href="{{ route('promotion.edit', $p->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                      <form action="{{ route('promotion.destroy', $p->id) }}" method="POST" onsubmit="return confirm('حذف هذه الترقية؟');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="9" class="text-center text-muted">لا توجد بيانات.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

@section('script')
<script>
function attachFilter(inputId, tableId){
  const i = document.getElementById(inputId);
  const t = document.getElementById(tableId);
  if(!i||!t) return;
  i.addEventListener('input', function(){
    const q = this.value.trim().toLowerCase();
    [...t.tBodies[0].rows].forEach(tr=>{
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
}
attachFilter('eligibles-filter','eligibles-table');
attachFilter('promotions-filter','promotions-table');
</script>
@endsection
