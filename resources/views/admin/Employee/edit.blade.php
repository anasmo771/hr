@extends('admin.layout.master')

@section('title')
  <title>تعديل موظف</title>
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
              <li class="breadcrumb-item"><a href="{{ route('employees.edit', [$emp->id]) }}">تعديل بيانات الموظف</a></li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">تعديل بيانات الموظف
                <span style="color: #0d6efd;">{{ $emp->person->name }}</span>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    @include('admin.layout.validation-messages')

    <div class="row">
      <div class="col-12">

        <form action="{{ route('employees.update', [$emp->id]) }}" method="post" enctype="multipart/form-data" id="form">
          @csrf
          @method('PATCH')

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
                           value="{{ old('name', $emp->person->name) }}"
                           oninvalid="this.setCustomValidity('الرجاء ادخال اسم الموظف')"
                           oninput="this.setCustomValidity('')">
                    <span id="Name_text"></span>
                  </div>
                </div>

                {{-- ليبي / غير ليبي --}}
                @if ($emp->person->N_id)
                  <div class="col-sm-4" id="Libyan1">
                    <div class="mb-3">
                      <label for="N_id">الرقــم الوطــني</label>
                      <input id="N_id" name="N_id" min="12" max="12" type="text"
                             onkeyup="N_id_validation()" class="form-control"
                             placeholder="الرقم الوطني"
                             value="{{ old('N_id', $emp->person->N_id) }}" required
                             oninvalid="this.setCustomValidity('الرجاء ادخال الرقم الوطني')"
                             oninput="this.setCustomValidity('')">
                      <span id="text_N_id"></span>
                    </div>
                  </div>
                  <div class="col-sm-4" style="display:none;" id="notLibyan1">
                    <div class="mb-3">
                      <label for="non_citizen_ref_no">رقم الإقامة او الجواز لغير الليبين</label>
                      <input id="non_citizen_ref_no" name="non_citizen_ref_no" type="text"
                             class="form-control"
                             value="{{ old('non_citizen_ref_no', $emp->person->non_citizen_ref_no) }}"
                             placeholder="رقم الإقامة او الجواز لغير الليبين">
                    </div>
                  </div>
                @else
                  <div class="col-sm-4" style="display:none;" id="Libyan1">
                    <div class="mb-3">
                      <label for="N_id">الرقــم الوطــني</label>
                      <input id="N_id" name="N_id" min="12" max="12" type="text"
                             onkeyup="N_id_validation()" class="form-control"
                             placeholder="الرقم الوطني"
                             value="{{ old('N_id', $emp->person->N_id) }}">
                      <span id="text_N_id"></span>
                    </div>
                  </div>
                  <div class="col-sm-4" id="notLibyan1">
                    <div class="mb-3">
                      <label for="non_citizen_ref_no">رقم الإقامة او الجواز لغير الليبين</label>
                      <input id="non_citizen_ref_no" name="non_citizen_ref_no" required
                             type="text" class="form-control"
                             value="{{ old('non_citizen_ref_no', $emp->person->non_citizen_ref_no) }}"
                             placeholder="رقم الإقامة او الجواز لغير الليبين">
                    </div>
                  </div>
                @endif

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="email">البــريــد الإلـكـترونـي (اختياري)</label>
                    <input id="email" name="email" type="email" class="form-control"
                           onkeyup="validation()"
                           value="{{ old('email', $emp->person->email) }}" placeholder="البريد الالكتروني">
                    <span id="text"></span>
                  </div>
                </div>

                <div class="col-12">
                  <div class="mb-3">
                    <label>
                      <input type="checkbox" id="countryToggle"
                             {{ $emp->person->N_id ? '' : 'checked' }} onchange="notLibyan()">
                      أجـنـبـي الـجـنـسـيـة
                    </label>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="phone">رقــم الـهـاتـف (اختياري)</label>
                    <input id="phone" name="phone" type="text" class="form-control"
                           value="{{ old('phone', $emp->person->phone) }}" placeholder="رقــم الـهـاتـف">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="birthDate">تاريــخ الـمـيـلاد</label>
                    <input id="birthDate" name="birth_date" type="text" class="form-control"
                           oninput="dateValidation(this)" placeholder="DD-MM-YYYY" required
                           value="{{ old('birth_date', optional(\Carbon\Carbon::parse($emp->person->birth_date))->format('d-m-Y')) }}"
                           oninvalid="this.setCustomValidity('الرجاء إدخال تاريخ الـمـيـلاد بالتنسيق DD-MM-YYYY')"
                           oninput="this.setCustomValidity('')">
                    <span id="startD_text"></span>
                  </div>
                </div>

                <div class="col-sm-4 mb-3">
                  <label class="control-label">الـجـنـس</label>
                  <select name="gender" class="form-control" required
                          oninvalid="this.setCustomValidity('الرجاء تحديد جنس الموظف')"
                          oninput="this.setCustomValidity('')">
                    <option value="ذكر"  {{ old('gender', $emp->person->gender)=='ذكر' ? 'selected' : '' }}>ذكر</option>
                    <option value="انثى" {{ old('gender', $emp->person->gender)=='انثى' ? 'selected' : '' }}>انثى</option>
                  </select>
                </div>

                <div class="col-sm-4 mb-3">
                  <label class="control-label">الحالة الاجتماعية</label>
                  <select name="marital_status" class="form-control" required
                          oninvalid="this.setCustomValidity('الرجاء تحديد الحالة الاجتماعية الخاصة للموظف')"
                          oninput="this.setCustomValidity('')">
                    @php $ms = old('marital_status', $emp->person->marital_status); @endphp
                    <option value="أعزب"  {{ $ms=='أعزب'  ? 'selected' : '' }}>أعزب</option>
                    <option value="متزوج" {{ $ms=='متزوج' ? 'selected' : '' }}>متزوج</option>
                    <option value="مطلق"  {{ $ms=='مطلق'  ? 'selected' : '' }}>مطلق</option>
                    <option value="أرمل"  {{ $ms=='أرمل'  ? 'selected' : '' }}>أرمل</option>
                  </select>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="country">الـبـلـد</label>
                    <input id="country" name="country" type="text" class="form-control"
                           value="{{ old('country', $emp->person->country) }}" placeholder="الـبـلـد" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال البلد')"
                           oninput="this.setCustomValidity('')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="city">الـمـديـنـة</label>
                    <input id="city" name="city" type="text" class="form-control"
                           value="{{ old('city', $emp->person->city) }}" placeholder="الـمـديـنـة" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال المدينة')"
                           oninput="this.setCustomValidity('')">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="street_address">الـمـنـطـقـة (اختياري)</label>
                    <input id="street_address" name="street_address" type="text" class="form-control"
                           value="{{ old('street_address', $emp->person->street_address) }}" placeholder="الـمـنـطـقـة">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="image">صورة الموظف (اختياري)</label>
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

                {{-- الإدارة/القسم --}}
                <div class="col-sm-4 mb-3">
                  <label class="control-label">الإدارة <span><i class="fa fa-caret-down" aria-hidden="true"></i></span></label>
                  <select id="section_id"
                          name="section_id"
                          class="form-control js-example-basic-single"
                          lang="ar" required
                          oninvalid="this.setCustomValidity('الرجاء اختيار إدارة الموظف')"
                          oninput="this.setCustomValidity('')">
                    <option disabled {{ !$emp->sub_section_id ? 'selected' : '' }}>اختيار الإدارة/القسم</option>

                    @foreach ($sub as $su)
                      @if ($su->sub->count() > 0)
                        {{-- جذر --}}
                        <option value="{{ $su->id }}"
                          @if(!$emp->sub_section_id && isset($emp->section_id) && $emp->section_id == $su->id) selected @endif>
                          {{ $su->name }}
                        </option>
                        {{-- أبناء --}}
                        @foreach ($su->sub as $su2)
                          <option value="{{ $su2->id }}"
                            @if($emp->sub_section_id && $emp->sub_section_id == $su2->id) selected @endif>
                            {{ $su->name }} - {{ $su2->name }}
                          </option>
                        @endforeach
                      @else
                        <option value="{{ $su->id }}"
                          @if(!$emp->sub_section_id && isset($emp->section_id) && $emp->section_id == $su->id) selected @endif>
                          {{ $su->name }}
                        </option>
                      @endif
                    @endforeach
                    <option value="new">+ اضافة إدارة / قسم جديدة</option>
                  </select>
                </div>

                {{-- الوحدة الوظيفية (مقعد) --}}
                <div class="col-sm-4 mb-3">
                  <label class="control-label">الـوحـدة الـوظـيـفـيـة</label>
                  <select id="unit_staffing_id"
                          name="unit_staffing_id"
                          class="form-control"
                          data-selected="{{ old('unit_staffing_id', $emp->unit_staffing_id ?? '') }}"
                          required
                          disabled>
                    <option value="">اختر الإدارة/القسم أولاً</option>
                  </select>
                  <small id="unitStaffingHelp" class="text-muted"></small>
                </div>

                <div class="col-sm-4 mb-3">
                  <label class="control-label">نـوع الـتـوظـيـف</label>
                  <select name="type" id="select_type" class="form-control" required
                          oninvalid="this.setCustomValidity('الرجاء اختيار نوع التوظيف')"
                          oninput="this.setCustomValidity('')">
                    @php $tp = old('type', $emp->type); @endphp
                    <option value="عقد"   {{ $tp=='عقد' ? 'selected' : '' }}>عقد</option>
                    <option value="تعيين" {{ $tp=='تعيين' ? 'selected' : '' }}>تعيين</option>
                    <option value="إعارة" {{ $tp=='إعارة' ? 'selected' : '' }}>إعارة</option>
                    <option value="ندب"   {{ $tp=='ندب' ? 'selected' : '' }}>ندب</option>
                  </select>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="dNumber">رقــم القــرار</label>
                    <input id="dNumber" name="res_num" type="number" value="{{ old('res_num', $emp->res_num) }}"
                           placeholder="رقم القرار" class="form-control">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="startDate">تاريــخ المبــاشرة</label>
                    <input id="startDate" name="start_date" type="text" class="form-control"
                           oninput="dateValidation(this)" placeholder="DD-MM-YYYY" required
                           value="{{ old('start_date', optional(\Carbon\Carbon::parse($emp->start_date))->format('d-m-Y')) }}"
                           oninvalid="this.setCustomValidity('الرجاء إدخال تاريخ المباشرة بالتنسيق DD-MM-YYYY')">
                    <span id="startD_text"></span>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="score">الدرجــة الحــالية</label>
                    <input id="score" name="degree" type="number" min="1" max="15"
                           value="{{ old('degree', $emp->degree) }}" class="form-control" placeholder="الدرجة الوظيفية" required
                           oninvalid="this.setCustomValidity('الرجاء ادخال الدرجة الوظيفية')"
                           oninput="this.setCustomValidity('')">
                  </div>
                </div>

                <div class="col-sm-4 mb-3">
                  <label class="control-label">حـالـة الـمـوظـف</label>
                  <select name="status" id="status" class="form-control" required
                          oninvalid="this.setCustomValidity('الرجاء تحديد حـالـة الـمـوظـف')"
                          oninput="this.setCustomValidity('')">
                    @php $st = old('status', $emp->status); @endphp
                    <option value="يعمل"   {{ $st=='يعمل' ? 'selected' : '' }}>يعمل</option>
                    <option value="مستقيل" {{ $st=='مستقيل' ? 'selected' : '' }}>مستقيل</option>
                    <option value="متقاعد" {{ $st=='متقاعد' ? 'selected' : '' }}>متقاعد</option>
                    <option value="منتقل"  {{ $st=='منتقل' ? 'selected' : '' }}>منتقل</option>
                    <option value="منقطع"  {{ $st=='منقطع' ? 'selected' : '' }}>منقطع</option>
                    <option value="موقوف"  {{ $st=='موقوف' ? 'selected' : '' }}>موقوف</option>
                    <option value="مفصول"  {{ $st=='مفصول' ? 'selected' : '' }}>مفصول</option>
                  </select>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="lastdeal">تاريخ الحصول علي الدرجة الحالية (اختياري)</label>
                    <input id="lastdeal" name="degree_date"
                           value="{{ old('degree_date', $emp->degree_date ? \Carbon\Carbon::parse($emp->degree_date)->format('d-m-Y') : '') }}"
                           oninput="dateValidation(this)" placeholder="DD-MM-YYYY" type="text" class="form-control">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>تاريخ استحقاق العلاوة القادمة (اختياري)</label>
                    <input name="futureBonus" oninput="dateValidation(this)"
                           value="{{ old('futureBonus', $emp->futureBonus ? \Carbon\Carbon::parse($emp->futureBonus)->format('d-m-Y') : '') }}"
                           placeholder="DD-MM-YYYY" type="text" class="form-control">
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>تاريخ استحقاق الترقية القادمة (اختياري)</label>
                    <input name="futurepromotion" oninput="dateValidation(this)"
                           value="{{ old('futurepromotion', $emp->futurepromotion ? \Carbon\Carbon::parse($emp->futurepromotion)->format('d-m-Y') : '') }}"
                           placeholder="DD-MM-YYYY" type="text" class="form-control">
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
              <div class="row mt-3">
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="startout_data">تاريخ الاستقالة/الخروج</label>
                    <input id="startout_data" name="startout_data" type="text" class="form-control"
                           oninput="dateValidation(this)" placeholder="DD-MM-YYYY"
                           value="{{ old('startout_data', $emp->startout_data ? \Carbon\Carbon::parse($emp->startout_data)->format('d-m-Y') : '') }}">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="archive_char">الحرف المؤرشف</label>
                    <input id="archive_char" name="archive_char" type="text" class="form-control"
                           placeholder="الحرف المؤرشف" value="{{ old('archive_char', $emp->archive_char) }}">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="archive_num">الرقم المؤرشف</label>
                    <input id="archive_num" name="archive_num" type="number" class="form-control"
                           placeholder="الرقم المؤرشف" value="{{ old('archive_num', $emp->archive_num) }}">
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- بطاقة تعريفية للندب/الإعارة --}}
          <div class="card" id="showNdb" style="display:none;">
            <div class="card-body">
              <h4 class="card-title">بـيـانـات نـدب/إعـارة الـمـوظـف</h4>
              <p class="text-muted m-0">
                تظهر هذه البطاقة لأن نوع التوظيف هو <strong>ندب/إعارة</strong>.<br>
                تفاصيل الندب/الإعارة تُدار من شاشة/جدول <strong>Ndb_Detail</strong>.
              </p>
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
                    @php $qf = old('qualification', $emp->qualification); @endphp
                    <option value="اعدادي"      {{ $qf=='اعدادي' ? 'selected' : '' }}>اعدادي</option>
                    <option value="ثانوي"        {{ $qf=='ثانوي' ? 'selected' : '' }}>ثانوي</option>
                    <option value="دبلوم متوسط" {{ $qf=='دبلوم متوسط' ? 'selected' : '' }}>دبلوم متوسط</option>
                    <option value="دبلوم عالي"   {{ $qf=='دبلوم عالي' ? 'selected' : '' }}>دبلوم عالي</option>
                    <option value="بكالوريوس"    {{ $qf=='بكالوريوس' ? 'selected' : '' }}>بكالوريوس</option>
                    <option value="ماجستير"      {{ $qf=='ماجستير' ? 'selected' : '' }}>ماجستير</option>
                    <option value="دكتوراة"      {{ $qf=='دكتوراة' ? 'selected' : '' }}>دكتوراة</option>
                  </select>
                </div>

                <div class="col-sm-4 mb-3">
                  <label class="control-label">التــخــصص <span><i class="fa fa-caret-down" aria-hidden="true"></i></span></label>
                  <select name="specialty_id" class="form-control js-example-basic-single" lang="ar">
                    @if ($emp->specialty_id)
                      <option value="{{ $emp->specialty_id }}" selected>{{ $emp->specialty->name }}</option>
                    @endif
                    @foreach ($Specialties as $spec)
                      <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                    @endforeach
                    <option value="new">+ اضافة تخصص جديد</option>
                  </select>
                </div>

                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="earnDate">تاريــخ الحــصول على المؤهل</label>
                    <input id="earnDate" name="due"
                           value="{{ old('due', $emp->due ? \Carbon\Carbon::parse($emp->due)->format('d-m-Y') : '') }}"
                           type="text" class="form-control" oninput="dateValidation(this)" placeholder="DD-MM-YYYY">
                  </div>
                </div>

                {{-- (اختياري) حقل داخلي قديم - إن لم يعد مستخدمًا يمكنك حذفه من الفورم والكونترولر --}}
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label for="sequince">التــسلسل (اختياري)</label>
                    <input id="sequince" name="seq" value="{{ old('seq', $emp->seq) }}" type="text"
                           placeholder="التسلسل" class="form-control">
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

  </div> <!-- pc-content -->
</div>
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
      var nid = document.getElementById('N_id');
      if (nid) { nid.value = ""; nid.removeAttribute('required'); }
      document.getElementById('notLibyan1').style.display = "block";
      document.getElementById('non_citizen_ref_no').setAttribute('required', true);
    } else {
      document.getElementById('notLibyan1').style.display = "none";
      var nc = document.getElementById('non_citizen_ref_no');
      if (nc) { nc.value = ""; nc.removeAttribute('required'); }
      document.getElementById('Libyan1').style.display = "block";
      document.getElementById('N_id').setAttribute('required', true);
    }
  }

  function N_id_validation() {
    var form = document.getElementById("form");
    var N_id = document.getElementById("N_id") ? document.getElementById("N_id").value : '';
    var text_N_id = document.getElementById("text_N_id");
    var pattern = /^[0-9]*$/;
    if (N_id.length === 12 && pattern.test(N_id)) {
      form.classList.add("valid");
      form.classList.remove("invalid");
      if (text_N_id){ text_N_id.innerHTML = "مــــوافق"; text_N_id.style.color = "green"; }
    } else if (N_id.length > 0) {
      form.classList.add("invalid");
      form.classList.remove("valid");
      if (text_N_id){ text_N_id.innerHTML = "رقـــم وطـــني يجب ان يــكون 12 خانة"; text_N_id.style.color = "#ff0000"; }
    } else {
      if (text_N_id){ text_N_id.innerHTML = ""; }
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

  function validateInput() {
    var input = document.getElementById('numericInput');
    if (input) input.value = input.value.replace(/[^\d]/g, '');
  }

  // يقبل DD-MM-YYYY أو DD/MM/YYYY أو YYYY-MM-DD أو YYYY/MM/DD
  function dateValidation(inputElement) {
    var dateRegex1 = /^\d{1,2}-\d{1,2}-\d{4}$/; // DD-MM-YYYY
    var dateRegex2 = /^\d{1,2}\/\d{1,2}\/\d{4}$/; // DD/MM/YYYY
    var dateRegex3 = /^\d{4}-\d{1,2}-\d{1,2}$/; // YYYY-MM-DD
    var dateRegex4 = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // YYYY/MM/DD
    if (dateRegex1.test(inputElement.value) ||
        dateRegex2.test(inputElement.value) ||
        dateRegex3.test(inputElement.value) ||
        dateRegex4.test(inputElement.value)) {
      inputElement.setCustomValidity('');
    } else {
      inputElement.setCustomValidity('الرجاء إدخال التاريخ بالتنسيق DD-MM-YYYY أو DD/MM/YYYY أو YYYY-MM-DD');
    }
  }

  // إظهار/إخفاء حقول الاستقالة حسب الحالة
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
    }
  }
  document.getElementById('status').addEventListener('change', toggleResignationFields);

  // إظهار بطاقة الندب/الإعارة عندما النوع = ندب أو إعارة
  function toggleNdbCard() {
    const v = document.getElementById('select_type').value;
    const box = document.getElementById('showNdb');
    box.style.display = (v === 'ندب' || v === 'إعارة') ? 'block' : 'none';
  }
  document.getElementById('select_type').addEventListener('change', toggleNdbCard);

  // تحميل الوحدات الوظيفية بحسب الإدارة (AJAX) + دعم Select2 + إرسال الكوكيز
  (function () {
    const sectionSelect   = document.getElementById('section_id');
    const unitSelect      = document.getElementById('unit_staffing_id');
    const preselectedUnit = unitSelect?.dataset?.selected || '';

    function resetUnits(msg = 'اختر الإدارة/القسم أولاً') {
      unitSelect.innerHTML = `<option value="">${msg}</option>`;
      unitSelect.disabled = true;
    }

    async function loadUnits(sectionId) {
      if (!sectionId || sectionId === 'new') return resetUnits();

      unitSelect.disabled  = true;
      unitSelect.innerHTML = '<option value="">جاري التحميل...</option>';

      try {
        const res = await fetch(`{{ url('ajax/unit-staffings') }}/${sectionId}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          credentials: 'same-origin',
          cache: 'no-store'
        });

        const ct = res.headers.get('content-type') || '';
        if (!ct.includes('application/json')) throw new Error('Not JSON');

        const data = await res.json();

        if (!Array.isArray(data) || data.length === 0) {
          return resetUnits('لا توجد وحدات لهذا القسم');
        }

        unitSelect.innerHTML = '<option value="">اختر الوحدة الوظيفية</option>';
        for (const u of data) {
          const opt = document.createElement('option');
          opt.value = u.id;
          opt.textContent = u.name + (u.code ? (' - ' + u.code) : '');
          unitSelect.appendChild(opt);
        }

        if (preselectedUnit) unitSelect.value = preselectedUnit;
        unitSelect.disabled = false;
      } catch (e) {
        resetUnits('تعذّر تحميل الوحدات');
      }
    }

    // تغيير عادي
    sectionSelect.addEventListener('change', (e) => loadUnits(e.target.value));

    // دعم Select2 إن كان مفعّلًا
    if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
      const $sel = jQuery(sectionSelect);
      $sel.on('select2:select', function (e) {
        const id = e.params?.data?.id;
        if (id) loadUnits(id);
      });
      $sel.on('select2:clear select2:unselect', function () {
        resetUnits();
      });
    }

    // عند فتح الصفحة
    if (sectionSelect.value) {
      loadUnits(sectionSelect.value);
    } else {
      resetUnits();
    }
  })();

  // تطبيق الحالة المبدئية عند التحميل
  document.addEventListener('DOMContentLoaded', function() {
    toggleResignationFields();
    toggleNdbCard();
    notLibyan();
  });
</script>
@endsection
