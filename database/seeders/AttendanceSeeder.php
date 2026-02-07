<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    // إعدادات عامة
    const MONTHS_BACK = 2; // كم شهر للخلف
    const WEEKEND_DAYS = [Carbon::FRIDAY, Carbon::SATURDAY]; // الجمعة/السبت عطلة

    // نوافذ الدوام (مطابقة للمنطق المعتمد)
    const CHECK_IN_OPEN             = '07:30:00';
    const CHECK_IN_PRESENT_LIMIT    = '08:15:00'; // حتى هذا الوقت = حاضر
    const CHECK_IN_LAST             = '10:30:00'; // آخر فرصة حضور
    const WORK_END                  = '14:00:00';
    const CHECK_OUT_OPEN            = '08:00:00';
    const CHECK_OUT_FIRST_HALF_END  = '10:30:00'; // 08:00–10:30 نصف أول
    const CHECK_OUT_SECOND_HALF_END = '13:59:59'; // 10:31–13:59 نصف ثاني
    const CHECK_OUT_DEADLINE        = '14:30:00';

    public function run(): void
    {
        // اسم جدول الحضور
        $table = Schema::hasTable('attendances') ? 'attendances'
               : (Schema::hasTable('attendance') ? 'attendance' : null);

        if (!$table) {
            $this->command?->error('لم أجد جدول الحضور (attendances/attendance).');
            return;
        }

        // FK الموظف
        $fk = Schema::hasColumn($table, 'employee_id') ? 'employee_id'
            : (Schema::hasColumn($table, 'emp_id') ? 'emp_id' : null);

        if (!$fk) {
            $this->command?->error("لم أجد عمود الربط بالموظف (employee_id/emp_id) في $table.");
            return;
        }

        // عمود التاريخ (اختر أول الموجودين)
        $dateCol = $this->pickFirstExisting($table, [
            'attendance_date', 'date', 'work_date', 'att_date', 'day'
        ]);
        if (!$dateCol) {
            $this->command?->error("لم أجد عمود تاريخ اليوم في $table (attendance_date/date/...).");
            return;
        }

        // أعمدة الدخول/الخروج: نحاول مابين time و datetime حسب التسمية
        $inCol  = $this->pickFirstExisting($table, [
            'check_in_time','checkin_time','check_in','in_time','in_at','check_in_at','checkin_at'
        ]);
        $outCol = $this->pickFirstExisting($table, [
            'check_out_time','checkout_time','check_out','out_time','out_at','check_out_at','checkout_at'
        ]);

        if (!$inCol || !$outCol) {
            $this->command?->error("أحتاج عمودي الدخول/الخروج في $table (check_in_* / check_out_*).");
            return;
        }

        // عمود الحالة (اختياري)
        $statusCol = $this->pickFirstExisting($table, ['status','state','day_status']);

        // عمود دقائق التأخير (اختياري)
        $lateCol = $this->pickFirstExisting($table, ['minutes_late','late_minutes','late_min']);

        // created_at / updated_at (اختياريان)
        $hasCreated = Schema::hasColumn($table, 'created_at');
        $hasUpdated = Schema::hasColumn($table, 'updated_at');

        // IDs الموظفين
        $empTable = 'employees';
        if (!Schema::hasTable($empTable)) {
            $this->command?->error("لم أجد جدول $empTable.");
            return;
        }
        $employeeIds = DB::table($empTable)->pluck('id');
        if ($employeeIds->isEmpty()) {
            $this->command?->warn('لا يوجد موظفون. شغّل EmployeesSeeder أولاً.');
            return;
        }

        $startDate = Carbon::now()->subMonths(self::MONTHS_BACK)->startOfMonth();
        $endDate   = Carbon::now();

        foreach ($employeeIds as $empId) {
            $rows = [];
            $d = $startDate->copy();

            while ($d->lte($endDate)) {
                // عطلة أسبوعية
                if (in_array($d->dayOfWeek, self::WEEKEND_DAYS, true)) {
                    $d->addDay();
                    continue;
                }

                // 75% نُسجل حضور/تأخير، 25% نترك اليوم بلا سجل = غياب
                if (random_int(0, 99) < 75) {
                    // تحديد حاضر/متأخر
                    $present = (random_int(0, 100) < 60);
                    if ($present) {
                        $checkIn = $this->randTime(self::CHECK_IN_OPEN, self::CHECK_IN_PRESENT_LIMIT);
                        $status  = 'حاضر';
                    } else {
                        $checkIn = $this->randTime($this->addOneMinute(self::CHECK_IN_PRESENT_LIMIT), self::CHECK_IN_LAST);
                        $status  = 'متأخر';
                    }

                    // انصراف: 20% نصف أول، 20% نصف ثاني، 60% طبيعي
                    $o = random_int(1, 100);
                    if ($o <= 20) {
                        $checkOut = $this->randTime(self::CHECK_OUT_OPEN, self::CHECK_OUT_FIRST_HALF_END);
                    } elseif ($o <= 40) {
                        $checkOut = $this->randTime($this->addOneMinute(self::CHECK_OUT_FIRST_HALF_END), self::CHECK_OUT_SECOND_HALF_END);
                    } else {
                        $checkOut = $this->randTime(self::WORK_END, self::CHECK_OUT_DEADLINE);
                    }

                    // جهّز الصف وفق نوع الأعمدة (time vs datetime)
                    $row = [
                        $fk       => (int) $empId,
                        $dateCol  => $d->toDateString(),
                    ];

                    $row[$inCol]  = $this->formatTimeForColumn($inCol,  $checkIn,  $d);
                    $row[$outCol] = $this->formatTimeForColumn($outCol, $checkOut, $d);

                    if ($statusCol) {
                        $row[$statusCol] = $status;
                    }
                    if ($lateCol) {
                        $row[$lateCol] = $this->minutesLate($checkIn, self::CHECK_IN_PRESENT_LIMIT);
                    }
                    if ($hasCreated) $row['created_at'] = now();
                    if ($hasUpdated) $row['updated_at'] = now();

                    $rows[] = $row;
                }

                $d->addDay();
            }

            if (!empty($rows)) {
                // upsert على (employee_id + date) أو (emp_id + date)
                DB::table($table)->upsert(
                    $rows,
                    [$fk, $dateCol],
                    array_values(array_diff(array_keys($rows[0]), [$fk, $dateCol]))
                );
            }
        }
    }

    /** اختَر أول عمود موجود فعلاً */
    private function pickFirstExisting(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) return $c;
        }
        return null;
    }

    /** تنسيق القيمة وفق اسم العمود: *_time => H:i:s، *_at => Y-m-d H:i:s */
    private function formatTimeForColumn(string $col, string $timeHms, Carbon $date): string
    {
        $isDateTimeLike = str_ends_with($col, '_at') || str_contains($col, 'datetime');
        return $isDateTimeLike
            ? $date->toDateString() . ' ' . $timeHms
            : $timeHms;
    }

    /** وقت عشوائي بين حدّين (H:i:s) */
    private function randTime(string $from, string $to): string
    {
        $fromSec = $this->hmsToSec($from);
        $toSec   = $this->hmsToSec($to);
        if ($toSec < $fromSec) [$fromSec, $toSec] = [$toSec, $fromSec];
        $rand = random_int($fromSec, $toSec);
        return $this->secToHms($rand);
    }

    private function hmsToSec(string $hms): int
    {
        [$h, $m, $s] = array_map('intval', explode(':', $hms));
        return $h * 3600 + $m * 60 + $s;
    }

    private function secToHms(int $sec): string
    {
        $h = intdiv($sec, 3600);
        $sec -= $h * 3600;
        $m = intdiv($sec, 60);
        $s = $sec - $m * 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    private function addOneMinute(string $hms): string
    {
        [$h,$m,$s] = array_map('intval', explode(':', $hms));
        $m += 1;
        if ($m >= 60) { $m -= 60; $h += 1; }
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    private function minutesLate(string $checkIn, string $presentLimit): int
    {
        $late = $this->hmsToSec($checkIn) - $this->hmsToSec($presentLimit);
        return max(0, intdiv($late, 60));
    }
}
