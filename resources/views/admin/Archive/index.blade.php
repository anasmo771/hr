@extends('admin.layout.master')

@section('title')
    <title>الأرشـيـف الوظـيـفـي</title>
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
                                <li class="breadcrumb-item"><a href="#"> الأرشـيـف الوظـيـفـي</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="page-header-title">
                                <h2 class="mb-0">  الأرشـيـف الوظـيـفـي </h2>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-ms-3">
                                <a href=".orderdetailsModal" data-bs-toggle="modal"
                                    class="btn btn-primary" style="color: white; float: left;">إضـافـة أرشـيـف وظـيـفـي </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->




            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="col-sm-12">
                            <h2 dir="rtl">
                                <div role="group" aria-label="Basic radio toggle button group">

                                    @if ($page == 0)
                                        <a href="{{ route('archives.index', ['id' => 0]) }}" class="btn btn-primary m-1"
                                            style="border-radius:25px; min-width:115px;">الكل</a>
                                    @else
                                        <a href="{{ route('archives.index', ['id' => 0]) }}"
                                            class="btn btn-outline-primary m-1"
                                            style="border-radius:25px; min-width:115px;">الكل</a>
                                    @endif
                                    @foreach ($types as $type)
                                        @if ($page == $type->id)
                                            <a href="{{ route('archives.index', ['id' => $type->id]) }}"
                                                class="btn btn-primary m-1"
                                                style="border-radius:25px; min-width:115px;">{{ $type->name }}</a>
                                        @else
                                            <a href="{{ route('archives.index', ['id' => $type->id]) }}"
                                                class="btn btn-outline-primary m-1"
                                                style="border-radius:25px; min-width:115px;">{{ $type->name }}</a>
                                        @endif
                                    @endforeach

                                    <a href=".addType" data-bs-toggle="modal" class="btn btn-outline-primary m-1"
                                        style="border-radius:25px; min-width:40px;">+</a>

                                </div>
                            </h2>
                        </div>
                    </div>
                </div>
                <!-- end page title -->


                @if ($errors->any())
                    <div class="alert alert-danger" style=" font-family: cairo;">
                        <ul
                            style=" font-family: cairo;text-align: center; color: darkred; list-style-type: none; font-size: 15px; ">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div style=" font-family: cairo;text-align: center; color: darkred; font-size: 15px;"
                        class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div style=" font-family: cairo;text-align: center; font-size: 15px; color: green;"
                        class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif




                @if ($archives->count() == 0)
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد أرشـيـف وظـيـفـي</h4>
                        </div>
                    </div>
                @else
                                       
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-3" dir="rtl">
                                        <div class="search-box me-2 mb-2">
                                            <form action="{{ route('admin.searchArchuves') }}" method="get">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <input type="hidden" name="type" value="{{ $page }}">
                                                    @if (isset($query))
                                                        <input type="text" name="search" value="{{ $query['search'] }}" class="form-control rounded-pill" placeholder="البحث عن أرشيف">
                                                    @else
                                                        <input type="text" name="search" class="form-control rounded-pill" placeholder="البحث عن أرشيف">
                                                    @endif
                                                    <button class="btn btn-outline-primary rounded-end-pill px-3" type="submit">
                                                        <i class="mdi mdi-magnify"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <!-- end col-->


                                <div style="color: black" class="table-responsive" dir="rtl">
                                    <table class="table align-middle table-nowrap table-check">
                                        <thead class="table-light">
                                            <tr style="color: black">

                                                <th class="text-center"> #</th>
                                                <th class="text-center">الجهة التابعة</th>
                                                <th class="text-center">الموظف</th>
                                                <th class="text-center">الهيئة</th>
                                                <th scope="col" class="text-center">الـوثـيـقـة</th>
                                                <th class="text-center">وصف</th>
                                                <th class="text-center">التاريخ</th>
                                                <th class="text-center">التعديل </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                </div>
                                </div>
                            </div>
                        </div>
                                            @php
                                                $i = 1;

                                                $j = 0;
                                            @endphp
                                            @foreach ($archives as $arr)
                                                <tr style="color: black" class="text-center">
                                                    <td style="color: black">{{ $i++ }}</td>
                                                    <td>{{ $arr->name }}</td>
                                                    <td>{{ $arr->emp->person->name }}</td>
                                                    <td>{{ $arr->type->name }}</td>
                                                    {{-- <td>
                                                   <a href="#"  class="text-primary">
                                                     <i class="bx bx-file-blank"  style="font-size:25px;margin-top:5px" ></i>
                                                    </a>
                                                        </td> --}}
                                                    @if ($arr->files->count() > 0)
                                                        <td>
                                                            <a href=".showFile" data-bs-toggle="modal"
                                                                onclick="showItem({{ $arr->files }})"
                                                                class="text-primary"><i class="bx bx-file"
                                                                    style="font-size:25px;margin-top:5px"></i></a>
                                                        </td>
                                                    @else
                                                        <td> - </td>
                                                    @endif

                                                    @if ($arr->desc)
                                                        <td>
                                                            {{ $arr->desc }}
                                                        </td>
                                                    @else
                                                        <td> - </td>
                                                    @endif


                                                    <td>
                                                        {{ $arr->date }}
                                                    </td>
                                                    <td class="text-center">
    <a href=".editArchive" data-bs-toggle="modal" onclick="editArchive({{ $arr }})" class="btn btn-sm btn-primary me-2" title="تعديل">
        <i class="mdi mdi-pencil font-size-18"></i>
    </a>

    <form method="POST" action="{{ route('archives.destroy', [$arr->id]) }}" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
            <i class="mdi mdi-delete font-size-18"></i>
        </button>
    </form>
</td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <ul class="pagination pagination-rounded justify-content-end mb-2"
                                    style="margin-right: 35%;">
                                    @if (isset($query))
                                        {{ $archives->appends($query)->links('pagination::bootstrap-4') }}
                                    @else
                                        {{ $archives->links('pagination::bootstrap-4') }}
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- end row -->
            @endif


        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

@endsection



<div class="row" dir="rtl">
    <div class="col-12">

        <div class="modal fade orderdetailsModal" tabindex="-1" role="dialog"
            aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl">إضــافة أرشـيـف وظـيـفـي
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="print">

                        <div dir="rtl">
                            <div class="table-responsive" dir="rtl"> </div>
                            <form action="{{ route('archives.store') }}" method="post" name="event-form"
                                id="form-event" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">الـمـوظـف <span><i class="fa fa-caret-down"
                                                        aria-hidden="true"></i></span>
                                            </label>
                                            <select style="color: black;border-color:black" name="emp_id"
                                                class="form-control form-select" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                                                oninput="this.setCustomValidity('')">
                                                <option disabled selected value="">الرجاء إختيار الموظف</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->person->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">الجهة التابعة</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                placeholder=" الجهة التابعة " type="text" name="entity" required
                                                oninvalid="this.setCustomValidity('الرجاء ادخال الجهة التابعة')"
                                                oninput="this.setCustomValidity('')" id="event-title" />
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">نوع الأرشيف <span><i class="fa fa-caret-down"
                                                        aria-hidden="true"></i></span>
                                            </label>
                                            <select style="color: black;border-color:black" name="type"
                                                class="form-control form-select" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار نوع الارشيف')"
                                                oninput="this.setCustomValidity('')">
                                                <option disabled selected value="">الرجاء إختيار النوع</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label "> ملف أو صورة
                                                PDF</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                name="files[]" type="file" id="formFile" multiple required
                                                oninvalid="this.setCustomValidity('الرجاء تحميل الوثيقة')"
                                                oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">الوصف (اختياري) </label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                placeholder=" الوصف " type="text" name="desc"
                                                id="event-title" />
                                            <div class="invalid-feedback"> </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">تاريخ الوثيقة (اختياري)</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                oninput="dateValidation(this)" placeholder="YYYY-MM-DD"
                                                type="date" name="date" id="event-title" />
                                            <div class="invalid-feedback"> </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary" id="btn-save-event">حفظ</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn btn-danger me-1"
                                            data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade addType" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 style="color: black" class="modal-title" id="orderdetailsModalLabel" dir="rtl">
                            إضافة نوع الأرشيف </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="print">

                        <div dir="rtl">
                            <div class="table-responsive" dir="rtl"> </div>
                            <form enctype="multipart/form-data" action="{{ route('archiveTypes.store') }}"
                                method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3" style="color: black">
                                            <label class="form-label ">نوع الأرشيف</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                placeholder="الرجاء إدخال نوع الأرشيف  " type="text"
                                                name="type" id="event-title" required
                                                oninvalid="this.setCustomValidity('الرجاء ادخال  نوع الأرشيف  ')"
                                                oninput="this.setCustomValidity('')" autocomplete="name" autofocus />
                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary" id="btn-save-event">حفظ</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn btn-danger me-1"
                                            data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" dir="rtl">
    <div class="col-12">

        <div class="modal fade editArchive" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">


                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl">تعديل أرشـيـف وظـيـفـي
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="print">

                        <div dir="rtl">
                            <div class="table-responsive" dir="rtl"> </div>
                            <form action="{{ route('archivesUpdate') }}" method="post" name="event-form"
                                id="form-event" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="arrId" value="">

                                <div class="row">

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">الـمـوظـف <span><i class="fa fa-caret-down"
                                                        aria-hidden="true"></i></span>
                                            </label>
                                            <select name="emp_id" class="form-control form-select" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                                                oninput="this.setCustomValidity('')">
                                                <option id="employee_select" value="" selected></option>
                                                </option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->person->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">الجهة التابعة</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                placeholder=" الجهة التابعة  " type="text" name="entity"
                                                value="" id="entity" required
                                                oninvalid="this.setCustomValidity('الرجاء ادخال  الجهة التابعة  ')"
                                                oninput="this.setCustomValidity('')" autocomplete="name" autofocus />
                                            <div class="invalid-feedback"> </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">نوع الأرشيف <span><i class="fa fa-caret-down"
                                                        aria-hidden="true"></i></span>
                                            </label>
                                            <select style="color: black;border-color:black" name="type"
                                                class="form-control form-select" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار نوع الارشيف')"
                                                oninput="this.setCustomValidity('')">
                                                <option id="type_select" value="" selected></option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label "> استبدال الملف أو
                                                الصورة
                                                PDF</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                name="files[]" type="file" id="formFile" multiple>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">الوصف</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                placeholder=" الوصف " type="text" name="desc"
                                                id="desc" />
                                            <div class="invalid-feedback"> </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label ">التاريخ (اختياري)</label>
                                            <input style="color: black;border-color:black" class="form-control"
                                                placeholder="التاريخ " type="date" name="date"
                                                oninput="dateValidation(this)" placeholder="YYYY-MM-DD"
                                                id="date" />
                                            <div class="invalid-feedback"> </div>
                                        </div>
                                    </div>



                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary" id="btn-save-event">حفظ</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn btn-danger me-1"
                                            data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row" dir="rtl">
    <div class="col-12">

        <div class="modal fade showFile" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">


                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl"> صورة من الوثيقة
                            <span id="arrName"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                            aria-label="Close"></button>
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



@section('script')
    <script>
        function editArchive(arr) {
            document.getElementById("arrId").value = arr['id'];
            document.getElementById("entity").value = arr['name'];
            document.getElementById("desc").value = arr['desc'];
            document.getElementById("date").value = arr['date'];
            document.getElementById("type_select").value = arr['type_id'];
            document.getElementById("type_select").innerHTML = arr['type']['name'];
            document.getElementById("employee_select").value = arr['emp_id'];
            document.getElementById("employee_select").innerHTML = arr['emp']['person']['name'];
        }

        function showItem(images) {

            console.log(images);
            var table = document.getElementById("bodyrow");
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
                cell.innerHTML = "<a href='/archiveTypes/" + idd +
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

        function openImageInNewTab(filename) {
            var url = '/storage/' + filename;
            console.log(url);
            window.open(url, '_blank');
        }

        function dateValidation(inputElement) {
            var dateRegex = /^\d{1,2}-\d{1,2}-\d{4}$/; // Regex for date format DD-MM-YYYY
            var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // Regex for date format DD/MM/YYYY

            var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/; // Regex for date format DD-MM-YYYY
            var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // Regex for date format DD/MM/YYYY
            // Check if the input matches either of the date formats
            if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) || dateRegex3.test(inputElement
                    .value) || dateRegex4.test(inputElement.value)) {
                // If the date does not match the format, show a custom validity message
                inputElement.setCustomValidity('');
            } else {
                inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');

                // If the date matches one of the formats, clear any custom validity message
            }
        }
    </script>
@endsection
