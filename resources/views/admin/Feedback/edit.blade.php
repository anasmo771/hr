
            @extends('admin.layout.master')

            @section('title')
            <title>تـعـديـل تقرير الكفاية </title>
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
                                            <li class="breadcrumb-item" aria-current="page">تـعـديـل تقرير الكفاية    </li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <div class="page-header-title">
                                            <h4 class="mb-sm-0 font-size-18">تـعـديـل تقرير الكفاية  <a href="{{ route('EmployeeDetails', [$feed->emp->id]) }}"><span style="color: #3585e4;">{{$feed->emp->person->name}}</span></a> </h4>

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
                                                <h4>{{$feed->emp->person->name}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">الرقــم الوطــني</label>
                                                <h4>{{$feed->emp->person->N_id}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">المؤهــل في القرار</label>
                                                <h4>{{$feed->emp->qualification}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">التــخــصص</label>
                                                @if($feed->emp->specialty)
                                                <h4>{{$feed->emp->specialty->name}}</h4>
                                            @else
                                                <h4> - </h4>
                                            @endif
                                                                                    </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تاريــخ الاستــحقاق</label>
                                                <h4>{{$feed->emp->due}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">الدرجــة الحــالية</label>
                                                <h4>{{$feed->emp->degree}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تاريــخ المبــاشرة</label>
                                                <h4 id ="earnDate">{{$feed->emp->start_date}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">رقــم القــرار</label>
                                                <h4>{{$feed->emp->res_num}}</h4>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 mb-3">
                                            <label class="control-label">الـقـسـم</label>
                                            <h4>{{$feed->emp->section->name}}</h4>
                                        </div>

                                        <div class="col-sm-4 mb-3">
                                            <label class="control-label">جـنـس الـمـوظـف</label>
                                            @if($feed->emp->gender == 0)
                                            <h4>ذكر</h4>
                                            @else
                                            <h4>انثي</h4>
                                            @endif
                                        </div>

                                        <div class="col-sm-4 mb-3">
                                            <label class="control-label">الـحـالـة الاجـتـمـاعـيـة للـمـوظـف</label>
                                            @if($feed->emp->status == 0)
                                            <h4>اعزب</h4>
                                            @else
                                            <h4>متزوج</h4>
                                            @endif
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">تاريخ اخر تسوية وظيفية </label>
                                                <h4>{{$feed->emp->last_sett}}</h4>
                                            </div>
                                        </div>

                                    </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">تـعـديـل بـيـانـات تقرير الكفاية</h4>

                                <form action="{{route('feedback.update',[$feed->id])}}" method="post" enctype="multipart/form-data" id="form">
                                    @csrf
                                    {{ method_field('PATCH') }}
                                    <div class="row mt-3">

                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label for="email">ســنــة التقـيـيـم</label>
                                                <input id="earnDate" name="year" type="year" class="form-control" value="{{$feed->year}}" placeholder="ســنــة التقـيـيـم" required oninvalid="this.setCustomValidity('الرجاء ادخال ســنــة التقـيـيـم')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>
                                        <div class="col-sm-4"> </div>
                                        <div class="col-sm-4"> </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">أداء الـواجـب</label>
                                                <h3> درجة النهاية العظمي <span style="color: #3585e4;">45</span></h3>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة كفاية الموظف (الرئيس المباشر) </label>
                                                <input id="grade11" name="grade11" oninput="grade1()" value="{{$feed->grade11}}" type="number" min="0" max="45" placeholder="درجة كفاية الموظف" class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة كفاية الموظف')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade12" name="grade12" oninput="grade1()" value="{{$feed->grade12}}" type="number" min="0" max="45" placeholder=" درجة الكفاية المعدلة " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة الكفاية المعدلة ')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة الكفاية  (الرئيس الاعلي) </label>
                                                <input id="grade13" name="grade13" type="text" value="{{$feed->textGrade1}}" placeholder="مبررات تعديل درجة الكفاية " class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">المواظبة علي العمل</label>
                                                <h3> درجة النهاية العظمي <span style="color: #3585e4;">15</span></h3>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة كفاية الموظف (الرئيس المباشر) </label>
                                                <input id="grade21" name="grade21" oninput="grade2()" value="{{$feed->grade21}}" type="number" min="0" max="15" placeholder="درجة كفاية الموظف" class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة كفاية الموظف')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade22" name="grade22" oninput="grade2()" value="{{$feed->grade22}}" type="number" min="0" max="15" placeholder=" درجة الكفاية المعدلة " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة الكفاية المعدلة ')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة الكفاية  (الرئيس الاعلي) </label>
                                                <input id="grade23" name="grade23" type="text" value="{{$feed->textGrade2}}" placeholder="مبررات تعديل درجة الكفاية " class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">القدرات والأستعداد الذاتي</label>
                                                <h3> درجة النهاية العظمي <span style="color: #3585e4;">20</span></h3>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة كفاية الموظف (الرئيس المباشر) </label>
                                                <input id="grade31" name="grade31" oninput="grade3()" value="{{$feed->grade31}}" type="number" min="0" max="20" placeholder="درجة كفاية الموظف" class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة كفاية الموظف')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade32" name="grade32" oninput="grade3()" value="{{$feed->grade32}}" type="number" min="0" max="20" placeholder=" درجة الكفاية المعدلة " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة الكفاية المعدلة ')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة الكفاية  (الرئيس الاعلي) </label>
                                                <input id="grade33" name="grade33" type="text" value="{{$feed->textGrade3}}" placeholder="مبررات تعديل درجة الكفاية " class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">العلاقات الأنسانية</label>
                                                <h3> درجة النهاية العظمي <span style="color: #3585e4;">20</span></h3>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة كفاية الموظف (الرئيس المباشر) </label>
                                                <input id="grade41" name="grade41" oninput="grade4()" value="{{$feed->grade41}}" type="number" min="0" max="20" placeholder="درجة كفاية الموظف" class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة كفاية الموظف')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade42" name="grade42" oninput="grade4()" value="{{$feed->grade42}}" type="number" min="0" max="20" placeholder=" درجة الكفاية المعدلة " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة الكفاية المعدلة ')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة الكفاية  (الرئيس الاعلي) </label>
                                                <input id="grade43" name="grade43" type="text" value="{{$feed->textGrade4}}" placeholder="مبررات تعديل درجة الكفاية =" class="form-control">
                                            </div>
                                        </div>


                                          <div class="col-sm-4">
                                              <div class="mb-3">
                                                  <label for="email">درجــة التـقيـيـم</label>
                                                  <input id="score" name="grade" type="number" value="{{$feed->grade}}" min="0" max="100" placeholder="درجــة التـقيـيـم" class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال درجة التقرير الكفاية 0 - 100')" oninput="this.setCustomValidity('')">
                                              </div>
                                          </div>

                                          <div class="col-sm-4">
                                              <div class="mb-3">
                                                  <label for="email">التـقيـيـم الـفـضـي</label>
                                                  <input id="textGrade" name="text_grade" type="text" value="{{$feed->text_grade}}" onkeyup="name_validation()" placeholder=" التـقيـيـم اللـفـضـي " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال التـقيـيـم اللـفـضـي ')" oninput="this.setCustomValidity('')">
                                                  <span id="Name_text"></span>
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
                                          <button type="submit" class="btn btn-primary waves-effect waves-light">تـعـديـل الـتقـييـم</button>
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


        function grade1()
        {
            var g1 = parseInt(document.getElementById("grade11").value);
            var g2 = parseInt(document.getElementById("grade12").value);
            if(g1 == NaN){
                g1 = 0;
            }if(g2 == NaN){
                g2 = 0;
            }
            if(g1+g2 > 45){
                document.getElementById("grade11").value = "";
                document.getElementById("grade12").value = "";
                alert("مجموع تقرير الكفاية أداء الواجب يجب ان لايتجاوز 45");
            }
            sumNow();
        }

        function grade2()
        {
            var g1 = parseInt(document.getElementById("grade21").value);
            var g2 = parseInt(document.getElementById("grade22").value);
            if(g1 == NaN){
                g1 = 0;
            }if(g2 == NaN){
                g2 = 0;
            }
            if(g1+g2 > 15){
                document.getElementById("grade21").value = "";
                document.getElementById("grade22").value = "";
                alert("مجموع تقرير الكفاية المواظبة علي العمل يجب ان لايتجاوز 15");
            }
            sumNow();
        }

        function grade3()
        {
            var g1 = parseInt(document.getElementById("grade31").value);
            var g2 = parseInt(document.getElementById("grade32").value);
            if(g1 == NaN){
                g1 = 0;
            }if(g2 == NaN){
                g2 = 0;
            }
            if(g1+g2 > 20){
                document.getElementById("grade31").value = "";
                document.getElementById("grade32").value = "";
                alert("مجموع تقرير الكفاية القدرات والأستعداد الذاتي يجب ان لايتجاوز 20");
            }
            sumNow();
        }

        function grade4()
        {
            var g1 = parseInt(document.getElementById("grade41").value);
            var g2 = parseInt(document.getElementById("grade42").value);
            if(g1 == NaN){
                g1 = 0;
            }if(g2 == NaN){
                g2 = 0;
            }
            if(g1+g2 > 20){
                document.getElementById("grade41").value = "";
                document.getElementById("grade42").value = "";
                alert("مجموع تقرير الكفاية العلاقات الأنسانية يجب ان لايتجاوز 20");
            }
            sumNow();
        }

        function sumNow() {
    var total = 0;

    // List of all grade elements to check
    var gradeElements = [
        "grade11", "grade12", "grade21", "grade22",
        "grade31", "grade32", "grade41", "grade42"
    ];

    // Loop through all grade elements, parse and add their values
    gradeElements.forEach(function(elementId) {
        var elementValue = document.getElementById(elementId).value;
        var number = parseInt(elementValue, 10); // The second parameter '10' is the radix, ensuring we parse as a decimal number

        if (!isNaN(number)) { // Check if the number is not NaN
            total += number; // If it's a valid number, add it to the total
        }
    });

    // Update the total score
    document.getElementById("score").value = total;

    // Determine the text grade based on the total score
    var textGrade;
    if(total >= 85 && total <= 100){
        textGrade = "ممتاز";
    } else if(total >= 75 && total < 85){
        textGrade = "جيد جدآ";
    } else if(total >= 65 && total < 75){
        textGrade = "جيد";
    } else if(total >= 50 && total < 65){
        textGrade = "متوسط";
    } else {
        textGrade = "ضعيف";
    }

    // Update the text grade
    document.getElementById("textGrade").value = textGrade;
}

            </script>
        @endSection
