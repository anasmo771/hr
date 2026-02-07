


            @extends('admin.layout.master')

            @section('title')
                <title> دورات الـمـوظــف   </title>
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
                                            <li class="breadcrumb-item" aria-current="page">دورات الـمـوظــف </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <div class="page-header-title">
                                            <h2 class="mb-0">دورات الـمـوظــف <a href="{{ route('EmployeeDetails', [$emp->id]) }}"><span
                                                style="color: blue;">{{ $emp->person->name }}</span></a></h2>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-ms-6">
                                                <a href="{{route('courses.createNew')}}" class="btn btn-primary" style="color: white; float: left;">اضـافـة دوراة </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [ breadcrumb ] end -->

                        @include('admin.layout.validation-messages')

                        <!-- [ Main Content ] start -->





                        @if($courses->count() > 0)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">


                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover" id="tab1">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 70px;">#</th>
                                                        <th scope="col">اســم الـدورة</th>
                                                        <th scope="col">نـوع الـدورة</th>
                                                        <th scope="col">الموظفين</th>
                                                        <th scope="col">تاريخ البداية</th>
                                                        <th scope="col">تاريخ النهاية</th>
                                                        <th scope="col">النتيجة</th>
                                                        <th scope="col" class="text-center">الـوثـيـقـة</th>
                                                        <th scope="col">ملاحظات</th>
                                                        <th scope="col">المنشئ</th>
                                                        <th scope="col" class="lastR text-center"  style="">الاجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($courses as $index => $course)
                                                    <tr>
                                                        <td class="text-center">{{ $index+1 }} </td>

                                                        <td>{{$course->name_course}}</td>
                                                        <td>{{$course->course_type}}</td>

                                                        <td>
                                                            @if($course->employees->count() > 0)
                                                                <a href=".showSections" data-bs-toggle="modal"
                                                                    onclick="showSections({{ json_encode($course->name_course) }},{{ json_encode($course->employees) }})"
                                                                    class="text-primary">{{$course->employees->count()}}
                                                                    <i class="fa-solid fa-user"></i>
                                                                </a>
                                                            @else
                                                                {{$course->employees->count()}}
                                                            @endif

                                                        </td>

                                                        <td>{{$course->from_date}}</td>
                                                        <td>{{$course->to_date}}</td>
                                                        <td>{{$course->employees->where('emp_id', $emp->id)->first()->result}}</td>

                                                        @if ($course->files->count() > 0)
                                                        <td class="text-center">
                                                            <a href=".showFile" data-bs-toggle="modal"
                                                                onclick="showItem({{ $course->files }})"
                                                                class="text-primary"><i class="bx bx-file"
                                                                    style="font-size:25px;margin-top:5px"></i></a>
                                                        </td class="text-center">
                                                    @else
                                                    <td class="text-center"> - </td>
                                                    @endif

                                                        <td>{{$course->notes}}</td>

                                                        @if($course->user)
                                                            <td>{{$course->user->name}}</td>
                                                        @else
                                                        <td>-</td>
                                                        @endif
                                                    <td class="text-center col-lg-2">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item px-2" id="edit">
                                                                <a href="{{ route('courses.edit', [$course->id]) }}" class="btn btn-sm btn-primary" title="تعديل">
                                                                    <i class="mdi mdi-pencil"></i>
                                                                </a>
                                                            </li>

                                                            {{-- زر الحذف (معلق حالياً) --}}
                                                            {{--
                                                            <li class="list-inline-item px-2" id="delete">
                                                                <form method="POST" action="{{ route('courses.destroy', [$course->id]) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                                        <i class="mdi mdi-delete"></i>
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

                                            @if(isset($query))
                                            {{ $courses->appends($query)->links('pagination::bootstrap-4') }}
                                          @else
                                          {{ $courses->links('pagination::bootstrap-4') }}
                                          @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row" style="padding-bottom: 40px;">
                            <div class="col-lg-12">
                                <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد دورات</h4>
                            </div>
                        </div>
                        @endif



                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <div class="row" dir="rtl">
                    <div class="col-12">

                        <div class="modal fade showSections" tabindex="-1" role="dialog"
                            aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl"> الموظفين في الدورة
                                            <span id="arrName" style="color: blue"></span> </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="print">

                                        <div dir="rtl">
                                            <div class="table-responsive" dir="rtl">

                                                <table class="table table-hover table-center">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-center"> الموظف</th>
                                                            <th class="text-center"> النتيجة</th>
                                                            <th class="text-center"> ملاحظة</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tbody id="bodyrow">


                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="col-12 text-start">
                                                <button type="submit" data-bs-dismiss="modal" class="btn btn-danger">إغلاق</button>
                                                {{-- <a href="" onclick="printPageArea()">  <button style="width: 150px" type="submit" class="btn btn-primary" >طباعة</button></a> --}}
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
                }

function showSections(name, employees) {

console.log(employees);
var table = document.getElementById("bodyrow");
table.innerHTML = "";
for (var i = 0; i < employees.length; i++) {
    if (top) {
        var row = table.insertRow(-1);
    } else {
        var row = table.insertRow();
    }
    // (B3) INSERT CELLS
    var cell = row.insertCell();
    cell.innerHTML = employees[i]['emp']['name'];
    cell.style.textAlign = "center";

    var cell = row.insertCell();
    cell.innerHTML = employees[i]['result'];
    cell.style.textAlign = "center";

    var cell = row.insertCell();
    if (typeof employees[i]['notes'] === "undefined") {
    cell.innerHTML = "-";
} else {
    cell.innerHTML = employees[i]['notes'];
}

    cell.style.textAlign = "center";
}

// document.getElementById("arrFileId").value = arr['id'];
document.getElementById("arrName").innerHTML = name;
// var text = "{{ asset(Storage::url('')) }}";
// document.getElementById("link").src = text+arr['file'];

}



    function exportData(type,name)
    {

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
