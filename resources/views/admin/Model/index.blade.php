@extends('admin.layout.master')

@section('title')
    <title> الـنـماذج الـجـاهـزة </title>
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
                                <li class="breadcrumb-item" aria-current="page">الـنـماذج الـجـاهـزة </li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="page-header-title">
                                <h2 class="mb-0">الـنـماذج الـجـاهـزة </h2>
                                
                            </div>
                        </div>
                         <div class="col-6">
                        <div class="text-sm-end">
                            <a href=".orderdetailsModal" data-bs-toggle="modal" data-bs-target=".orderdetailsModal"
                               class="btn btn-primary text-white ms-3"> إضافة نموذج </a>
                                       </div>
                    </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->



            <!-- start page title -->
            <div class="row">

                @if ($models->count() == 0)
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد نـماذج</h4>
                        </div>
                    </div>
                @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="d-flex justify-content-between align-items-end mb-4 mt-3 ms-3 me-3">
                            <form action="{{ route('admin.searchModels') }}" method="get" class="d-flex">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control rounded-pill" name="search" required
                                        oninvalid="this.setCustomValidity('الرجاء تعبئة خانة البحث')"
                                        oninput="this.setCustomValidity('')"
                                        placeholder="البحث عن نموذج" aria-label="Search">
                                    <button class="btn btn-outline-primary rounded-pill" type="submit">
                                        <i class="mdi mdi-magnify" style="font-size: 1.2rem;"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div style="color: black" class="table-responsive" dir="rtl">
                                <table class="table align-middle table-nowrap table-check">
                                    <thead class="table-light">
                                        <tr style="color: black">
                                            <th class="text-center"> #</th>
                                            <th class="text-center">الوصف</th>
                                            <th scope="col" class="text-center">الـوثـيـقـة</th>
                                            <th class="text-center">التاريخ</th>
                                            <th class="text-center">التعديل </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;

                                            $j = 0;
                                        @endphp
                                        @foreach ($models as $arr)
                                    <tr style="color: black" class="text-center">
                                        <td style="color: black">{{ $i++ }}</td>
                                        <td>{{ $arr->name }}</td>

                                        @if ($arr->files->count() > 0)
                                            <td>
                                                <a href=".showFile" data-bs-toggle="modal"
                                                    onclick="showItem({{ $arr->files }})"
                                                    class="text-primary">
                                                <i class="bx bx-file" style="font-size:25px;margin-top:5px"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td class="text-center"> - </td>
                                        @endif
                                        <td>{{ $arr->created_at->format('Y-m-d') }}</td>

                                        <td>
                                        <!-- زر التعديل -->
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-primary me-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target=".editArchive" 
                                            onclick="editArchive({{ $arr }})"
                                            title="تعديل">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </button>
                                        <!-- زر الحذف -->
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $arr->id }}"
                                            title="حذف">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </button>

                                        <!-- مودال تأكيد الحذف -->
                                        <div id="deleteModal{{ $arr->id }}" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="deleteModalLabel{{ $arr->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-body text-center">
                                                        <div>
                                                            <i class="ti ti-trash text-danger fa-5x mb-3"></i>
                                                        </div>
                                                        <p style="font-size: 18px; padding-top: 20px;">
                                                            هل أنت متأكد من حذف العنصر التالي؟<br>
                                                            <span style="color: red;">{{ $arr->name }}</span>
                                                        </p>
                                                        <form action="{{ route('models.destroy', [$arr->id]) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                    </div>

                                                    <div class="modal-footer d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
                                    <ul class="pagination pagination-rounded justify-content-end mb-2"
                                        style="margin-right: 35%;">
                                        @if (isset($query))
                                            {{ $models->appends($query)->links('pagination::bootstrap-4') }}
                                        @else
                                            {{ $models->links('pagination::bootstrap-4') }}
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


        <div class="row" dir="rtl">
            <div class="col-12">

                <div class="modal fade orderdetailsModal" tabindex="-1" role="dialog"
                    aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl">إضــافة نـمـوذج </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="print">

                                <div dir="rtl">
                                    <div class="table-responsive" dir="rtl"> </div>
                                    <form action="{{ route('models.store') }}" method="post" name="event-form"
                                        id="form-event" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label class="form-label ">عنوان النموذج </label>
                                                    <input style="color: black;border-color:black" class="form-control"
                                                        placeholder=" عنوان النموذج " type="text" name="name"
                                                        required
                                                        oninvalid="this.setCustomValidity('الرجاء ادخال عنوان النموذج')"
                                                        oninput="this.setCustomValidity('')" id="event-title" />
                                                    <div class="invalid-feedback"> </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label "> ملف أو صورة
                                                        PDF</label>
                                                    <input style="color: black;border-color:black" class="form-control"
                                                        name="files[]" type="file" id="formFile" multiple required
                                                        oninvalid="this.setCustomValidity('الرجاء ادخال الملف')"
                                                        oninput="this.setCustomValidity('')">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary"
                                                    id="btn-save-event">حفظ</button>
                                            </div>
                                            <div class="col-6 text-end">
                                                <button type="button" class="btn btn-light me-1"
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

                <div class="modal fade editArchive" tabindex="-1" role="dialog"
                    aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderdetailsModalLabel" dir="rtl">تـعـديـل النـمـوذج
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" dir="rtl"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="print">

                                <div dir="rtl">
                                    <div class="table-responsive" dir="rtl"> </div>
                                    <form action="{{ route('modelsUpdate') }}" method="post" name="event-form"
                                        id="form-event" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" id="modelId" value="">

                                        <div class="row">


                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label class="form-label ">عنوان النموذج </label>
                                                    <input style="color: black;border-color:black" class="form-control"
                                                        placeholder=" عنوان النموذج  " type="text" required
                                                        oninvalid="this.setCustomValidity('الرجاء ادخال عنوان النموذج')"
                                                        oninput="this.setCustomValidity('')" name="name"
                                                        id="name" />
                                                    <div class="invalid-feedback"> </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label "> ملف أو صورة
                                                        PDF</label>
                                                    <input style="color: black;border-color:black" class="form-control"
                                                        name="files[]" type="file" multiple id="formFile">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary"
                                                    id="btn-save-event">حفظ</button>
                                            </div>
                                            <div class="col-6 text-end">
                                                <button type="button" class="btn btn-light me-1"
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


        <!-- end modal -->
    @endsection

    @section('script')
<script>
  function showItem(images) {
    const table = document.getElementById("bodyrow");
    table.innerHTML = "";

    // نبني URL من الراوت نفسه ثم نستبدل الـ ID في JS
    const PREVIEW_ROUTE_TEMPLATE = "{{ route('modelfiles.preview', ['id' => '__ID__']) }}";

    for (let i = 0; i < images.length; i++) {
      const row = table.insertRow(-1);

      let cell = row.insertCell();
      cell.innerHTML = " ملف " + (i + 1);
      cell.style.textAlign = "center";

      const fileId = images[i]['id'];
      const previewUrl = PREVIEW_ROUTE_TEMPLATE.replace('__ID__', fileId);

      cell = row.insertCell();
      cell.innerHTML =
        "<a href='" + previewUrl + "' target='_blank' class='text-primary' title='عرض'>" +
          "<i class='bx bx-show' style='font-size:25px;margin-top:5px'></i>" +
        "</a>";
      cell.style.textAlign = "center";

      cell = row.insertCell();
      cell.innerHTML =
        "<a href='/downloadFile/" + fileId + "' class='text-primary' title='تنزيل'>" +
          "<i class='bx bx-download' style='font-size:25px;margin-top:5px'></i>" +
        "</a>";
      cell.style.color = "green";
      cell.style.textAlign = "center";
    }
  }

  function editArchive(arr) {
    document.getElementById("modelId").value = arr['id'];
    document.getElementById("name").value = arr['name'];
  }
</script>
@endsection
