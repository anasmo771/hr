@extends('admin.layout.master')

@section('title')
    <title> إضافة موظف </title>
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
                                <li class="breadcrumb-item" aria-current="page">إضافــة موظـف</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0"> إضافــة موظـف </h2>
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
                    <form action="{{ route('employees.store') }}" method="post" enctype="multipart/form-data" id="form">
                        @csrf

                        {{-- البطاقة: البيانات الشخصية --}}
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">الـبـيـانـات الـشـخـصـيـة</h4>

                                <div class="row mt-3">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="fullname">اســم الــموظــف</label>
                                            <input id="fullname" name="name" type="text" class="form-control"
                                                   onkeyup="name_validation()" placeholder="اسم الموظف" required
                                                   value="{{ old('name') }}"
                                                   oninvalid="this.setCustomValidity('الرجاء ادخال اسم الموظف')"
                                                   oninput="this.setCustomValidity('')">
                                            <span id="Name_text"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" id="Libyan1">
                                        <div class="mb-3">
                                            <label for="N_id">الرقــم الوطــني</label>
                                            <input id="N_id" name="N_id" min="12" max="12" type="text"
                                                   onkeyup="N_id_validation()" class="form-control"
                                                   placeholder="الرقم الوطني" required
                                                   oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الوطني')"
                                                   oninput="this.setCustomValidity('')"
                                                   value="{{ old('N_id') }}">
                                            <span id="text_N_id"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-4" style="display:none;" id="notLibyan1">
                                        <div class="mb-3">
                                            <label for="non_citizen_ref_no">رقم الإقامة او الجواز لغير الليبين</label>
                                            <input id="non_citizen_ref_no" name="non_citizen_ref_no"
                                                   type="text" class="form-control"
                                                   placeholder="رقم الإقامة او الجواز لغير الليبين"
                                                   value="{{ old('non_citizen_ref_no') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="email">البــريــد الإلـكـترونـي (اختياري)</label>
                                            <input id="email" name="email" type="email" class="form-control"
                                                   value="{{ old('email') }}" placeholder="البريد الالكتروني"
                                                   oninput="validation()">
                                            <span id="text"></span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label>
                                                <input type="checkbox" id="countryToggle" onchange="notLibyan()">
                                                أجـنـبـي الـجـنـسـيـة
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="phone">رقــم الـهـاتـف (اختياري)</label>
                                            <input id="phone" name="phone" type="text" class="form-control"
                                                   value="{{ old('phone') }}" placeholder="رقــم الـهـاتـف">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="birthDate">تاريــخ الـمـيـلاد</label>
                                            <input id="birthDate" name="birth_date" type="text" class="form-control"
                                                   oninput="dateValidation(this)" placeholder="DD-MM-YYYY" required
                                                   value="{{ old('birth_date') }}"
                                                   oninvalid="this.setCustomValidity('الرجاء إدخال تاريخ الـمـيـلاد بالتنسيق DD-MM-YYYY')">
                                            <span id="startD_text"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">الـجـنـس</label>
                                        <select name="gender" class="form-control" required
                                                oninvalid="this.setCustomValidity('الرجاء تحديد جنس الموظف')"
                                                oninput="this.setCustomValidity('')">
                                            <option disabled {{ old('gender')? '' : 'selected' }}>اختيار الجنس</option>
                        <option value="ذكر"  {{ old('gender','ذكر')=='ذكر' ? 'selected':'' }}>ذكر</option>
                        <option value="انثى" {{ old('gender')=='انثى' ? 'selected':'' }}>انثى</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">الحالة الاجتماعية</label>
                                        <select name="marital_status" class="form-control" required
                                                oninvalid="this.setCustomValidity('الرجاء تحديد الحالة الاجتماعية الخاصة للموظف')"
                                                oninput="this.setCustomValidity('')">
                                            <option value="أعزب"   {{ old('marital_status','أعزب')=='أعزب'?'selected':'' }}>أعزب</option>
                                            <option value="متزوج"  {{ old('marital_status')=='متزوج' ?'selected':'' }}>متزوج</option>
                                            <option value="مطلق"   {{ old('marital_status')=='مطلق'  ?'selected':'' }}>مطلق</option>
                                            <option value="أرمل"   {{ old('marital_status')=='أرمل'  ?'selected':'' }}>أرمل</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="country">الـبـلـد</label>
                                            <input id="country" name="country" type="text" class="form-control"
                                                   value="{{ old('country') }}" placeholder="الـبـلـد" required
                                                   oninvalid="this.setCustomValidity('الرجاء ادخال البلد')"
                                                   oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="city">الـمـديـنـة</label>
                                            <input id="city" name="city" type="text" class="form-control"
                                                   value="{{ old('city') }}" placeholder="الـمـديـنـة" required
                                                   oninvalid="this.setCustomValidity('الرجاء ادخال المدينة')"
                                                   oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="street_address">الـمـنـطـقـة (اختياري)</label>
                                            <input id="street_address" name="street_address" type="text"
                                                   class="form-control" value="{{ old('street_address') }}"
                                                   placeholder="الـمـنـطـقـة">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="image">صـورة الـمـوظـف (اختياري)</label>
                                            <input name="image" id="image" type="file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>

                        {{-- البطاقة: البيانات الوظيفية --}}
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">الـبـيـانـات الـوظـيـفـيـة</h4>
                                <div class="row mt-3">

                                    {{-- الإدارة / القسم --}}
                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">الإدارة <span><i class="fa fa-caret-down" aria-hidden="true"></i></span></label>
                                        <select id="section_id"
                                                name="section_id"
                                                class="form-control js-example-basic-single"
                                                lang="ar" required
                                                onchange="handleSectionChange(this.value)"
                                                oninvalid="this.setCustomValidity('الرجاء اختيار إدارة الموظف')"
                                                oninput="this.setCustomValidity('')">
                                            @foreach ($sub as $su)
                                                @if ($su->sub->count() > 0)
                                                    <option value="{{ $su->id }}" {{ old('section_id') == $su->id ? 'selected':'' }}>{{ $su->name }}</option>
                                                    @foreach ($su->sub as $su2)
                                                        <option value="{{ $su2->id }}" {{ old('section_id') == $su2->id ? 'selected':'' }}>
                                                            {{ $su->name }} - {{ $su2->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ $su->id }}" {{ old('section_id') == $su->id ? 'selected':'' }}>{{ $su->name }}</option>
                                                @endif
                                            @endforeach
                                            <option value="new">+ اضافة إدارة / قسم جديدة</option>
                                        </select>
                                    </div>

                                    {{-- نوع التوظيف --}}
                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">نـوع الـتـوظـيـف</label>
                                        <select name="type" id="select_type" class="form-control" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار نوع التوظيف')"
                                                oninput="this.setCustomValidity('')">
                                            <option value="عقد"   {{ old('type')=='عقد'   ? 'selected':'' }}>عقد</option>
                                            <option value="تعيين" {{ old('type','تعيين')=='تعيين' ? 'selected':'' }}>تعيين</option>
                                            <option value="إعارة" {{ old('type')=='إعارة' ? 'selected':'' }}>إعارة</option>
                                            <option value="ندب"   {{ old('type')=='ندب'   ? 'selected':'' }}>ندب</option>
                                        </select>
                                    </div>

                                    {{-- رقم القرار --}}
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="dNumber">رقــم القــرار</label>
                                            <input id="dNumber" name="res_num" type="number"
                                                   value="{{ old('res_num') }}" placeholder="رقم القرار"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    {{-- الوحدة/المقعد الوظيفي --}}
                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">الـوحـدة / المقعد الوظيفي</label>
                                        <select id="unit_staffing_id"
                                                name="unit_staffing_id"
                                                class="form-control"
                                                data-selected="{{ old('unit_staffing_id') }}"
                                                required
                                                disabled>
                                            <option value="">اختر الإدارة/القسم أولاً</option>
                                        </select>
                                        <small id="unitStaffingHelp" class="text-muted d-block mt-1"></small>
                                    </div>

                                    {{-- تاريخ المباشرة --}}
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="startDate">تاريــخ المبــاشرة</label>
                                            <input id="startDate" name="start_date" type="text" class="form-control"
                                                   oninput="dateValidation(this)" placeholder="DD-MM-YYYY" required
                                                   value="{{ old('start_date') }}"
                                                   oninvalid="this.setCustomValidity('الرجاء إدخال تاريخ المباشرة بالتنسيق DD-MM-YYYY')">
                                            <span id="startD_text"></span>
                                        </div>
                                    </div>

                                    {{-- الدرجة --}}
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="score">الدرجــة الحــالية</label>
                                            <input id="score" name="degree" type="number" min="1" max="15"
                                                   value="{{ old('degree') }}" class="form-control" placeholder="الدرجة الوظيفية" required
                                                   oninvalid="this.setCustomValidity('الرجاء ادخال الدرجة الوظيفية')"
                                                   oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>

                                    {{-- الحالة --}}
                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">حـالـة الـمـوظـف</label>
                                        <select name="status" id="status" class="form-control" required
                                                oninvalid="this.setCustomValidity('الرجاء تحديد حـالـة الـمـوظـف')"
                                                oninput="this.setCustomValidity('')">
                                            <option value="يعمل"    {{ old('status','يعمل')=='يعمل' ? 'selected':'' }}>يعمل</option>
                                            <option value="مستقيل"  {{ old('status')=='مستقيل'  ? 'selected':'' }}>مستقيل</option>
                                            <option value="متقاعد"  {{ old('status')=='متقاعد'  ? 'selected':'' }}>متقاعد</option>
                                            <option value="منتقل"   {{ old('status')=='منتقل'   ? 'selected':'' }}>منتقل</option>
                                            <option value="منقطع"   {{ old('status')=='منقطع'   ? 'selected':'' }}>منقطع</option>
                                            <option value="موقوف"   {{ old('status')=='موقوف'   ? 'selected':'' }}>موقوف</option>
                                            <option value="مفصول"   {{ old('status')=='مفصول'   ? 'selected':'' }}>مفصول</option>
                                        </select>
                                    </div>

                                    {{-- تواريخ اختيارية --}}
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="lastdeal">تاريخ الحصول علي الدرجة الحالية (اختياري)</label>
                                            <input id="lastdeal" name="degree_date" value="{{ old('degree_date') }}"
                                                   oninput="dateValidation(this)" placeholder="DD-MM-YYYY"
                                                   type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label>تاريخ استحقاق العلاوة القادمة (اختياري)</label>
                                            <input name="futureBonus" oninput="dateValidation(this)"
                                                   value="{{ old('futureBonus') }}" placeholder="DD-MM-YYYY"
                                                   type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label>تاريخ استحقاق الترقية القادمة (اختياري)</label>
                                            <input name="futurepromotion" oninput="dateValidation(this)"
                                                   value="{{ old('futurepromotion') }}" placeholder="DD-MM-YYYY"
                                                   type="text" class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <br>
                            </div>
                        </div>

                        {{-- يظهر فقط عند اختيار الحالة "مستقيل" --}}
                        <div class="card" id="resignationCard" style="display:none;">
                            <div class="card-body">
                                <h4 class="card-title">بيانات الاستقالة</h4>
                                <div class="row mt-3" id="resignationFields">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="startout_data">تاريخ الاستقالة/الخروج</label>
                                            <input id="startout_data" name="startout_data" type="text"
                                                   class="form-control" oninput="dateValidation(this)"
                                                   placeholder="DD-MM-YYYY">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="archive_char">الحرف المؤرشف</label>
                                            <input id="archive_char" name="archive_char" type="text"
                                                   class="form-control" placeholder="الحرف المؤرشف">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="archive_num">الرقم المؤرشف</label>
                                            <input id="archive_num" name="archive_num" type="number"
                                                   class="form-control" placeholder="الرقم المؤرشف">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- البطاقة: الندب/الإعارة --}}
                        <div class="card" id="showNdb" style="display:none;">
                            <div class="card-body">
                                <h4 class="card-title" id="titlee22">بـيـانـات نـدب/إعـارة الـمـوظـف</h4>
                                <div class="row mt-3">

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="ndb_transfer_decision">رقــم قـرار الـنـدب/إعـارة</label>
                                            <input id="ndb_transfer_decision" name="ndb_transfer_decision" type="text"
                                                   placeholder="رقـم قـرار الـنـدب/إعـارة" class="form-control"
                                                   value="{{ old('ndb_transfer_decision') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="ndb_start">تـاريـخ بـدايـة الـنـدب/إعـارة</label>
                                            <input id="ndb_start" name="ndb_start" oninput="dateValidation(this)"
                                                   placeholder="DD-MM-YYYY" type="text" class="form-control"
                                                   value="{{ old('ndb_start') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="ndb_end">تـاريـخ نـهايـة الـنـدب/إعـارة</label>
                                            <input id="ndb_end" name="ndb_end" oninput="dateValidation(this)"
                                                   placeholder="DD-MM-YYYY" type="text" class="form-control"
                                                   value="{{ old('ndb_end') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="dec_source">مـصـدر الـقـرار</label>
                                            <input id="dec_source" name="dec_source" type="text"
                                                   placeholder="مصدر القرار" class="form-control"
                                                   value="{{ old('dec_source') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="dec_constraints">قـيـود الـقـرار</label>
                                            <input id="dec_constraints" name="dec_constraints"
                                                   value="{{ old('dec_constraints') }}" type="text"
                                                   placeholder="قيود القرار" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="ndb_workplace">مـكـان الـعـمـل الـمـنـتـدب/المُعار منه</label>
                                            <input id="ndb_workplace" name="ndb_workplace"
                                                   value="{{ old('ndb_workplace') }}" type="text"
                                                   placeholder="مكان العمل" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label>صـورة من القرار (اختياري)</label>
                                            <input name="files[]" type="file" class="form-control" multiple>
                                        </div>
                                    </div>

                                </div>
                                <br>
                            </div>
                        </div>

                        {{-- البطاقة: المؤهل العلمي --}}
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">بـيـانـات الـمـؤهـل الـعـلـمـي</h4>
                                <div class="row mt-3">
                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">المؤهــل في القرار</label>
                                        <select name="qualification" class="form-control" required
                                                oninvalid="this.setCustomValidity('الرجاء ادخال المؤهل في القرار')"
                                                oninput="this.setCustomValidity('')">
                                            <option disabled {{ old('qualification')? '' : 'selected' }}>اختيار المؤهــل العلمي</option>
                                            <option value="اعدادي"        {{ old('qualification')=='اعدادي'       ? 'selected':'' }}>اعدادي</option>
                                            <option value="ثانوي"         {{ old('qualification')=='ثانوي'        ? 'selected':'' }}>ثانوي</option>
                                            <option value="دبلوم متوسط"   {{ old('qualification')=='دبلوم متوسط'  ? 'selected':'' }}>دبلوم متوسط</option>
                                            <option value="دبلوم عالي"    {{ old('qualification')=='دبلوم عالي'   ? 'selected':'' }}>دبلوم عالي</option>
                                            <option value="بكالوريوس"     {{ old('qualification','بكالوريوس')=='بكالوريوس' ? 'selected':'' }}>بكالوريوس</option>
                                            <option value="ماجستير"       {{ old('qualification')=='ماجستير'      ? 'selected':'' }}>ماجستير</option>
                                            <option value="دكتوراة"       {{ old('qualification')=='دكتوراة'      ? 'selected':'' }}>دكتوراة</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4 mb-3">
                                        <label class="control-label">التــخــصص <span><i class="fa fa-caret-down" aria-hidden="true"></i></span></label>
                                        <select name="specialty_id" class="form-control js-example-basic-single" lang="ar">
                                            <option value="" disabled {{ old('specialty_id')? '' : 'selected' }}>اختيار التخصص</option>
                                            @foreach ($Specialties as $spec)
                                                <option value="{{ $spec->id }}" {{ old('specialty_id')==$spec->id ? 'selected':'' }}>
                                                    {{ $spec->name }}
                                                </option>
                                            @endforeach
                                            <option value="new">+ اضافة تخصص جديد</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="earnDate">تاريــخ الحــصول على المؤهل</label>
                                            <input id="earnDate" name="due" value="{{ old('due') }}" type="text"
                                                   class="form-control" oninput="dateValidation(this)"
                                                   placeholder="DD-MM-YYYY">
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>

                        {{-- زر الحفظ --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" onclick="last_validation()" class="btn btn-primary waves-effect waves-light">حـفـظ</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection

@section('script')
<script type="text/javascript">
    function last_validation() {
        var requiredInputs = document.querySelectorAll('input[required], select[required]');
        requiredInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                if (input.value.trim() === '') {
                    input.setCustomValidity('الرجاء ملء هذا الحقل');
                } else {
                    input.setCustomValidity('');
                }
            });
        });
    }

    function validation() {
        var form = document.getElementById("form");
        var email = document.getElementById("email").value;
        var text = document.getElementById("text");
        var pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        if (email && email.match(pattern)) {
            form.classList.add("valid");
            form.classList.remove("invalid");
            text.innerHTML = "ايــمــيــل صــحــيــح";
            text.style.color = "#00ff00";
        } else if (email) {
            form.classList.add("invalid");
            form.classList.remove("valid");
            text.innerHTML = "ايميل يجب ان يحتوي علي @ و .com";
            text.style.color = "#ff0000";
        } else {
            text.innerHTML = "";
        }
    }

    function notLibyan() {
        var isChecked = document.getElementById('countryToggle').checked;
        if (isChecked) {
            document.getElementById('Libyan1').style.display = "none";
            document.getElementById('N_id').value = "";
            document.getElementById('N_id').removeAttribute('required');

            document.getElementById('notLibyan1').style.display = "block";
            document.getElementById('non_citizen_ref_no').setAttribute('required', true);
        } else {
            document.getElementById('notLibyan1').style.display = "none";
            document.getElementById('non_citizen_ref_no').value = "";
            document.getElementById('non_citizen_ref_no').removeAttribute('required');

            document.getElementById('Libyan1').style.display = "block";
            document.getElementById('N_id').setAttribute('required', true);
        }
    }

    // إظهار/إخفاء الندب/الإعارة
    document.getElementById('select_type').addEventListener('change', function() {
        const v = this.value;
        const box = document.getElementById('showNdb');
        const reqIds = ['ndb_transfer_decision','ndb_start','ndb_end','dec_source'];
        if (v === 'ندب' || v === 'إعارة') {
            box.style.display = 'block';
            reqIds.forEach(id => document.getElementById(id).setAttribute('required', true));
        } else {
            box.style.display = 'none';
            reqIds.forEach(id => document.getElementById(id).removeAttribute('required'));
            ['ndb_transfer_decision','ndb_start','ndb_end','dec_source','dec_constraints','ndb_workplace'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
        }
    });

    // إظهار/إخفاء حقول الاستقالة عند اختيار "مستقيل"
    function toggleResignationFields() {
        const st = document.getElementById('status').value;
        const card = document.getElementById('resignationCard');
        const outDate = document.getElementById('startout_data');
        if (st === 'مستقيل') {
            card.style.display = 'block';
            if (outDate) outDate.setAttribute('required', true);
        } else {
            card.style.display = 'none';
            if (outDate) outDate.removeAttribute('required');
            ['startout_data','archive_char','archive_num'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
        }
    }
    document.getElementById('status').addEventListener('change', toggleResignationFields);
    document.addEventListener('DOMContentLoaded', toggleResignationFields);

    function N_id_validation() {
        var form = document.getElementById("form");
        var N_id = document.getElementById("N_id").value;
        var text_N_id = document.getElementById("text_N_id");
        var pattern = /^[0-9]*$/;
        if (N_id.length === 12 && pattern.test(N_id)) {
            form.classList.add("valid");
            form.classList.remove("invalid");
            text_N_id.innerHTML = "مــــوافق";
            text_N_id.style.color = "green";
        } else if (N_id.length > 0) {
            form.classList.add("invalid");
            form.classList.remove("valid");
            text_N_id.innerHTML = "رقـــم وطـــني يجب ان يــكون 12 خانة";
            text_N_id.style.color = "#ff0000";
        } else {
            text_N_id.innerHTML = "";
        }
    }

    function name_validation() {
        var input = document.getElementById('fullname');
        var regex = /^[a-zA-Z\s\u0621-\u064A\u0660-\u0669]*$/;
        input.addEventListener('input', function() {
            if (input.value.match(regex) || input.value === "") {
                input.oldValue = input.value;
                input.oldSelectionStart = input.selectionStart;
                input.oldSelectionEnd = input.selectionEnd;
            } else if (input.hasOwnProperty('oldValue')) {
                input.value = input.oldValue;
                input.setSelectionRange(input.oldSelectionStart, input.oldSelectionEnd);
            } else {
                input.value = "";
            }
        });
    }

    // يقبل DD-MM-YYYY أو DD/MM/YYYY أو YYYY-MM-DD أو YYYY/MM/DD
    function dateValidation(inputElement) {
        var dateRegex1 = /^\d{1,2}-\d{1,2}-\d{4}$/;
        var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
        var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/;
        var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/;
        if (dateRegex1.test(inputElement.value) ||
            dateRegex2.test(inputElement.value) ||
            dateRegex3.test(inputElement.value) ||
            dateRegex4.test(inputElement.value)) {
            inputElement.setCustomValidity('');
        } else {
            inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY أو YYYY-MM-DD');
        }
    }

    /* ===================== تحميل الوحدات الوظيفية بحسب الإدارة (AJAX) ===================== */
    document.addEventListener('DOMContentLoaded', function () {
      const sectionSelect   = document.getElementById('section_id');
      const unitSelect      = document.getElementById('unit_staffing_id');
      const helpTxt         = document.getElementById('unitStaffingHelp');
      const preselectedUnit = unitSelect?.dataset?.selected || '';

      function resetUnits(msg = 'اختر الإدارة/القسم أولاً') {
        unitSelect.innerHTML = `<option value="">${msg}</option>`;
        unitSelect.disabled = true;
        if (helpTxt) helpTxt.textContent = '';
      }

      async function loadUnits(sectionId) {
        if (!sectionId || sectionId === 'new') return resetUnits();

        unitSelect.disabled  = true;
        unitSelect.innerHTML = '<option value="">جاري التحميل...</option>';
        if (helpTxt) helpTxt.textContent = '';

        try {
          const res = await fetch(`{{ url('ajax/unit-staffings') }}/${sectionId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin',
            cache: 'no-store'
          });

          const ct = res.headers.get('content-type') || '';
          if (!ct.includes('application/json')) throw new Error('Not JSON');

          const data = await res.json();

          if (!Array.isArray(data) || data.length === 0) {
            return resetUnits('لا توجد وحدات لهذا القسم');
          }

          // نعيد تعبئة القائمة ونمنع اختيار الوحدات الممتلئة إن وُجدت بيانات الحصة
          unitSelect.innerHTML = '<option value="">اختر الوحدة الوظيفية</option>';
          let totalFree = 0;

          for (const u of data) {
            // نتوقع من الـ API (إن توفّر) الحقول: quota, occupied, remaining
            const remaining = typeof u.remaining !== 'undefined'
                              ? Number(u.remaining)
                              : (typeof u.quota !== 'undefined' && typeof u.occupied !== 'undefined'
                                  ? Number(u.quota) - Number(u.occupied)
                                  : undefined);

            const opt = document.createElement('option');
            opt.value = u.id;

            // الاسم الظاهر
            let label = u.name;
            if (u.code) label += ' - ' + u.code;

            // إظهار الحالة (متاح/ممتلئ) إن وُجدت بيانات
            if (typeof remaining !== 'undefined') {
              totalFree += Math.max(0, remaining);
              if (remaining <= 0) {
                opt.disabled = true;             // منع الاختيار
                label += ' (ممتلئ)';
              } else {
                label += ` (متاح: ${remaining})`;
              }
            }
            opt.textContent = label;
            unitSelect.appendChild(opt);
          }

          if (preselectedUnit) unitSelect.value = preselectedUnit;
          unitSelect.disabled = false;

          if (helpTxt && totalFree >= 0) {
            helpTxt.textContent = totalFree > 0
              ? `المقاعد المتاحة في هذه الإدارة: ${totalFree}`
              : 'لا توجد شواغر متاحة في هذه الإدارة.';
          }
        } catch (e) {
          resetUnits('تعذّر تحميل الوحدات');
        }
      }

      // متاح عالميًا للـ onchange في وسم الـ<select>
      window.handleSectionChange = function (val) { loadUnits(val); };

      // دعم Select2 إن وُجد
      if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
        jQuery(document)
          .off('select2:select', '#section_id')
          .on('select2:select', '#section_id', function (e) {
            const id = e.params && e.params.data && e.params.data.id;
            if (id) loadUnits(id);
          })
          .off('change', '#section_id')
          .on('change', '#section_id', function () { loadUnits(this.value); });
      } else {
        sectionSelect.addEventListener('change', function () { loadUnits(this.value); });
      }

      // تحميل أولي عند فتح الصفحة (إن وُجدت قيمة)
      if (sectionSelect.value) {
        loadUnits(sectionSelect.value);
      } else {
        resetUnits();
      }
    });
</script>
@endsection
