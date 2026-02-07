

@extends('admin.layout.master')


@section('title')
<title>عـلاوة جـديـد</title>
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
                                <li class="breadcrumb-item">اضـافـة العـلاوة للـمـوظـف <a href="{{ route('EmployeeDetails', [$emp->id]) }}"><span style="color: blue;">{{$emp->person->name}}</span></a> </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h2 class="mb-0">اضـافـة العـلاوة للـمـوظـف <a href="{{ route('EmployeeDetails', [$emp->id]) }}"><span style="color: blue;">{{$emp->person->name}}</span></a></h2>
                            </div>
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

                                <h4 class="card-title">بـيـانـات الـمـوظـف</h4>


                                <!-- <p class="card-title-desc">Fill all information below</p> -->

                                  <div class="row">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="fullname">اســم الــموظــف</label>
                                            {{-- @if($emp->person->image)
                                                <h4> <img src="{{ asset(Storage::url($emp->person->image)) }}"
                                                    class="me-1 rounded-circle avatar-sm" alt="user-pic"> {{$emp->person->name}}</h4>
                                            @else
                                                <h4> <img src="{{asset('assets/images/user.png')}}"
                                                    class="me-1 rounded-circle avatar-sm" alt="user-pic"> 
                                            @endif --}}
                                            <h4> {{ $emp->person->name }}</h4>
                                        </div>
                                    </div>

                                    @if ($emp->person->N_id)
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="email">الرقــم الوطــني</label>
                                            <h4>{{$emp->person->N_id}}</h4>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="email">رقم الإقامة او الجواز لغير الليبين</label>
                                            <h4>{{$emp->person->non_citizen_ref_no}}</h4>
                                        </div>
                                    </div>
                                    @endif

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">المؤهــل في القرار</label>
                                                <h4>{{$emp->qualification}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">التــخــصص</label>
                                                @if($emp->specialty)
                                                        <h4>{{$emp->specialty->name}}</h4>
                                                    @else
                                                        <h4> - </h4>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تاريــخ الاستــحقاق</label>
                                                <h4>{{$emp->due}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">الدرجــة الحــالية</label>
                                                <h4>{{$emp->degree}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تاريــخ المبــاشرة</label>
                                                <h4 id ="earnDate">{{$emp->start_date}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">رقــم القــرار</label>
                                                <h4>{{$emp->res_num}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 mb-3">
                                            <label class="control-label">الإدارة</label>
                                            @php
                                                // إن كان للموظف مقعد/وحدة وظيفية مخصصة
                                                $unit   = optional($emp->unitStaffing)->unit;     // sub_sections المرتبطة بالمقعد
                                                // في حال لا يوجد مقعد، نستخدم الحقل القديم sub_section_id
                                                $fallbackUnit = $emp->subSection;

                                                // نختار أيهما متوفر
                                                $u = $unit ?: $fallbackUnit;

                                                $parentName = optional($u)->parent ? $u->parent->name : null;
                                                $selfName   = optional($u)->name;
                                            @endphp

                                            @if($selfName)
                                                <h4>{{ $parentName ? $parentName.' - ' : '' }}{{ $selfName }}</h4>
                                            @else
                                                <h4>-</h4>
                                            @endif
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">الـجـنـس </label>
                                                <h4>{{$emp->person->gender}}</h4>
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label for="email">الحالة الاجتماعية </label>
                                                        <h4>{{$emp->person->marital_status}}</h4>
                                                    </div>
                                                </div>
                                    </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">تـقـديـم بـيـانـات العـلاوة</h4>

                                <form action="{{route('storeBouns',[$emp->id])}}" method="post" enctype="multipart/form-data" id="form">
                                    @csrf
                                    <div class="row mt-3">


                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">رقــم الــعلاوة</label>
                                                @if ($emp->bouns()->where('degree', $emp->dgree)->count() > 0)
                                                    <input id="earnDate" name="bonus_num" value="{{ $emp->bouns()->where('degree', $emp->dgree)->count()+1 }}" type="number" class="form-control" placeholder="رقــم الــعلاوة" required oninvalid="this.setCustomValidity('الرجاء ادخال رقم العلاوة')" oninput="this.setCustomValidity('')">
                                                @else
                                                    <input id="earnDate" name="bonus_num" value="1" type="number" class="form-control" placeholder="رقــم الــعلاوة" required oninvalid="this.setCustomValidity('الرجاء ادخال رقم العلاوة')" oninput="this.setCustomValidity('')">
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">الـدرجـة</label>
                                                <input id="earnDate" name="degree" type="number" value="{{ $emp->degree }}" class="form-control" placeholder="الـدرجـة" required oninvalid="this.setCustomValidity('الرجاء ادخال الدرجة ')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تـاريـخ الاسـتـحـقـاق</label>
                                                <input id="earnDate" name="date" type="date" class="form-control"  oninput="dateValidation(this)" placeholder="YYYY-MM-DD" required oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ الاستحقاق')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تـاريـخ اخـر عـلاوة</label>
                                                @if ($emp->bouns()->where('degree', $emp->dgree)->count() > 0)
                                                <input id="earnDate" name="last_date" value="{{ $emp->bouns()->where('degree', $emp->dgree)->last()->date }}" type="date" class="form-control" oninput="dateValidation(this)" placeholder="YYYY-MM-DD">
                                                @else
                                                <input id="earnDate" name="last_date" type="date" class="form-control" oninput="dateValidation(this)" placeholder="YYYY-MM-DD">
                                                @endif
                                            </div>
                                        </div>

                                          <div class="col-sm-4">
                                            <div class="mb-3 ">
                                                <label for="email">وثـيـقـة  (اختياري) </label>
                                                <input name="files[]" type="file" class="form-control" multiple>
                                            </div>
                                        </div>

                                      </div>

                                      <br>

                                      <div class="d-flex flex-wrap gap-2">
                                          <button type="submit" class="btn btn-primary waves-effect waves-light">إضــافـة الـعـلاوة</button>
                                      </div>
                                  </form>

                            </div>
                        </div>


                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->



        @endSection
        @section('script')
        <script>

function dateValidation(inputElement) {
    var dateRegex = /^\d{1,2}-\d{1,2}-\d{4}$/; // Regex for date format DD-MM-YYYY
    var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // Regex for date format DD/MM/YYYY

    var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/; // Regex for date format DD-MM-YYYY
    var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // Regex for date format DD/MM/YYYY
    // Check if the input matches either of the date formats
    if (dateRegex.test(inputElement.value) || dateRegex2.test(inputElement.value) || dateRegex3.test(inputElement.value) || dateRegex4.test(inputElement.value)) {
        // If the date does not match the format, show a custom validity message
        inputElement.setCustomValidity('');
    } else {
        inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY');

        // If the date matches one of the formats, clear any custom validity message
    }
}

            </script>
        @endSection
