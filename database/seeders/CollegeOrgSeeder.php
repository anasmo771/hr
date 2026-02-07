<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\subSection;
use App\Models\Staffing;

// لو أنشأت الموديل الجديد:
use App\Models\UnitStaffing;

class CollegeOrgSeeder extends Seeder
{
    /** @var bool */
    protected $hasMetaCols;
    /** @var bool */
    protected $hasUnitStaffings;

    public function run(): void
    {
        $this->hasMetaCols = Schema::hasColumn('sub_sections','unit_type')
            && Schema::hasColumn('sub_sections','sort_order')
            && Schema::hasColumn('sub_sections','code');

        $this->hasUnitStaffings = Schema::hasTable('unit_staffings');

        // 1) الجذر: الكلية
        $college = $this->createUnit('كلية تقنية المعلومات', null, 'college', 0, 'CIT');

        // 2) وحدات عليا
        $deanOffice       = $this->createUnit('مكتب العميد',             $college, 'office',     1);
        $viceDeanOffice   = $this->createUnit('مكتب وكيل الكلية',        $college, 'office',     2);
        $registrar        = $this->createUnit('مكتب المسجل',             $college, 'office',     3);
        $quality          = $this->createUnit('قسم الجودة وتقييم الأداء', $college, 'department', 4);
        $researchTraining = $this->createUnit('قسم البحوث والاستشارات والتدريب', $college, 'department', 5);
        $gradStudies      = $this->createUnit('مكتب الدراسات العليا',    $college, 'office',     6);
        $facultyAffairs   = $this->createUnit('مكتب شؤون أعضاء هيئة التدريس', $college, 'office', 7);
        $adminFinance     = $this->createUnit('الشؤون الإدارية والمالية', $college, 'department', 8);

        // 3) وحدات تابعة للمسجّل
        $admissions = $this->createUnit('القبول والتسجيل',     $registrar, 'section', 1);
        $exams      = $this->createUnit('الدراسة والامتحانات', $registrar, 'section', 2);
        $studentAct = $this->createUnit('النشاط الطلابي',      $registrar, 'section', 3);
        $alumni     = $this->createUnit('الخريجون',            $registrar, 'section', 4);

        // 4) وحدات تابعة للشؤون الإدارية والمالية
        $adminServices = $this->createUnit('الشؤون الإدارية والخدمات', $adminFinance, 'section', 1);
        $financeStore  = $this->createUnit('الشؤون المالية والمخازن',  $adminFinance, 'section', 2);

        // 5) قاموس المسمّيات (Staffings)
        $roles = [
            'عميد', 'وكيل الكلية', 'مسجل الكلية', 'مدير مكتب', 'رئيس قسم',
            'منسق برامج', 'مسؤول كنترول', 'مدقق درجات', 'مدخل بيانات',
            'موظف قبول وتسجيل', 'موظف أرشيف', 'محاسب', 'أمين مخزن',
            'أمين خدمات', 'مسؤول مشتريات', 'أخصائي جودة', 'مدقق داخلي',
        ];
        $roleIds = [];
        foreach ($roles as $r) {
            $roleIds[$r] = Staffing::firstOrCreate(['name' => $r])->id;
        }

        // 6) ربط الملاك بالوحدات (إن كان جدول unit_staffings موجودًا)
        if ($this->hasUnitStaffings && class_exists(UnitStaffing::class)) {
            // مكتب العميد
            $this->pos($deanOffice,     $roleIds['عميد'],           1, true, 1);
            $this->pos($deanOffice,     $roleIds['مدير مكتب'],      1, false, 2);

            // مكتب وكيل الكلية
            $this->pos($viceDeanOffice, $roleIds['وكيل الكلية'],    1, true, 1);

            // مكتب المسجّل + فروعه
            $this->pos($registrar,      $roleIds['مسجل الكلية'],    1, true, 1);

            $this->pos($admissions, $roleIds['موظف قبول وتسجيل'], 3, false, 1);
            $this->pos($exams,      $roleIds['مسؤول كنترول'],     1, false, 1);
            $this->pos($exams,      $roleIds['مدقق درجات'],       1, false, 2);
            $this->pos($exams,      $roleIds['مدخل بيانات'],       2, false, 3);
            $this->pos($alumni,     $roleIds['موظف أرشيف'],        2, false, 1);

            // الجودة
            $this->pos($quality, $roleIds['رئيس قسم'],   1, true,  1);
            $this->pos($quality, $roleIds['أخصائي جودة'], 2, false, 2);
            $this->pos($quality, $roleIds['مدقق داخلي'],  1, false, 3);

            // الدراسات العليا
            $this->pos($gradStudies,   $roleIds['مدير مكتب'],   1, true, 1);
            $this->pos($gradStudies,   $roleIds['منسق برامج'],  2, false,2);

            // شؤون أعضاء هيئة التدريس
            $this->pos($facultyAffairs, $roleIds['مدير مكتب'],  1, true, 1);
            $this->pos($facultyAffairs, $roleIds['موظف أرشيف'], 1, false,2);

            // الشؤون الإدارية والخدمات
            $this->pos($adminServices, $roleIds['رئيس قسم'],   1, true, 1);
            $this->pos($adminServices, $roleIds['أمين خدمات'], 2, false,2);

            // الشؤون المالية والمخازن
            $this->pos($financeStore, $roleIds['رئيس قسم'],     1, true, 1);
            $this->pos($financeStore, $roleIds['محاسب'],         2, false,2);
            $this->pos($financeStore, $roleIds['أمين مخزن'],     1, false,3);
            $this->pos($financeStore, $roleIds['مسؤول مشتريات'], 1, false,4);
        }
    }

    // ===== Helpers =====

    /** إنشاء/جلب وحدة (قسم/مكتب..) مع دعم أعمدة meta إن وُجدت. */
    protected function createUnit(string $name, ?subSection $parent, string $type, int $sort, ?string $code=null): subSection
    {
        $attrs = ['name' => $name, 'parent_id' => $parent?->id];
        $meta  = [];

        if ($this->hasMetaCols) {
            $meta['unit_type']  = $type;
            $meta['sort_order'] = $sort;
            $meta['code']       = $code;
        }

        return subSection::firstOrCreate($attrs, $meta);
    }

    /** إضافة موضع ملاك للوحدة */
    protected function pos(subSection $unit, int $staffingId, int $quota=1, bool $isManager=false, int $sort=0, ?string $title=null, ?string $notes=null): void
    {
        UnitStaffing::firstOrCreate(
            [
                'unit_id'     => $unit->id,
                'staffing_id' => $staffingId,
                'title'       => $title,
            ],
            [
                'quota'       => $quota,
                'is_manager'  => $isManager,
                'sort_order'  => $sort,
                'notes'       => $notes,
            ]
        );
    }
}
