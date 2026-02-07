@extends('admin.layout.master')

@section('title')
<title>تـكـلـيـف الموظف {{$employee->person->name}}</title>
@endsection

@section('css')
<style>
  .onlyTable { counter-reset: rowNumber; }
  .onlyTable tr { counter-increment: rowNumber; }
  .onlyTable tr td:first-child::before {
    content: counter(rowNumber);
    min-width: 1em;
    margin-right: 0.5em;
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
              <li class="breadcrumb-item" aria-current="page">التـكـلـيـف</li>
            </ul>
          </div>
          <div class="col-6">
            <div class="page-header-title">
              <h2 class="mb-0">
                تـكـلـيـف الـمـوظـف
                <a href="{{ route('EmployeeDetails', [$employee->id]) }}"><span style="color: blue;">{{$employee->person->name}}</span></a>
              </h2>
            </div>
          </div>
          @if(!$employee->startout_data)
          <div class="col-6">
            <div class="text-sm-end">
              <a href="{{route('createTask',['id'=>$employee->id])}}" class="btn btn-primary" style="color: white; float: left;">إضـــافــة تـكـلـيـف</a>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    @if($tasks->count() > 0)
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle table-nowrap table-hover">
                <thead class="table-light">
                  <tr>
                    <th class="text-center" style="width: 70px;">#</th>
                    <th class="text-center">اسم الموظف</th>
                    <th class="text-center">السبب</th>
                    <th class="text-center">المصدر</th>     <!-- task_res -->
                    <th class="text-center">الملاحظات</th>  <!-- note -->
                    <th class="text-center">الوثيقة</th>
                    <th class="text-center">التاريخ</th>
                    <th class="text-center">المنشئ</th>
                    <th class="text-center">الإجراءات</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tasks as $index => $vac)
                  <tr>
                    <td class="text-center">{{ $index+1 }}</td>
                    <td class="text-center">{{ $vac->emp->person->name ?? '-' }}</td>
                    <td class="text-center">{{ $vac->title }}</td>

                    <!-- مصدر القرار -->
                    <td class="text-center">{{ $vac->task_res ?? '-' }}</td>

                    <!-- الملاحظات -->
                    <td class="text-center">{{ $vac->note ?? '-' }}</td>

                    @if ($vac->files->count() > 0)
                      <td class="text-center">
                        <a href=".showFile" data-bs-toggle="modal"
                           onclick="showItem({{ $vac->files }})"
                           class="text-primary">
                           <i class="bx bx-file" style="font-size:25px;margin-top:5px"></i>
                        </a>
                      </td>
                    @else
                      <td class="text-center"> - </td>
                    @endif

                    <td class="text-center">{{ $vac->date }}</td>
                    <td class="text-center">{{ optional($vac->user)->name ?? '-' }}</td>

                    <td class="text-center">
                      <ul class="list-inline mb-0">
                        <li class="list-inline-item px-2">
                          <a href="{{ route('tasks.edit', [$vac->id]) }}" class="btn btn-sm btn-primary" title="تعديل">
                            <i class="mdi mdi-pencil"></i>
                          </a>
                        </li>
                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                          <li class="list-inline-item px-2">
                            <form method="POST" action="{{ route('tasks.destroy', [$vac->id]) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="d-inline-block">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                <i class="mdi mdi-delete"></i>
                              </button>
                            </form>
                          </li>
                        @endif
                      </ul>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <ul class="pagination pagination-rounded justify-content-center mb-2">
              @if(isset($query))
                {{ $tasks->appends($query)->links('pagination::bootstrap-4') }}
              @else
                {{ $tasks->links('pagination::bootstrap-4') }}
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
    @else
      <div class="row">
        <div class="col-lg-12">
          <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد التـكـلـيـف للــمــوظــف</h4>
        </div>
      </div>
    @endif
  </div>
</div>

<!-- Modal عرض الملفات -->
<div class="row" dir="rtl">
  <div class="col-12">
    <div class="modal fade showFile" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl"> صورة من الوثيقة <span id="arrName"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="print">
            <div dir="rtl">
              <div class="table-responsive" dir="rtl">
                <table class="table table-hover table-center">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">عرض</th>
                      <th class="text-center">تنزيل</th>
                    </tr>
                  </thead>
                  <tbody id="bodyrow"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
function showItem(images) {
  var table = document.getElementById("bodyrow");
  table.innerHTML = "";
  for (var i = 0; i < images.length; i++) {
    var row = table.insertRow(-1);

    var cell = row.insertCell();
    cell.innerHTML = " ملف " + (i + 1);
    cell.style.textAlign = "center";

    cell = row.insertCell();
    cell.innerHTML = "<a href='/storage/" + images[i]['path'] +
      "' target='_blank' class='text-primary'><i class='bx bx-show' style='font-size:25px;margin-top:5px'></i></a>";
    cell.style.textAlign = "center";

    cell = row.insertCell();
    var idd = images[i]['id'];
    cell.innerHTML = "<a href='/downloadFile/" + idd +
      "' class='text-primary'><i class='bx bx-download' style='font-size:25px;margin-top:5px'></i></a>";
    cell.style.color = "green";
    cell.style.textAlign = "center";
  }
}
</script>
@endsection
