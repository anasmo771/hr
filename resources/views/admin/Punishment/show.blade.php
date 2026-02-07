@extends('admin.layout.master')

@section('title')
<title> سـجـل عـقـوبـات الـمـوظــف </title>
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
              <li class="breadcrumb-item" aria-current="page">العـقـوبـات</li>
            </ul>
          </div>
          <div class="col-6">
            <div class="page-header-title">
              <h2 class="mb-0">
                عـقـوبـات الـمـوظـف
                <a href="{{ route('EmployeeDetails', [$emp->id]) }}">
                  <span style="color: blue;">{{ $emp->person->name }}</span>
                </a>
              </h2>
            </div>
          </div>
          <div class="col-6">
            <div class="text-sm-end">
              <a href="{{ route('createPunishment',[$emp->id]) }}"
                 class="btn btn-primary" style="color: white; float: left;">
                اضـافـة عـقـوبـة
              </a>
            </div>
          </div><!-- end col-->
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    @if($punshes->count() > 0)
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">

              <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover" id="tab1">
                  <thead class="table-light text-center">
                    <tr>
                      <th scope="col" style="width:70px;">#</th>
                      <th scope="col">نوع العقوبة</th>
                      <th scope="col">تاريخ العقوبة</th>
                      <th scope="col">رقم الكتاب</th>
                      <th scope="col">الرقم الإشاري</th>
                      <th scope="col">اسم من توصي بالعقوبة</th>
                      <th scope="col" class="text-center">الـوثـيـقـة</th>
                      <th scope="col">ملاحظات</th>
                      <th scope="col">المنشئ</th>
                      <th scope="col">الاجراءات</th>
                    </tr>
                  </thead>

                  <tbody class="onlyTable text-center">
                    @foreach($punshes as $index => $punsh)
                      <tr>
                        {{-- ترقيم حسب الصفحة --}}
                        <td class="text-center">{{ ($punshes->firstItem() ?? 1) + $index }}</td>

                        <td>{{ $punsh->pun_type }}</td>

                        <td>{{ $punsh->pun_date }}</td>

                        <td>{{ $punsh->book_num }}</td>

                        <td>{{ $punsh->index }}</td>

                        <td>{{ $punsh->penaltyName }}</td>

                        @if ($punsh->files->count() > 0)
                          <td class="text-center">
                            <a href=".showFile" data-bs-toggle="modal"
                               onclick='showItem(@json($punsh->files))'
                               class="text-primary">
                              <i class="bx bx-file" style="font-size:25px;margin-top:5px"></i>
                            </a>
                          </td>
                        @else
                          <td class="text-center">-</td>
                        @endif

                        <td>{{ $punsh->notes }}</td>

                        <td>{{ optional($punsh->user)->name ?? '-' }}</td>

                        <td class="text-center col-lg-2">
                          <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                              <a href="{{ route('punishments.edit', [$punsh->id]) }}"
                                 class="btn btn-sm btn-primary" title="تعديل">
                                <i class="mdi mdi-pencil"></i>
                              </a>
                            </li>
                            <li class="list-inline-item">
                              <form method="POST"
                                    action="{{ route('punishments.destroy', [$punsh->id]) }}"
                                    onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                  <i class="mdi mdi-delete"></i>
                                </button>
                              </form>
                            </li>
                          </ul>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <ul class="pagination pagination-rounded justify-content-center mb-2">
                @if(isset($query))
                  {{ $punshes->appends($query)->links('pagination::bootstrap-4') }}
                @else
                  {{ $punshes->links('pagination::bootstrap-4') }}
                @endif
              </ul>

            </div>
          </div>
        </div>
      </div>
    @else
      <div class="row" style="padding-bottom: 40px;">
        <div class="col-lg-12">
          <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد عـقـوبـات</h4>
        </div>
      </div>
    @endif

  </div> <!-- pc-content -->
</div> <!-- pc-container -->

{{-- Modal عرض الملفات --}}
<div class="row" dir="rtl">
  <div class="col-12">
    <div class="modal fade showFile" tabindex="-1" role="dialog"
         aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl">
              صورة من الوثيقة <span id="arrName"></span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    dir="rtl" aria-label="Close"></button>
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
          </div> <!-- /modal-body -->
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
    cell.innerHTML = "ملف " + (i + 1);
    cell.style.textAlign = "center";

    cell = row.insertCell();
    cell.innerHTML =
      "<a href='/storage/" + images[i]['path'] +
      "' target='_blank' class='text-primary'><i class='bx bx-show' style='font-size:25px;margin-top:5px'></i></a>";
    cell.style.textAlign = "center";

    cell = row.insertCell();
    var idd = images[i]['id'];
    cell.innerHTML =
      "<a href='/downloadFile/" + idd +
      "' class='text-primary'><i class='bx bx-download' style='font-size:25px;margin-top:5px'></i></a>";
    cell.style.color = "green";
    cell.style.textAlign = "center";
  }
}

// (اختياري) تصدير
function exportData(type,name) {
  $('#tab1 tr').find('th:last-child, td:last-child').remove();
  $('#tab1 tr').find('th:last-child, td:last-child').remove();
  $('#tab1 tr').find('th:first-child, td:first-child').remove();
  var data = document.getElementById('tab1');
  var file = XLSX.utils.table_to_book(data, {sheet: "الموظفين"});
  XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
  XLSX.writeFile(file, name+'.' + type);
  window.location.reload();
}
</script>
@endsection
