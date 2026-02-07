@extends('admin.layout.master')

@section('title')
    <title>المستقيلين </title>
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
                                <li class="breadcrumb-item" aria-current="page"> 
                                    المستقيلين</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">  المستقيلين</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->



            @if ($employees->count() > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                         <form action="{{ route('searchResignationEmployee') }}" method="get" class="px-3 py-2">
                            @csrf
                            <div class="row g-2 align-items-center">
                               
                                <div class="col-lg-4">
                                    <div class="input-group input-group-sm">
                                        <select name="type" class="form-select rounded-pill">
                                            <option selected value="name">الاسم</option>
                                            <option value="id">الرقم الوطني</option>
                                            <option value="degree">الدرجة الوظيفية</option>
                                            <option value="res_num">رقم القرار</option>
                                        </select>
                                    </div>
                                </div>

                              
                                <div class="col-lg-4">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="name" class="form-control rounded-pill" placeholder="البحث عن موظف مستقيل">
                                    </div>
                                </div>

                            
                                <div class="col-lg-4">
                                    <div class="input-group input-group-sm">
                                        <select name="workplace" class="form-select rounded-start-pill">
                                            <option selected disabled value="0">اختار جهة معينة لعرض المستقيلين</option>
                                            <option value="all">عرض الكل</option>
                                            @foreach ($subsection1 as $su)
                                                <option value="{{ $su->id }}">{{ $su->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap table-hover" id="tab1">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col" class="text-center">الاســـم</th>
                                                <th scope="col" class="text-center">الرقم الوطني</th>
                                                <th scope="col" class="text-center">الدرجة الحالية</th>
                                                <th scope="col" class="text-center">تاريخ المباشرة</th>
                                                <th scope="col" class="text-center">رقم القرار</th>
                                                <th scope="col" class="text-center">القـسـم</th>
                                                <th scope="col" class="text-center">مـؤرشـف</th>
                                                <th scope="col" class="text-center">الحالة الوظيفية</th>

                                                <th scope="col" class="text-center">المنشئ</th>
                                                <th scope="col" class="text-center">عرض التفاصيل</th>
                                                <th scope="col" class="lastR text-center" style="">الاجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employees as $index => $emp)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }} </td>

                                                    <td class=" text-center">
                                                        <h5 class="text-center">{{ $emp->person->name }}</h5>
                                                    </td>

                                                    <td class=" text-center">{{ $emp->person->N_id }}</td>
                                                    <td class=" text-center">{{ $emp->degree }}</td>
                                                    <td class=" text-center">{{ $emp->start_date }}</td>
                                                    <td class=" text-center">{{ $emp->res_num }}</td>
                                                    <td class=" text-center">{{ $emp->section->name }}</td>
                                                    <td class=" text-center">{{ $emp->ch }} - {{ $emp->num }}</td>

                                                    @if ($emp->startout_data)
                                                        <td class=" text-center" style="color: red;">مستقيل</td>
                                                    @elseif(Carbon\Carbon::now()->between($emp->ndb_start, $emp->ndb_end))
                                                        <td class=" text-center" style="color: green;">ندب</td>
                                                    @elseif($emp->on)
                                                        <td class=" text-center" style="color: green;">يعمل</td>
                                                    @else
                                                        <td class=" text-center" style="color: red;">متوقف</td>
                                                    @endif
                                                    </td>

                                                    @if ($emp->user)
                                                        <td class=" text-center">{{ $emp->user->name }}</td>
                                                    @else
                                                        <td class=" text-center">-</td>
                                                    @endif

                                                    <td class=" text-center"> <a
                                                            href="{{ route('EmployeeDetails', [$emp->id]) }}"
                                                            class=""> <i class="mdi mdi-eye" style="font-size: 23px;"></i>
                                                    </td>
                                                    <td class="text-center">
                                                       <div class="dropdown d-inline-block">
                                                            <button type="button" class="btn btn-light p-2"
                                                                id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false" style="transition: all 0.3s ease;">
                                                                <i class="fa fa-ellipsis-v text-secondary" style="font-size: 18px;"></i>
                                                            </button>

                                                            <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in rounded-3 mt-2" style="min-width: 160px;">
                                                            @if (!$emp->startout_data)
                                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('employees.edit', [$emp->id]) }}">
                                                                        <i class="bx bx-pencil text-primary me-2"></i>
                                                                        <span>تعديل</span>
                                                                    </a>
                                                                @endif

                                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                                                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('vacations.show', [$emp->id]) }}">
                                                                        <i class="fa fa-calendar-alt text-primary me-2"></i>
                                                                        <span>الإجازات</span>
                                                                    </a>
                                                                @endif

                                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('resignation.edit', [$emp->id]) }}">
                                                                        <i class="fa fa-hand-paper text-danger me-2"></i>
                                                                        <span>إسـتقـالة</span>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                                                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('vacations.show', [$emp->id]) }}">
                                                                        <i class="fa fa-calendar-alt text-primary me-2"></i>
                                                                        <span>الإجازات</span>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <ul class="pagination pagination-rounded justify-content-center mb-2">

                                    @if (isset($query))
                                        {{ $employees->appends($query)->links('pagination::bootstrap-4') }}
                                    @else
                                        {{ $employees->links('pagination::bootstrap-4') }}
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="mb-sm-0 text-center font-size-18">لا يــوجــد مـوظـفـيـن مسـتـقيـليـن</h4>
                    </div>
                </div>
            @endif
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

@endsection
