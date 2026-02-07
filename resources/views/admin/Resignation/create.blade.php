

                @extends('admin.layout.master')

                @section('title')
                <title>تعديل موظف</title>
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
                                                <li class="breadcrumb-item" aria-current="page">استقالة الموظف <span style="color: blue;">{{$emp->person->name}}</span></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="page-header-title">
                                                <h2 class="mb-0">استقالة الموظف <span style="color: blue;">{{$emp->person->name}}</span></h2>
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
                                            {{-- <label for="fullname">اســم الــموظــف</label>
                                            @if($emp->person->image)
                                                <h4> <img src="{{ asset(Storage::url($emp->person->image)) }}"
                                                    class="me-1 rounded-circle avatar-sm" alt="user-pic"> {{$emp->person->name}}</h4>
                                            @else
                                                <h4> <img src="{{asset('assets/images/user.png')}}"
                                                    class="me-1 rounded-circle avatar-sm" alt="user-pic"> {{ $emp->person->name }}</h4>
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
                                <h4 class="card-title">تـقـديـم اسـتـقـالـة الـمـوظـف</h4>

                                <!-- <p class="card-title-desc">Fill all information below</p> -->

                                <form action="{{route('resignation.update',[$emp->id])}}" id="form" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @METHOD('Patch')
                                <div class="row mt-3">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="email">تاريــخ الإستقالة</label>
                                            <input id="outtDate" name="startout_data" type="date" value="" oninput="startDate_validation()" class="form-control" placeholder="تاريخ الإستقالة" required oninvalid="this.setCustomValidity('الرجاء ادخال تاريخ الإستقالة')" oninput="this.setCustomValidity('')">
                                            <span id="startD_text"></span>
                                        </div>

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="ch">الحرف  مؤرشف</label>
                                            <input id="ch" name="ch" minlength="1" max="1" maxlength="1" type="text" placeholder="الحرف مؤرشف " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال الحرف ')" oninput="this.setCustomValidity('')">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="num">رقم المؤرشف</label>
                                            <input id="num" name="num" min="1"  type="number" placeholder="الرقم المؤرشف" class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال الرقم مخزن')" oninput="this.setCustomValidity('')">
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
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"> إضـافـة</button>
                                    <!-- <button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button> -->
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

function startDate_validation(){
var form = document.getElementById("form");
var outtDate = document.getElementById("outtDate").value;
var earnDate = document.getElementById("earnDate").innerHTML;
var text = document.getElementById("startD_text");

outtDate = new Date(outtDate);
earnDate =  new Date(earnDate);

if(outtDate > earnDate){
form.classList.add("valid");
form.classList.remove("invalid");

text.innerHTML = "مـــوافـــق";
text.style.color ="#00ff00";

}else{
form.classList.add("invalid");
form.classList.remove("valid");

text.innerHTML = "تاريخ الاستقالة لايمكن ان يكون قبل تاريخ المباشرة";
text.style.color ="#ff0000";

}
}

</script>

@endSection
