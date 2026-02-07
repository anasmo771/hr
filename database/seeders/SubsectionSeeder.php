<?php

namespace Database\Seeders;

use App\Models\subSection;

use Illuminate\Database\Seeder;

class SubsectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            // 1 - المركز الرئيسي
            subSection::create([
                'name' => 'المركز الرئيسي',
            ]);

            // 2 - إدارة الشؤون الإدارية
            subSection::create([
                'name' => 'إدارة الشؤون الإدارية',
            ]);

            // 3 - إدارة التعاون الفني
            subSection::create([
                'name' => 'إدارة التعاون الفني',
            ]);

            // 4 - إدارة الموارد البشرية
            subSection::create([
                'name' => 'إدارة الموارد البشرية',
            ]);

            // 5 - إدارة العلاقات العامة
            subSection::create([
                'name' => 'إدارة العلاقات العامة',
            ]);

            // 6 - إدارة المشتريات
            subSection::create([
                'name' => 'إدارة المشتريات',
            ]);

            // 7 - إدارة المالية والمحاسبة
            subSection::create([
                'name' => 'إدارة المالية والمحاسبة',
            ]);

            // 8 - إدارة الصيانة
            subSection::create([
                'name' => 'إدارة الصيانة',
            ]);

            // 9 - إدارة المخزون
            subSection::create([
                'name' => 'إدارة المخزون',
            ]);

            // 10 - إدارة المشاريع
            subSection::create([
                'name' => 'إدارة المشاريع',
            ]);

            // 11 - إدارة تكنولوجيا المعلومات
            subSection::create([
                'name' => 'إدارة تكنولوجيا المعلومات',
            ]);

            // 12 - إدارة التدقيق الداخلي
            subSection::create([
                'name' => 'إدارة التدقيق الداخلي',
            ]);

            // 13 - إدارة التسويق والعلاقات التجارية
            subSection::create([
                'name' => 'إدارة التسويق والعلاقات التجارية',
            ]);

            // 14 - إدارة الخدمات القانونية
            subSection::create([
                'name' => 'إدارة الخدمات القانونية',
            ]);

            // 15 - إدارة المرافق
            subSection::create([
                'name' => 'إدارة المرافق',
            ]);

            // 16 - إدارة التخطيط الاستراتيجي
            subSection::create([
                'name' => 'إدارة التخطيط الاستراتيجي',
            ]);

            // 17 - إدارة التواصل المؤسسي
            subSection::create([
                'name' => 'إدارة التواصل المؤسسي',
            ]);


            // أقسام إدارة الشؤون الإدارية
            subSection::create([
                'name' => 'قسم الإدارة العامة',
                'parent_id' => '2',
            ]);

            subSection::create([
                'name' => 'قسم العلاقات الداخلية',
                'parent_id' => '2',
            ]);

            subSection::create([
                'name' => 'قسم التنسيق الإداري',
                'parent_id' => '2',
            ]);

            // أقسام إدارة التعاون الفني
            subSection::create([
                'name' => 'قسم التنسيق الفني',
                'parent_id' => '3',
            ]);

            subSection::create([
                'name' => 'قسم إدارة المشاريع التقنية',
                'parent_id' => '3',
            ]);

            subSection::create([
                'name' => 'قسم الدعم الفني',
                'parent_id' => '3',
            ]);

            // أقسام إدارة الموارد البشرية
            subSection::create([
                'name' => 'قسم التوظيف والتعيينات',
                'parent_id' => '4',
            ]);

            subSection::create([
                'name' => 'قسم التدريب والتطوير',
                'parent_id' => '4',
            ]);

            subSection::create([
                'name' => 'قسم الرواتب والمزايا',
                'parent_id' => '4',
            ]);

            // أقسام إدارة العلاقات العامة
            subSection::create([
                'name' => 'قسم الإعلام والعلاقات العامة',
                'parent_id' => '5',
            ]);

            subSection::create([
                'name' => 'قسم الفعاليات والمناسبات',
                'parent_id' => '5',
            ]);

            subSection::create([
                'name' => 'قسم العلاقات مع وسائل الإعلام',
                'parent_id' => '5',
            ]);

            // أقسام إدارة المالية والمحاسبة
            subSection::create([
                'name' => 'قسم المحاسبة',
                'parent_id' => '7',
            ]);

            subSection::create([
                'name' => 'قسم الحسابات الدائنة',
                'parent_id' => '7',
            ]);

            subSection::create([
                'name' => 'قسم الحسابات المدينة',
                'parent_id' => '7',
            ]);

            // أقسام إدارة المشاريع
            subSection::create([
                'name' => 'قسم المشاريع الكبرى',
                'parent_id' => '10',
            ]);

            subSection::create([
                'name' => 'قسم المشاريع الصغيرة',
                'parent_id' => '10',
            ]);

            subSection::create([
                'name' => 'قسم متابعة المشاريع',
                'parent_id' => '10',
            ]);

            // أقسام إدارة تكنولوجيا المعلومات
            subSection::create([
                'name' => 'قسم تطوير البرمجيات',
                'parent_id' => '11',
            ]);

            subSection::create([
                'name' => 'قسم الدعم التقني',
                'parent_id' => '11',
            ]);

            subSection::create([
                'name' => 'قسم الشبكات والاتصالات',
                'parent_id' => '11',
            ]);

            // أقسام إدارة التدقيق الداخلي
            subSection::create([
                'name' => 'قسم التدقيق المالي',
                'parent_id' => '12',
            ]);

            subSection::create([
                'name' => 'قسم التدقيق الإداري',
                'parent_id' => '12',
            ]);

            subSection::create([
                'name' => 'قسم تدقيق العمليات',
                'parent_id' => '12',
            ]);


    }
}
