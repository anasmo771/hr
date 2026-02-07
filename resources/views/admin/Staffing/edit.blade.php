

                @extends('admin.layout.master')

@section('title')
    <title> تعديل بيانات المسمى الوظيفي <span>{{$Staffing->name}}</span>  </title>
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
                                <li class="breadcrumb-item" aria-current="page">تعديل بيانات المسمى الوظيفي <span>{{$Staffing->name}}</span>  </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">تعديل بيانات المسمى الوظيفي <span>{{$Staffing->name}}</span>  </h2>
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
                                <h4 class="card-title mb-4"></h4>


                                <form action="{{route('Staffing.update',[$Staffing->id])}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <div class="row mb-4">

                                        <div class="col-sm-6">
                                            <div>
                                                <label for="fullname">اسم المسمى الوظيفي</label>
                                                <input id="worktitle" name="name" type="text" value="{{$Staffing->name}}" class="form-control" placeholder=" اسم المسمى الوظيفي..." required oninvalid="this.setCustomValidity('الرجاء ادخال اسم المسمى الوظيفي')" oninput="this.setCustomValidity('')">
                                            </div>
                                        </div>


                                    </div>



                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">حفظ التعديلات</button>
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

@endsection
