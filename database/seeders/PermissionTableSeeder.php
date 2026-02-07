<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        // فضّل دائمًا مسح كاش الصلاحيات قبل/بعد التحديث
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ['name' => 'role-list', 'guard_name' => 'web', 'ar_name' => 'عرض الصلاحيات'],
            ['name' => 'role-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء صلاحية'],
            ['name' => 'role-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل صلاحية'],
            ['name' => 'role-delete', 'guard_name' => 'web', 'ar_name' => 'حذف صلاحية'],

            ['name' => 'user-list', 'guard_name' => 'web', 'ar_name' => 'عرض المستخدمين'],
            ['name' => 'user-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء المستخدمين'],
            ['name' => 'user-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل المستخدمين'],
            ['name' => 'user-delete', 'guard_name' => 'web', 'ar_name' => 'حذف المستخدمين'],

            ['name' => 'employee-list', 'guard_name' => 'web', 'ar_name' => 'عرض الموظفين'],
            ['name' => 'employee-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء موظف'],
            ['name' => 'employee-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل موظف'],
            ['name' => 'employee-delete', 'guard_name' => 'web', 'ar_name' => 'حذف موظف'],

            ['name' => 'absent-list', 'guard_name' => 'web', 'ar_name' => 'عرض الغياب'],
            ['name' => 'absent-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء الغياب'],
            ['name' => 'absent-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل الغياب'],
            ['name' => 'absent-delete', 'guard_name' => 'web', 'ar_name' => 'حذف الغياب'],

            ['name' => 'archive-list', 'guard_name' => 'web', 'ar_name' => 'عرض الأرشيف الوظيفي'],
            ['name' => 'archive-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء الأرشيف الوظيفي'],
            ['name' => 'archive-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل الأرشيف الوظيفي'],
            ['name' => 'archive-delete', 'guard_name' => 'web', 'ar_name' => 'حذف الأرشيف الوظيفي'],

            ['name' => 'system-list', 'guard_name' => 'web', 'ar_name' => 'عرض أعدادات النظام'],
            ['name' => 'system-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء أعدادات النظام'],
            ['name' => 'system-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل أعدادات النظام'],
            ['name' => 'system-delete', 'guard_name' => 'web', 'ar_name' => 'حذف أعدادات النظام'],

            ['name' => 'course-list', 'guard_name' => 'web', 'ar_name' => 'عرض الدورات'],
            ['name' => 'course-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء الدورات'],
            ['name' => 'course-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل الدورات'],
            ['name' => 'course-delete', 'guard_name' => 'web', 'ar_name' => 'حذف الدورات'],

            ['name' => 'feedback-list', 'guard_name' => 'web', 'ar_name' => 'عرض التقييم'],
            ['name' => 'feedback-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء التقييم'],
            ['name' => 'feedback-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل التقييم'],
            ['name' => 'feedback-delete', 'guard_name' => 'web', 'ar_name' => 'حذف التقييم'],

            ['name' => 'notification-list', 'guard_name' => 'web', 'ar_name' => 'عرض الاشعارات'],
            ['name' => 'notification-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء إشعار'],
            ['name' => 'notification-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل إشعار'],
            ['name' => 'notification-delete', 'guard_name' => 'web', 'ar_name' => 'حذف إشعار'],

            ['name' => 'punishment-list', 'guard_name' => 'web', 'ar_name' => 'عرض العقوبات'],
            ['name' => 'punishment-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء العقوبات'],
            ['name' => 'punishment-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل العقوبات'],
            ['name' => 'punishment-delete', 'guard_name' => 'web', 'ar_name' => 'حذف العقوبات'],

            ['name' => 'resignation-list', 'guard_name' => 'web', 'ar_name' => 'عرض المستقليين'],
            ['name' => 'resignation-create', 'guard_name' => 'web', 'ar_name' => 'اضافة مستقيل'],
            ['name' => 'resignation-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل مستقيل'],
            ['name' => 'resignation-delete', 'guard_name' => 'web', 'ar_name' => 'حذف المستقليين'],

            ['name' => 'promotion-list', 'guard_name' => 'web', 'ar_name' => 'عرض الترقيات'],
            ['name' => 'promotion-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء الترقيات'],
            ['name' => 'promotion-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل الترقيات'],
            ['name' => 'promotion-delete', 'guard_name' => 'web', 'ar_name' => 'حذف الترقيات'],

            ['name' => 'bonus-list', 'guard_name' => 'web', 'ar_name' => 'عرض العلاوات'],
            ['name' => 'bonus-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء العلاوات'],
            ['name' => 'bonus-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل العلاوات'],
            ['name' => 'bonus-delete', 'guard_name' => 'web', 'ar_name' => 'حذف العلاوات'],

            ['name' => 'vacation-list', 'guard_name' => 'web', 'ar_name' => 'عرض الإجازات'],
            ['name' => 'vacation-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء الإجازات'],
            ['name' => 'vacation-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل الإجازات'],
            ['name' => 'vacation-delete', 'guard_name' => 'web', 'ar_name' => 'حذف الإجازات'],
            ['name' => 'vacation-approve', 'guard_name' => 'web', 'ar_name' => 'اعتماد الإجازة'],

            ['name' => 'task-list', 'guard_name' => 'web', 'ar_name' => 'عرض التكاليف'],
            ['name' => 'task-create', 'guard_name' => 'web', 'ar_name' => 'أنشاء التكليف'],
            ['name' => 'task-edit', 'guard_name' => 'web', 'ar_name' => 'تعديل التكليف'],
            ['name' => 'task-delete', 'guard_name' => 'web', 'ar_name' => 'حذف التكليف'],
        ];

        // لأن عندك فهرس فريد على (name, ar_name, guard_name)
        // نخلي updateOrCreate بنفس الثلاثي لتجنّب تكرار القيود
        foreach ($permissions as $p) {
            Permission::updateOrCreate(
                ['name' => $p['name'], 'ar_name' => $p['ar_name'], 'guard_name' => $p['guard_name']],
                [] // لو أردت تحديث حقول أخرى أضِفها هنا
            );
        }

        // بعد الإدراج/التحديث، امسح الكاش مرة ثانية
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
