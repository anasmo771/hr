<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .menu-title {
        font-size: 13px;
        color: #555;
        padding: 12px 20px 6px;
        text-align: right;
        font-weight: 600;
        border-bottom: 1px solid #eee;
        margin-top: 10px;
    }

    .pc-micon i {
        font-size: 16px;
    }

    .pc-submenu i {
        margin-left: 5px;
        color: #666;
    }
</style>

<ul class="pc-navbar" style="padding-top: 50px;">

    <!-- لوحة التحكم -->
    <li class="menu-title">لوحة التحكم</li>
    <li class="pc-item">
        <a href="{{ route('home') }}" class="pc-link">
            <span class="pc-micon"><i class="fas fa-home"></i></span>
            <span class="pc-mtext">الرئيسية</span>
        </a>
    </li>

    <!-- الموارد البشرية -->
    @canany(['employee-list', 'resignation-list'])
        <li class="menu-title">الموارد البشرية</li>

        @can('employee-list')
            <li class="pc-item pc-hasmenu">
                <a href="#!" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-users"></i></span>
                    <span class="pc-mtext"> الموظفين</span>
                    <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                </a>
                <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="{{ route('employees.index') }}">كل الموظفين</a></li>
                    @can('employee-create')
                        <li class="pc-item"><a class="pc-link" href="{{ route('employees.create') }}">إضافة موظف</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('resignation-list')
            <li class="pc-item">
                <a href="{{ route('resignation.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-hand"></i></span>
                    <span class="pc-mtext">المستقيلين</span>
                </a>
            </li>
        @endcan
    @endcanany

  <!-- الحضور والغياب -->
@canany(['attendance-list', 'absent-list', 'vacation-list'])
    <li class="menu-title">الحضور والغياب</li>

    @can('attendance-list')
        <li class="pc-item">
            <a href="{{ route('attendance.monthly.report') }}" class="pc-link">
                <span class="pc-micon"><i class="fas fa-calendar-alt"></i></span>
                <span class="pc-mtext">التقرير الشهري للحضور</span>
            </a>
        </li>
    @endcan
    @can('attendance-list')
        <li class="pc-item">
            <a href="{{ route('attendance.index') }}" class="pc-link">
                <span class="pc-micon"><i class="fas fa-user-check"></i></span>
                <span class="pc-mtext">تسجيل الحضور والانصراف</span>
            </a>
        </li>
    @endcan
    @can('vacation-list')
            <li class="pc-item">
                <a href="{{ route('vacations.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-calendar-alt"></i></span>
                    <span class="pc-mtext">إدارة الإجازات</span>
                </a>
            </li>
    @endcan
@endcanany


    <!-- الترقيات والمكافآت -->
    @canany(['promotion-list','punishment-list','bonus-list'])
        <li class="menu-title">الترقيات و العقوبات</li>
        @can('promotion-list')
            <li class="pc-item">
                <a href="{{ route('promotion.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-angle-double-up"></i></span>
                    <span class="pc-mtext">الترقيات</span>
                </a>
            </li>
        @endcan
        @can('bonus-list')
            <li class="pc-item">
                <a href="{{ route('bouns.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-coins"></i></span>
                    <span class="pc-mtext">العلاوات</span>
                </a>
            </li>
        @endcan
        @can('punishment-list')
        <li class="pc-item">
            <a href="{{ route('punishments.index') }}" class="pc-link">
                <span class="pc-micon"><i class="fas fa-gavel"></i></span>
                <span class="pc-mtext">العقوبات</span>
            </a>
        </li>
        @endcan 
    @endcanany

    <!--  التدريب والتقييم  -->
    @canany(['course-list', 'task-list', 'feedback-list'])
        <li class="menu-title">  التدريب والتقييم</li>
        @can('feedback-list')
            <li class="pc-item">
                <a href="{{ route('feedback.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-star"></i></span>
                    <span class="pc-mtext">تقارير الكفائة</span>
                </a>
            </li>
        @endcan
        @can('course-list')
            <li class="pc-item">
                <a href="{{ route('courses.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-book"></i></span>
                    <span class="pc-mtext">الدورات</span>
                </a>
            </li>
        @endcan
        @can('task-list')
            <li class="pc-item">
                <a href="{{ route('tasks.index') }}" class="pc-link">
                    <span class="pc-micon"><i class="fas fa-tasks"></i></span>
                    <span class="pc-mtext">التكليفات</span>
                </a>
            </li>
        @endcan
    @endcanany

    <!-- الإعدادات الإدارية -->
    @can('system-list')
        <li class="menu-title">الإعدادات الإدارية</li>

        <li class="pc-item">
            <a class="pc-link" href="{{ route('subSection.index') }}">
                <span class="pc-micon"><i class="fas fa-building"></i></span>
                <span class="pc-mtext">الإدارات</span>
            </a>
        </li>

        <li class="pc-item">
            <a class="pc-link" href="{{ route('Staffing.index') }}">
                <span class="pc-micon"><i class="fas fa-building-user"></i></span>
                <span class="pc-mtext">الملاك الوظيفي</span>
            </a>
        </li>

        <li class="pc-item">
            <a class="pc-link" href="{{ route('Specialties.index') }}">
                <span class="pc-micon"><i class="fas fa-user-graduate"></i></span>
                <span class="pc-mtext">التخصصات</span>
            </a>
        </li>

        <li class="pc-item">
            <a class="pc-link" href="{{ route('admin.logs') }}">
                <span class="pc-micon"><i class="fas fa-history"></i></span>
                <span class="pc-mtext">سجل النظام</span>
            </a>
        </li>
    @endcan

    <!-- الشؤون الإدارية -->
    <li class="menu-title">الشؤون الإدارية</li>
    @can('archive-list')
        <li class="pc-item">
            <a href="{{ route('archives.index', ['id' => 0]) }}" class="pc-link">
                <span class="pc-micon"><i class="fas fa-archive"></i></span>
                <span class="pc-mtext">الأرشيف</span>
            </a>
        </li>
    @endcan

    <li class="pc-item">
        <a href="{{ route('models.index') }}" class="pc-link">
            <span class="pc-micon"><i class="fas fa-file-pdf"></i></span>
            <span class="pc-mtext">النماذج الجاهزة</span>
        </a>
    </li>

    @can('notification-list')
        <li class="pc-item pc-hasmenu">
            <a href="#!" class="pc-link">
                <span class="pc-micon"><i class="fas fa-bell"></i></span>
                <span class="pc-mtext">الإشعارات</span>
                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('notifications.index') }}">كل الإشعارات</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('notifications.create') }}">إرسال إشعار</a></li>
            </ul>
        </li>
    @endcan

<!-- إدارة المستخدمين -->
@can('user-list')
    <li class="menu-title">إدارة المستخدمين</li>

    <li class="pc-item">
        <a class="pc-link" href="{{ route('users.index') }}">
            <span class="pc-micon"><i class="fas fa-people-group"></i></span>
            <span class="pc-mtext">كل المستخدمين</span>
        </a>
    </li>

    <li class="pc-item">
        <a class="pc-link" href="{{ route('users.create') }}">
            <span class="pc-micon"><i class="fas fa-user-plus"></i></span>
            <span class="pc-mtext">إضافة مستخدم</span>
        </a>
    </li>

    <li class="pc-item">
        <a class="pc-link" href="{{ route('roles.index') }}">
            <span class="pc-micon"><i class="fas fa-user-shield"></i></span>
            <span class="pc-mtext">الصلاحيات</span>
        </a>
    </li>
@endcan

</ul>
