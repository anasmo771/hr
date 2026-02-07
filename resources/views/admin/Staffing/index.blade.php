@extends('admin.layout.master')

@section('title')
    <title> الرئيسية </title>
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
              <li class="breadcrumb-item" aria-current="page">المسمى الوظيفي</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">المسمى الوظيفي</h2>
            </div>
          </div>
          <div class="text-sm-end">
            <a href="{{ route('Staffing.create') }}" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2">
              إضافة مسمى وظيفي جديد
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">

            <div class="table-responsive">
              <table class="table align-middle table-nowrap table-check">
                <thead class="table-light">
                  <tr>
                    <th scope="col" style="width: 70px;">#</th>
                    <th scope="col">المسمى الوظيفي</th>
                    <th scope="col">عدد الوحدات</th>
                    <th scope="col">عدد الموظفين</th>
                    <th class="col">تعديل</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($all as $index => $spec)
                    <tr>
                      <td class="text-center">{{ $index + 1 }}</td>

                      <td>
                        <h5 class="font-size-14 mb-1">
                          <span>{{ $spec->name }}</span>
                        </h5>
                      </td>

                      {{-- عدد الوحدات (إن وُجد units_count من withCount وإلا fallback) --}}
                      <td>
                        {{ $spec->units_count
                            ?? (method_exists($spec, 'unitStaffings') ? $spec->unitStaffings()->count() : 0) }}
                      </td>

                      {{-- عدد الموظفين عبر الوحدات (يفضّل employees_count من withCount) --}}
                      <td>
                        {{ $spec->employees_count
                            ?? (method_exists($spec, 'employees') ? $spec->employees()->count() : 0) }}
                      </td>

                      <td>
                        <ul class="list-inline mb-0 me-2">
                          <li class="list-inline-item px-2">
                            <a href="{{ route('Staffing.edit', [$spec->id]) }}" class="btn btn-sm btn-primary" title="تعديل">
                              <i class="mdi mdi-pencil font-size-18"></i>
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
              {{ $all->links('pagination::bootstrap-4') }}
            </ul>

          </div>
        </div>
      </div>
    </div>
    <!-- end row -->
  </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->

<!-- Modal (إن احتجته لاحقًا) -->
<div class="modal fade orderdetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderdetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderdetailsModalLabel">البحث بتاريخ معين</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" value="" id="mini_id" name="id">
          <div class="mb-3">
            <label for="manufacturerbrand">تحديد التاريخ</label>
            <div class="input-daterange input-group" id="project-date-inputgroup" data-provide="datepicker"
                 data-date-format="dd M, yyyy" data-date-container='#project-date-inputgroup' data-date-autoclose="true">
              <input type="month" class="form-control" style="direction: rtl;" placeholder="تاريخ الاصدار" name="date" required
                     oninvalid="this.setCustomValidity('الرجاء تحديد التاريخ')" oninput="this.setCustomValidity('')">
            </div>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary waves-effect waves-light">البحث</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function () {
    const rtlSwitch = document.getElementById("rtl-mode-switch");
    if (rtlSwitch && typeof $(rtlSwitch).trigger === 'function') {
      $(rtlSwitch).trigger('click');
    }
  });

  function searchBy(id) {
    document.getElementById("mini_id").value = id;
  }
</script>
@endsection
