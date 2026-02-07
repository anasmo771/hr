
            @extends('admin.layout.master')

            @section('title')
            <title>إضافة تـكـلـيـف</title>
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
                                            <li class="breadcrumb-item" aria-current="page">إضــافــة التـكـلـيـف  للــمــوظــف <a
                                                href="{{ route('EmployeeDetails', [$emp->id]) }}"><span
                                                    style="color: blue;">{{ $emp->name }}</span></a> </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <div class="page-header-title">
                                            <h2 class="mb-0">إضــافــة التـكـلـيـف  للــمــوظــف <a
                                                href="{{ route('EmployeeDetails', [$emp->id]) }}"><span
                                                    style="color: blue;">{{ $emp->name }}</span></a> </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [ breadcrumb ] end -->

                        @include('admin.layout.validation-messages')

                        <!-- [ Main Content ] start -->



            <div class="row">
                <div class="col-lg-12">

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
                                        <h4>{{ $emp->person->name }}</h4>
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
                                        @if($emp->subSection)
                                        <h4>{{$emp->section->name}} - {{ $emp->subSection->name }}</h4>
                                        @else
                                        <h4>{{$emp->section->name}}</h4>
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
                            <h4 class="card-title">تـقـديـم تـكـلـيـف  للـمـوظـف</h4>
                            @include('includes.messages')

                            <form action="{{ route('saveTask', ['id' => $emp->id]) }}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="row mb-4">

                                    <div class="col-lg-4">
                                    <label for="number" class="col-form-label col-lg-4" style="font-size:100%;">الـرقـم الإشـاري</label>
                                    <input id="number" name="number" type="text" class="form-control" value="{{ old('number') }}" required
                                            oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الإشاري للتكليف')"
                                            oninput="this.setCustomValidity('')" placeholder="الـرقـم الإشـاري">
                                    </div>

                                    <div class="col-lg-4">
                                    <label for="title" class="col-form-label col-lg-4" style="font-size:100%;">سبب التكليف</label>
                                    <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required
                                            oninvalid="this.setCustomValidity('الرجاء ادخال سبب التكليف')"
                                            oninput="this.setCustomValidity('')" placeholder="سبب التكليف">
                                    </div>

                                    <div class="col-lg-4">
                                    <label for="date" class="col-form-label col-lg-4" style="font-size:100%;">تـاريـخ الـتـكـلـيـف</label>
                                    <input id="date" name="date" type="date" class="form-control" value="{{ old('date') }}" required
                                            oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ التكليف')"
                                            oninput="this.setCustomValidity('')">
                                    </div>

                                    <div class="col-lg-4">
                                    <label for="task_res" class="col-form-label col-lg-4" style="font-size:100%;">مــصــدر الـقــرار</label>
                                    <input id="task_res" name="task_res" type="text" class="form-control"
                                            value="{{ old('task_res') }}" placeholder="مثال: كتاب إداري / تعميم / بريد وارد ...">
                                    </div>

                                    <div class="col-lg-8">
                                    <label for="note" class="col-form-label col-lg-4" style="font-size:100%;">مــلاحظــات</label>
                                    <textarea id="note" name="note" rows="2" class="form-control" placeholder="ملاحظات إضافية (اختياري)">{{ old('note') }}</textarea>
                                    </div>

                                    <div class="col-sm-4 mt-4">
                                    <div class="mb-3 ">
                                        <label>وثـيـقـة (اختياري)</label>
                                        <input name="files[]" type="file" class="form-control" multiple>
                                    </div>
                                    
                                    </div>                          
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                    <button type="submit" class="btn btn-primary">حـفـظ التـكـلـيـف</button>
                                    </div>
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
@endsection


