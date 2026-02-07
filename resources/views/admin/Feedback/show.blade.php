

            @extends('admin.layout.master')

            @section('title')
            <title> تـقـيـيمات الـمـوظــف  </title>
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
                                            <li class="breadcrumb-item" aria-current="page">تـقـاريـر كـفـايـة الـمـوظــف   </li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-ms-6">
                                            <h4 class="mb-sm-0 font-size-18">تـقـاريـر الـكـفـايـة للـمـوظــف <a href="{{ route('EmployeeDetails', [$emp->id]) }}"><span
                                                style="color: blue;">{{ $emp->person->name }}</span></a> </h4>
                                                <a href="{{route('createFeedback',[$emp->id])}}" class="btn btn-primary" style="color: white; float: left;">اضـافـة تقرير الكفاية</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [ breadcrumb ] end -->

                        @include('admin.layout.validation-messages')

                        <!-- [ Main Content ] start -->





                        @if($feed->count() > 0)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">

                                    <form action="{{ route('searchEmployeefeedback') }}" method="get" class="px-3 py-2">
                                    @csrf
                                    <div class="row g-2 align-items-center">
                                        <div class="col-lg-4">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control rounded-pill" name="name" placeholder="البحث عن موظف" aria-label="Recipient's username">
                                                <button class="btn btn-outline-primary rounded-end-pill px-3" type="submit">
                                                    <i class="mdi mdi-magnify" style="font-size: 1.2rem;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                    <div class="card-body">


                                <div class="card-body" id="print2">

                                    <div class="table-responsive">

                                                                                    <!-- جدول عرض التقييمات -->
                                        <table class="table align-middle table-nowrap table-hover" id="tab1">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">اسم الموظف</th>
                                                    <th class="text-center">التقييم اللفظي</th>
                                                    <th class="text-center">درجة التقييم</th>
                                                    <th class="text-center">تاريخ التقييم</th>
                                                    <th class="text-center">الوثيقة</th>
                                                    <th class="text-center">منشئ التقييم</th>
                                                    <th class="text-center">الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($feed as $index => $fe)
                                                <tr>
                                                    <td class="text-center">{{ $index+1 }}</td>
                                                    <td class="text-center">{{ $fe->emp->person->name }}</td>
                                                    <td class="text-center">{{ $fe->text_grade }}</td>
                                                    <td class="text-center">
                                                        <a href=".showFeedBack"
                                                        class="text-primary showFeedBackBtn"
                                                        data-bs-toggle="modal"
                                                        data-name="{{ $fe->emp->person->name }}"
                                                        data-result='@json($fe)'>
                                                        {{ $fe->grade }}
                                                        </a>
                                                    </td>
                                                    <td class="text-center">{{ $fe->year }}</td>
                                                    <td class="text-center">
                                                        @if ($fe->files->count() > 0)
                                                            <a href=".showFile" data-bs-toggle="modal"
                                                            onclick="showItem({{ $fe->files }})"
                                                            class="text-primary">
                                                                <i class="bx bx-file" style="font-size:25px;margin-top:5px"></i>
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $fe->user->name }}</td>
                                                        <td class="text-center col-lg-2">
                                                    <ul class="list-inline mb-0 text-center">
                                                        <li class="list-inline-item px-2" id="edit">
                                                            <a href="{{ route('feedback.edit', [$fe->id]) }}" class="btn btn-sm btn-primary" title="تعديل">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </a>
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
                                            {{ $feed->appends($query)->links('pagination::bootstrap-4') }}
                                          @else
                                          {{ $feed->links('pagination::bootstrap-4') }}
                                          @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد تـقـيـيـمـات</h4>
                            </div>
                        </div>
                        @endif
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

<div class="row" dir="rtl">
    <div class="col-12">
        <!-- المودال الخاص بالتقييم -->
        <div class="modal fade showFeedBack" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color:black;">الـتـقـيـيـمـات</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row" id="print22">
                            <div class="col-12">
                                <h5 class="mb-2" style="color: black;">اسم الموظف: 
                                    <span style="color: blue;" id="orderName"></span>
                                </h5>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-bordered table-check text-center">
                                <thead class="table-light">
                                    <tr style="color: black;">
                                        <th>كفائة الأداء والصفات الشخصية</th>
                                        <th>درجة النهاية العظمى</th>
                                        <th>درجة كفاية الموظف</th>
                                        <th>درجة الكفاية المعدلة</th>
                                        <th>مبررات تعديل درجة الكفاية</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyrow23">
                                    <!-- يتم ملؤه بالجافاسكريبت -->
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

    <!-- المودال الخاص بالوثائق -->
    <div class="col-12 mt-3">
        <div class="modal fade showFile" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" dir="rtl">صورة من الوثيقة <span id="arrName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" dir="rtl"></button>
                    </div>

                    <div class="modal-body" id="print">
                        <div class="table-responsive" dir="rtl">
                            <table class="table table-hover table-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">عرض</th>
                                        <th class="text-center">تنزيل</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyrow">
                                    <!-- يتم ملؤه بالجافاسكريبت -->
                                </tbody>
                            </table>
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
        const table = document.getElementById("bodyrow");
        table.innerHTML = "";

        images.forEach((img, i) => {
            const row = table.insertRow();
            row.insertCell().innerText = "ملف " + (i + 1);

            row.insertCell().innerHTML = `<a href='/storage/${img.path}' target='_blank' class='text-primary'>
                <i class='bx bx-show' style='font-size:25px;'></i></a>`;
            row.insertCell().innerHTML = `<a href='/downloadFile/${img.id}' class='text-primary'>
                <i class='bx bx-download' style='font-size:25px;'></i></a>`;
        });
    }

    $(document).on("click", ".showFeedBackBtn", function () {
        const result = $(this).data('result');
        const name = $(this).data('name');
        document.getElementById("orderName").innerText = name;

        const table = document.getElementById("bodyrow23");
        table.innerHTML = "";

        function addRow(title, max, g1, g2, note) {
            const row = table.insertRow();
            row.insertCell().innerText = title;
            row.insertCell().innerText = max;
            row.insertCell().innerText = g1 ?? '-';
            row.insertCell().innerText = g2 ?? '-';
            row.insertCell().innerText = note ?? '-';
        }

        addRow("أداء الواجب", 45, result.grade11, result.grade12, result.textGrade1);
        addRow("المواظبة على العمل", 15, result.grade21, result.grade22, result.textGrade2);
        addRow("القدرات والاستعداد الذاتي", 20, result.grade31, result.grade32, result.textGrade3);
        addRow("العلاقات الإنسانية", 20, result.grade41, result.grade42, result.textGrade4);
    });
</script>
@endsection
