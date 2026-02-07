
                @extends('admin.layout.master')


@section('title')
<title>إضافة اشعار</title>
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
                                <li class="breadcrumb-item"><a href="#">إضــافــة إشــعــار جـديـد </a></li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">إضــافــة إشــعــار جـديـد </h2>
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

                                <form action="{{route('notifications.store')}}" method="post">
                                @csrf
                                    <div class="row mb-4">
                                        <div class="col-lg-6">
                                            <label for="worktitle" class="col-form-label col-lg-2" style="font-size:100%;">عــنــوان</label>
                                            <input id="worktitle" name="title" type="text" class="form-control" placeholder="عــنــوان الإشــعــار" required oninvalid="this.setCustomValidity('الرجاء ادخال عنوان خاص بالاشعار')" oninput="this.setCustomValidity('')">
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="worktitle" class="col-form-label col-lg-2" style="font-size:100%;">المستقبل</label>
                                            <select class="form-control select" name="role">
                                                <option selected value="1">الكل</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->name}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mb-4">

                                        <div class="col-lg-6">
                                            <label for="worktitle" class="col-form-label col-lg-2" style="font-size:100%;">الأهـمـيـة</label>
                                            <select class="form-control select" name="priority">
                                                <option value="1">ضعيف</option>
                                                <option value="2" selected>متوسط</option>
                                                <option value="3">مهم</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="worktitle" class="col-form-label" style="font-size:100%;">وصــف الاشــعــار</label>
                                            <textarea class="form-control" id="description" name="desc" rows="2" placeholder="الوصف"></textarea>
                                        </div>

                                    </div>



                                    <!-- <div class="row mb-4">
                                        <label for="projectbudget" class="col-form-label col-lg-2">Budget</label>
                                        <div class="col-lg-10">
                                            <input id="projectbudget" name="projectbudget" type="text" placeholder="Enter Project Budget..." class="form-control">
                                        </div>
                                    </div> -->

                                <!-- <div class="row mb-4">
                                    <label class="col-form-label col-lg-2">Attached Files</label>
                                    <div class="col-lg-10">
                                        <form action="/" method="post" class="dropzone">
                                            <div class="fallback">
                                                <input name="file" type="file" multiple />
                                            </div>

                                            <div class="dz-message needsclick">
                                                <div class="mb-3">
                                                    <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                                </div>

                                                <h4>Drop files here or click to upload.</h4>
                                            </div>
                                        </form>
                                    </div>
                                </div> -->
                                <div class="row justify-content-end">
                                    <div class="col-lg-10">

                                        <button type="submit" class="btn btn-primary"> <i></i>إرســال</button>
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
