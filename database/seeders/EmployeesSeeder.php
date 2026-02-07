<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class EmployeesSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('employees')) {
            $this->command?->warn('جدول employees غير موجود.');
            return;
        }
        if (!Schema::hasTable('people')) {
            $this->command?->warn('جدول people غير موجود. شغّل PeopleSeeder أولاً.');
            return;
        }
        if (!Schema::hasColumn('employees', 'person_id')) {
            $this->command?->error('employees لازم يحتوي person_id لعلاقة 1:1.');
            return;
        }

        $now = Carbon::now();
        $people = DB::table('people')->orderBy('id')->limit(10)->get();

        if ($people->isEmpty()) {
            $this->command?->warn('لا توجد سجلات في people. شغّل PeopleSeeder أولاً.');
            return;
        }

        // مفاتيح أجنبية اختيارية
        $subIds      = Schema::hasTable('sub_sections') ? DB::table('sub_sections')->pluck('id')->all() : [];
        $specIds     = Schema::hasTable('specialties')  ? DB::table('specialties')->pluck('id')->all()  : [];
        $gradeIds    = Schema::hasTable('grades')       ? DB::table('grades')->pluck('id')->all()       : [];
        $staffingIds = Schema::hasTable('staffings')    ? DB::table('staffings')->pluck('id')->all()    : [];
        $bankIds     = Schema::hasTable('banks')        ? DB::table('banks')->pluck('id')->all()        : [];

        foreach ($people as $i => $p) {
            // تاريخ تعيين/بدء عمل معقول
            $hire = Carbon::create(2019 + ($i % 5), ($i % 12) + 1, ($i % 28) + 1);

            $row = [
                'person_id'  => $p->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // املأ start_date إذا موجود (سبب الخطأ)
            if (Schema::hasColumn('employees', 'start_date')) {
                $row['start_date'] = $hire->toDateString();
            }
            // دعم أسماء بديلة إن وُجدت
            if (Schema::hasColumn('employees', 'hiring_date')) {
                $row['hiring_date'] = $hire->toDateString();
            }
            if (Schema::hasColumn('employees', 'emp_no')) {
                $row['emp_no'] = 'EMP' . str_pad((string) $p->id, 4, '0', STR_PAD_LEFT);
            }
            if (Schema::hasColumn('employees','job_number')) {
                $row['job_number'] = 1000 + (int) $p->id;
            }
            if (Schema::hasColumn('employees','enabled')) {
                $row['enabled'] = true;
            }

            // مفاتيح أجنبية اختيارية
            if (Schema::hasColumn('employees','sub_section_id') && $subIds) {
                $row['sub_section_id'] = $subIds[$i % count($subIds)];
            }
            if (Schema::hasColumn('employees','specialty_id') && $specIds) {
                $row['specialty_id']   = $specIds[$i % count($specIds)];
            }
            if (Schema::hasColumn('employees','grade_id') && $gradeIds) {
                $row['grade_id']       = $gradeIds[$i % count($gradeIds)];
            }
            if (Schema::hasColumn('employees','staffing_id') && $staffingIds) {
                $row['staffing_id']    = $staffingIds[$i % count($staffingIds)];
            }
            if (Schema::hasColumn('employees','bank_id') && $bankIds) {
                $row['bank_id']        = $bankIds[$i % count($bankIds)];
            }

            // عدم التكرار لكل شخص
            DB::table('employees')->updateOrInsert(['person_id' => $p->id], $row);
        }
    }
}
