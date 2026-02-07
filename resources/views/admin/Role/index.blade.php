
            @extends('admin.layout.master')

@section('title')
    <title> كل الصلاحيات </title>
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
                                <li class="breadcrumb-item" aria-current="page">كل الصلاحيات</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <div class="page-header-title">
                                <h2 class="mb-0">كل الصلاحيات</h2>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-sm-end">
                                <a href="{{route('roles.create')}}" class="btn btn-primary" style="color: white; float: left;">إضـــافــة صـلاحـيـة</a>
                            </div>
                        </div><!-- end col-->
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->





            @if ($roles->count() > 0)
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <form action="{{ route('searchRole') }}" method="get" class="p-3"
                                style="margin-bottom: -20px;">
                                @csrf
                                <div class="row">

                                    <div class="col-lg-4">

                                        <div class="form-group m-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="البحث عن صلاحية" aria-label="Recipient's username">
                                            </div>
                                        </div>
                                    </div>

                            </form>

                        </div>

                        <div class="card-body">

                            {{-- <div class="col-lg-4">
                                            <div class="form-group m-0">
                                                <div class="btn" role="group" aria-label="Basic example">
                                                    <a href="#" onclick="exportData('xlsx',{{json_encode('الموظفين')}})" type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-file me-1"></i> تــصديــر</a>
                                                </div>
                                            </div>
                                        </div> --}}

                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover" id="tab1">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">اسم الصلاحية</th>
                                            <th scope="col" class="lastR text-center" style="">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $index => $role)
                                            <tr class="text-center">

                                                <td class="text-center">{{ $index+1 }} </td>

                                                <td>{{ $role->name }}</td>

                                                <td>
                                                    <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">عرض</a>
                                                </td>

                                                {{-- <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <button type="button" class="btn header-item waves-effect"
                                                            id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v  d-none d-xl-inline-block"
                                                                style="font-size: 18px;"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <!-- item-->
                                                            <!-- <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">شخصي</span></a> -->
                                                            @if (!$emp->startout_data)
                                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('employees.edit', [$emp->id]) }}"><i
                                                                            class="bx bx-pencil font-size-16 align-middle me-1"></i>
                                                                        <span key="t-profile">تعديل</span></a>
                                                                @endif

                                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('resignation.edit', [$emp->id]) }}"><i
                                                                            class="fa fa-hand-paper font-size-16 align-middle me-1"></i>
                                                                        <span key="t-profile">إسـتقـالة</span></a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td> --}}

                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination pagination-rounded justify-content-center mb-2">

                                @if (isset($query))
                                    {{ $roles->appends($query)->links('pagination::bootstrap-4') }}
                                @else
                                    {{ $roles->links('pagination::bootstrap-4') }}
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-12">
                <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد صـلاحـيـات</h4>
            </div>
        </div>
        @endif
    </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

@endsection

@section('script')
    <script>
        function exportData(type, name) {

            $('#tab1 tr').find('th:last-child, td:last-child').remove();
            $('#tab1 tr').find('th:last-child, td:last-child').remove();
            $('#tab1 tr').find('th:first-child, td:first-child').remove();
            var data = document.getElementById('tab1');
            var file = XLSX.utils.table_to_book(data, {
                sheet: "الموظفين"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, name + '.' + type);
            window.location.reload();
        }
    </script>
@endsection
