
            @extends('admin.layout.master')

            @section('title')
            <title>تقـيـيـم جـديـد</title>
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
                                            <li class="breadcrumb-item" aria-current="page">اضـافـة تقرير الكفاية للـمـوظـف     </li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <div class="page-header-title">
                                            <h4 class="mb-sm-0 font-size-18">اضـافـة تقرير الكفاية للـمـوظـف  </h4>

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
                                <h4 class="card-title">تـقـديـم بـيـانـات الـتقرير الكفاية</h4>


                                <form action="{{route('storeFeedback',[0])}}" method="post" enctype="multipart/form-data" id="form">
                                    @csrf
                                    <div class="row mt-3">

                                        <div class="col-sm-4">
                                            <label for="worktitle" class="col-form-label col-lg-4">الـمـوظــف  <span><i class="fa fa-caret-down" aria-hidden="true"></i></span> </label>
                                            <select name="emp_id" class="form-control js-example-basic-single"
                                                multiple="multiple" lang="ar" required
                                                oninvalid="this.setCustomValidity('الرجاء اختيار الموظف')"
                                                oninput="this.setCustomValidity('')">
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->person->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-4 mt-4">
                                            <div class="mb-3">
                                                <label for="email">ســنــة التقـيـيـم</label>
                                                <input id="earnDate" name="year" type="year"  value="{{now()->format('Y')}}" class="form-control" placeholder="ســنــة التقـيـيـم" required oninvalid="this.setCustomValidity('الرجاء ادخال ســنــة التقـيـيـم')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>

                                        <div class="col-sm-4" style="padding-bottom: 100px;">
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">أداء الـواجـب</label>
                                                <h3> درجة النهاية العظمي <span style="color: #3585e4;">45</span></h3>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة كفاية الموظف (الرئيس المباشر) </label>
                                                <input id="grade11" name="grade11" oninput="grade1()" type="number" min="0" max="45" placeholder="درجة كفاية الموظف" class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade12" name="grade12" oninput="grade1()" type="number" min="0" max="45" placeholder=" درجة الكفاية المعدلة " class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة كفاية  (الرئيس الاعلي) </label>
                                                <input id="grade13" name="grade13" type="text" placeholder="مبررات تعديل درجة كفاية " class="form-control">
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
                                                <input id="grade21" name="grade21" oninput="grade2()" type="number" min="0" max="15" placeholder="درجة كفاية الموظف" class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade22" name="grade22" oninput="grade2()" type="number" min="0" max="15" placeholder=" درجة الكفاية المعدلة " class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة كفاية  (الرئيس الاعلي) </label>
                                                <input id="grade23" name="grade23" type="text" placeholder="مبررات تعديل درجة كفاية " class="form-control">
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
                                                <input id="grade31" name="grade31" oninput="grade3()" type="number" min="0" max="20" placeholder="درجة كفاية الموظف" class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade32" name="grade32" oninput="grade3()" type="number" min="0" max="20" placeholder=" درجة الكفاية المعدلة " class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة كفاية  (الرئيس الاعلي) </label>
                                                <input id="grade33" name="grade33" type="text" placeholder="مبررات تعديل درجة كفاية " class="form-control">
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
                                                <input id="grade41" name="grade41" oninput="grade4()" type="number" min="0" max="20" placeholder="درجة كفاية الموظف" class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">درجة الكفاية المعدلة (الرئيس الاعلي) </label>
                                                <input id="grade42" name="grade42" oninput="grade4()" type="number" min="0" max="20" placeholder=" درجة الكفاية المعدلة " class="form-control"  value="0">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="mb-3">
                                                <label for="email">مبررات تعديل درجة كفاية  (الرئيس الاعلي) </label>
                                                <input id="grade43" name="grade43" type="text" placeholder="مبررات تعديل درجة كفاية " class="form-control">
                                            </div>
                                        </div>



                                          <div class="col-sm-4">
                                              <div class="mb-3">
                                                  <label for="email">درجــة التـقيـيـم</label>
                                                  <input id="score" name="grade" value="0" oninput="sumNow()" type="number" placeholder="درجــة التـقيـيـم" class="form-control">
                                              </div>
                                          </div>

                                          <div class="col-sm-4">
                                              <div class="mb-3">
                                                  <label for="email">التـقيـيـم</label>
                                                  <input id="textGrade" name="text_grade" type="text" value="ضعيف" onkeyup="name_validation()" placeholder=" التـقيـيـم اللـفـضـي " class="form-control" required oninvalid="this.setCustomValidity('الرجاء ادخال التـقيـيـم اللـفـضـي ')" oninput="this.setCustomValidity('')">
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
                                          <button type="submit" class="btn btn-primary waves-effect waves-light">إضــافـة الـتقـييـم</button>
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
            console.log(g1+g2);
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
