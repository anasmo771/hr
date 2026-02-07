@extends('admin.layout.master')

@section('title')
    <title> اضافة صلاحية جديدة </title>
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
                                <li class="breadcrumb-item" aria-current="page">اضافة صلاحية جديدة</li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">اضافة صلاحية جديدة</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @include('admin.layout.validation-messages')

            <!-- [ Main Content ] start -->
            <form action="{{ route('roles.store') }}" method="post" id="form">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">بيانات الصلاحية</h4>
                        <div class="row mt-3">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label for="role-name">اســم الصلاحية</label>
                                    <input id="role-name" name="name" type="text" class="form-control" placeholder="اسم الصلاحية" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    // Define the new standalone permission for the new system
                    $attendancePermission = (object)[
                        'id' => 'attendance-list',
                        'ar_name' => 'عرض نظام الحضور والانصراف',
                        'name' => 'attendance-list'
                    ];

                    // Group other permissions by module name (the part before '-')
                    $groupedPermissions = $permission->groupBy(function ($item, $key) {
                        return explode('-', $item->name)[0];
                    });
                    
                    // Define display names for groups
                    $groupNames = [
                        'employee' => 'صلاحيات الموظفين',
                        'absent' => 'صلاحيات الغياب (القديم)',
                        'punishment' => 'صلاحيات العقوبات',
                        'feedback' => 'صلاحيات تقارير الكفاية',
                        'course' => 'صلاحيات الدورات',
                        'task' => 'صلاحيات التكاليف',
                        'promotion' => 'صلاحيات الترقيات',
                        'bonus' => 'صلاحيات العلاوات',
                        'vacation' => 'صلاحيات الإجازات',
                        'notification' => 'صلاحيات الإشعارات',
                        'system' => 'صلاحيات النظام',
                        'role' => 'صلاحيات الأدوار',
                        'user' => 'صلاحيات المستخدمين',
                        'archive' => 'صلاحيات الأرشيف',
                        'model' => 'صلاحيات النماذج',
                        'resignation' => 'صلاحيات المستقلين',
                    ];
                @endphp

                <!-- New Attendance System Permissions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">صلاحيات الحضور والانصراف</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permission[]" value="{{ $attendancePermission->id }}" id="role-{{ $attendancePermission->id }}">
                                    <label class="form-check-label" for="role-{{ $attendancePermission->id }}">
                                        {{ $attendancePermission->ar_name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Other System Permissions Grouped -->
                @foreach($groupedPermissions as $groupKey => $permissionsInGroup)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ $groupNames[$groupKey] ?? 'صلاحيات متنوعة' }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                     <div class="form-check">
                                        <input class="form-check-input select-all" type="checkbox" id="select-all-{{$groupKey}}">
                                        <label class="form-check-label fw-bold" for="select-all-{{$groupKey}}">
                                            تحديد الكل
                                        </label>
                                    </div>
                                    <hr>
                                </div>
                                @foreach($permissionsInGroup as $value)
                                    <div class="col-md-3">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permission[]" value="{{ $value->id }}" id="role-{{ $value->id }}">
                                            <label class="form-check-label" for="role-{{ $value->id }}">
                                                {{ $value->ar_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="row mt-3 mb-4">
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light w-100">حفظ الصلاحية</button>
                    </div>
                </div>
            </form>
        </div> <!-- container-fluid -->
    </div> <!-- End Page-content -->
@endsection

@section('script')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Logic for "Select All" checkboxes
        document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                // Find the parent card body
                let cardBody = this.closest('.card-body');
                // Find all permission checkboxes within that card
                cardBody.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });

        // Logic to update "Select All" if all individual boxes are checked/unchecked
        document.querySelectorAll('.permission-checkbox').forEach(function(permissionCheckbox) {
            permissionCheckbox.addEventListener('change', function() {
                let cardBody = this.closest('.card-body');
                let allCheckboxes = cardBody.querySelectorAll('.permission-checkbox');
                let selectAll = cardBody.querySelector('.select-all');
                
                // Check if all checkboxes in the group are checked
                let allChecked = Array.from(allCheckboxes).every(c => c.checked);
                selectAll.checked = allChecked;
            });
        });
    });
</script>
@endsection
