
@extends('admin.layout.master')


@section('title')
<title> عـلاوة الـمـوظــفــيــن </title>
@endsection

@section('css')
    <style>
        .onlyTable {
            counter-reset: rowNumber;
        }

        .onlyTable tr {
            counter-increment: rowNumber;
        }

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
                                <li class="breadcrumb-item"><a href="#">عـلاوة الـمـوظــف <a href="{{ route('EmployeeDetails', [$emp->id]) }}"><span
                                    style="color: blue;">{{ $emp->person->name }}</span></a>   </a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h2 class="mb-0">عـلاوة الـمـوظــف <a href="{{ route('EmployeeDetails', [$emp->id]) }}"><span
                                    style="color: blue;">{{ $emp->person->name }}</span></a> </h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-ms-6">
                                <a href="{{route('createBouns',[$emp->id])}}" class="btn btn-primary" style="color: white; float: left;">اضـافـة عـلاوة</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->
                        @if($bouns->count() > 0)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    {{-- <form action="{{route('searchEmployeebouns')}}" method="get" class="p-3">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group m-0">
                                                    <div class="input-group">
                                                       <input type="text" class="form-control" name="name" placeholder=" البحث عن موظف " aria-label="Recipient's username">
                                                        <div class="input-group-append mr-4">
                                                          <button class="btn btn-primary mr-4 " type="submit"><i class="mdi mdi-magnify"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form> --}}

                                    <div class="card-body">
                                        {{-- <div class="col-lg-4">
                                            <div class="form-group m-0">
                                                <div class="btn" role="group" aria-label="Basic example">
                                                    <a href="#"  type="button" onclick="printDiv()" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-file me-1"></i> طـبـاعـة</a>
                                                </div>
                                            </div>
                                        </div> --}}

                                <div class="card-body" id="print2">


                                    <div class="table-responsive">

                                            <table class="table align-middle table-nowrap table-hover" id="tab1">
                                            <!-- <div class="row">
                                            <div class="col-12">
                                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                                </div>
                                            </div>
                                        </div> -->
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center" scope="col" style="width: 70px;">#</th>
                                                        <th class="text-center" scope="col">اســم الــموظــف</th>
                                                        <th class="text-center" scope="col">رقــم الــعــلاوة</th>
                                                    <th class="text-center" scope="col"> الـدرجــة الـوظـيـفـيـة</th>
                                                    <th class="text-center" scope="col">تـاريـخ الاسـتـحـقـاق</th>
                                                    <th scope="col" class="text-center">تـاريـخ آخـر عـلاوة</th>
                                                    <th scope="col" class="text-center">الـوثـيـقـة</th>
                                                    <th class="text-center" scope="col">مـنـشـئ الـعـلاوة</th>
                                                    <th scope="col"class="lastR text-center" id="ch">الاجراءات
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($bouns as $index => $fe)
                                                    <tr>
                                                        <td class="text-center">{{ $index+1 }} </td>
                                                        <td class="text-center">
                                                            <!-- <a href="#"> -->
                                                            <h5 class="font-size-14 mb-1">{{ $fe->emp->person->name }}</h5>
                                                            </a>
                                                        </td>

                                                        <td class="text-center">{{ $fe->bonus_num }}</td>
                                                        <td class="text-center">{{ $fe->degree }}</td>
                                                        <td class="text-center">{{ $fe->date }}</td>
                                                        <td class="text-center">{{ $fe->last_date }}</td>

                                                        @if ($fe->files->count() > 0)
                                                            <td class="text-center">
                                                                <a href=".showFile" data-bs-toggle="modal"
                                                                    onclick="showItem({{ $fe->files }})"
                                                                    class="text-primary"><i class="bx bx-file"
                                                                        style="font-size:25px;margin-top:5px"></i></a>
                                                            </td class="text-center">
                                                        @else
                                                        <td class="text-center"> - </td>
                                                        @endif

                                                        <td class="text-center">{{$fe->user->name}}</td>
                                                        <td class="text-center col-lg-2">

                                                           <ul class="list-inline mb-0 text-center">
    <li class="list-inline-item px-2" id="edit">
        <a href="{{ route('bouns.edit', [$fe->id]) }}" class="btn btn-sm btn-primary" title="تعديل">
            <i class="mdi mdi-pencil font-size-18"></i>
        </a>
    </li>

    {{-- 
    <li class="list-inline-item px-2" id="delete">
        <form method="POST" action="{{ route('bouns.destroy', [$fe->id]) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="d-inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="حذف" style="border:none; background:none;">
                <i class="mdi mdi-delete font-size-18"></i>
            </button>
        </form>
    </li>
    --}}
</ul>


                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <ul class="pagination pagination-rounded justify-content-center mb-2">
                                            @if (isset($query))
                                            {{ $bouns->appends($query)->links('pagination::bootstrap-4') }}
                                        @else
                                            {{ $bouns->links('pagination::bootstrap-4') }}
                                        @endif
                                    </ul>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد عــلـاوات</h4>
                            </div>
                        </div>
                        @endif
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

@endsection

@section('modals')
<div class="row" dir="rtl">
    <div class="col-12">

        <div class="modal fade showBouns"  tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5  style="color: black;"  class="modal-title" id="orderdetailsModalLabel">الـتـقـيـيـمـات</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" dir="rtl" id="print22">
                            <div class="col-12">
                                <h5  style="color: black;" class="mb-2" dir="rtl">إسم الزبون : <span style="color: blue;" dir="rtl" id="orderName"></span></h5>
                            </div>
                        </div>
                        <div dir="rtl">
                            <div class="table-responsive" dir="rtl" >
                                <table class="table align-middle table-nowrap table-check">
                                    <thead class="table-light">
                                        <tr  style="color: black;">
                                            <th class="text-center">كفائة الاداء والصفات الشخصية</th>
                                            <th class="text-center">درجة النهاية العظمي </th>
                                            <th class="text-center">درجة كفاية الموظف</th>
                                            <th class="text-center">درجة الكفاية المعدلة</th>
                                            <th class="text-center">مبررات تعديل درجة كفاية الموظف</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyrow23">

                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 text-start">
                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">إغلاق</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="row" dir="rtl">
    <div class="col-12">

        <div class="modal fade showFile" tabindex="-1" role="dialog"
            aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
            {{-- <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> --}}
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl"> صورة من الوثيقة
                            <span id="arrName"></span> </h5>
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
                                            <th class="text-center"> عرض</th>
                                            <th class="text-center">تنزيل </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tbody id="bodyrow">

                                    </tbody>
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
        function printDiv() {

        $('#print2 tr').find('th:last-child, td:last-child').remove();
        // $('#print2 tr').find('th:last-child, td:last-child').remove();
        var printContent = document.getElementById("print2").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();

      }



      function showItem(images) {
                    var table = document.getElementById("bodyrow");
                    console.log(images);
                    table.innerHTML = "";
                    for (var i = 0; i < images.length; i++) {
                        if (top) {
                            var row = table.insertRow(-1);
                        } else {
                            var row = table.insertRow();
                        }
                        // (B3) INSERT CELLS
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
                        var idd = images[i]['id'];
                        cell.style.color = "green";
                        cell.style.textAlign = "center";
                    }
                    // document.getElementById("arrFileId").value = arr['id'];
                    // document.getElementById("arrName").innerHTML = arr['name'];
                    // var text = "{{ asset(Storage::url('')) }}";
                    // document.getElementById("link").src = text+arr['file'];
                }


$(document).on("click", "#showBouns", function(){
    var result = $(this).data('result');
    var name = $(this).data('name');

    console.log(name);
    console.log(result);
    document.getElementById("orderName").innerHTML = name;
    var table = document.getElementById("bodyrow23");
    table.innerHTML = "";
        if (top) { var row = table.insertRow(-1); }
        else { var row = table.insertRow(); }
        cell = row.insertCell();
        cell.innerHTML = "أداء الواجب";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = "45";
        cell.style.textAlign = "center";
        cell.style.fontWeight = "bold";
        cell = row.insertCell();
        cell.innerHTML = result["grade11"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade12"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["textGrade1"];
        cell.style.textAlign = "center";

        if (top) { var row = table.insertRow(-1); }
        else { var row = table.insertRow(); }
        cell = row.insertCell();
        cell.innerHTML = "المواظبة علي العمل";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = "15";
        cell.style.fontWeight = "bold";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade21"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade22"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["textGrade2"];
        cell.style.textAlign = "center";

        if (top) { var row = table.insertRow(-1); }
        else { var row = table.insertRow(); }
        cell = row.insertCell();
        cell.innerHTML = "القدرات والأستعداد الذاتي";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = "20";
        cell.style.fontWeight = "bold";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade31"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade32"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["textGrade3"];
        cell.style.textAlign = "center";

        if (top) { var row = table.insertRow(-1); }
        else { var row = table.insertRow(); }
        cell = row.insertCell();
        cell.innerHTML = "العلاقات الأنسانية";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = "20";
        cell.style.fontWeight = "bold";
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade41"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["grade42"];
        cell.style.textAlign = "center";
        cell = row.insertCell();
        cell.innerHTML = result["textGrade4"];
        cell.style.textAlign = "center";
});


      </script>

@endsection
