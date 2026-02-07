@extends('admin.layout.master')

@section('title')
    <title> بـيـانـات الصلاحية </title>
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
                                <li class="breadcrumb-item" aria-current="page">بـيـانـات الصلاحية</li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">بيانات الصلاحية <span style="color: blue;">{{$role->name}}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->
            <div class="row mt-3">
                <div class="col-12">
                    <form action="{{route('roles.update',[$role->id])}}" method="post" enctype="multipart/form-data" id="form">
                        @csrf
                        @method('PATCH')

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">بيانات الصلاحية</h4>
                                @include('includes.messages')
                                <div class="row mt-3">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label for="fullname">اســم الصلاحية</label>
                                            <input id="fullname" name="name" required oninvalid="this.setCustomValidity('الرجاء ادخال اسم الصلاحية')"
                                            oninput="this.setCustomValidity('')" value="{{$role->name}}" type="text" class="form-control" placeholder="اسم الصلاحية" required>
                                            <span id="Name_text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">اذونـات الـصـلاحـيـة</h4>
                                <div class="row">
                                    {{-- Add the new permission to the list --}}
                                    @php
                                        $newPermission = (object)['id' => 'attendance-list', 'ar_name' => 'عرض نظام الحضور والانصراف', 'name' => 'attendance-list'];
                                        if (!$permission->firstWhere('name', 'attendance-list')) {
                                            $permission->prepend($newPermission);
                                        }
                                        $count = 0;
                                    @endphp
                                    
                                    @foreach($permission as $index => $value)
                                    @if ($count % 12 == 0)
                                    @if ($count != 0)
                                </div> <!-- Close the previous row -->
                                @endif
                                <div class="row mt-3"> <!-- Open a new row every 12 items -->
                                    @endif
                                    @if ($count % 4 == 0)
                                    <div class="col-sm-4">
                                        <div class="border p-3 mb-3">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input select-all" type="checkbox" id="selectAll{{ floor($count / 4) }}">
                                                <label class="form-check-label" for="selectAll{{ floor($count / 4) }}">
                                                    تحديد الكل
                                                </label>
                                            </div>
                                            @endif
                                            <div class="mb-3">
                                                <input class="form-check-input permission-checkbox" type="checkbox" name="permission[]" {{ $rolePermissions->contains('id', $value->id) ? 'checked' : '' }} value="{{ $value->id }}" id="role{{ $value->id }}">
                                                <label for="role{{ $value->id }}">{{ $value->ar_name }}</label>
                                            </div>
                                            @if (($count + 1) % 4 == 0 || $loop->last)
                                        </div>
                                    </div>
                                    @endif
                                    @php $count++; @endphp
                                    @endforeach
                                </div> <!-- Close the last row -->
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-3">
                                <button type="submit"
                                class="btn btn-primary waves-effect waves-light">حفظ التعديلات</button>                                    </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                let group = this.closest('.col-sm-4');
                group.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });

        document.querySelectorAll('.permission-checkbox').forEach(function(permissionCheckbox) {
            permissionCheckbox.addEventListener('change', function() {
                let group = this.closest('.col-sm-4');
                let allChecked = Array.from(group.querySelectorAll('.permission-checkbox')).every(c => c.checked);
                group.querySelector('.select-all').checked = allChecked;
            });
        });
    });
</script>
@endsection
