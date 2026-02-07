<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Vacation;
use App\Models\Course;       // الدورات
use App\Models\Task;         // التكليفات
use App\Models\Specialty;    // التخصصات
use App\Models\subSection;   // الإدارات/الأقسام

class DashboardController extends Controller
{
    /**
     * صفحة لوحة التحكم:
     * - بطاقات موجزة
     * - إجمالي الموظفين (ذكر/أنثى)
     * - إحصائية آخر 8 أشهر (حضور/تأخير/غياب استنباطي)
     * - طلبات الإجازة المعلّقة (أحدث 10)
     */
    public function index()
    {
        // تعريب أسماء الشهور
        Carbon::setLocale('ar');
        app()->setLocale('ar');

        // بطاقات أعلى الصفحة
        $cards = [
            'sections'    => subSection::count(),   // الإدارات والأقسام
            'specialties' => Specialty::count(),    // التخصصات
            'courses'     => Course::count(),       // الدورات
            'tasks'       => Task::count(),         // التكليفات
        ];

        // إجمالي الموظفين: ذكر / أنثى (ندعم تهجئتين للأنثى)
        $totalEmployees  = Employee::count();
        $maleEmployees   = Employee::whereHas('person', fn ($q) => $q->where('gender', 'ذكر'))->count();
        $femaleEmployees = Employee::whereHas('person', fn ($q) => $q->whereIn('gender', ['أنثى', 'انثي']))->count();

        // --- منطق "الشهور غير المُحصاة" ---
        // أول تاريخ سجل حضور في النظام (إن لم يوجد فكل الشهور السابقة غير مُحصاة)
        $firstAttendanceDateStr = Attendance::min('attendance_date');
        $firstCountedMonthStart = $firstAttendanceDateStr
            ? Carbon::parse($firstAttendanceDateStr)->startOfMonth()
            : null;

        // بناء سلسلة آخر 8 أشهر
        $attendanceSeries = ['labels' => [], 'present' => [], 'late' => [], 'absent' => []];

        $today       = Carbon::now()->startOfDay();
        $weekendDays = [Carbon::FRIDAY, Carbon::SATURDAY]; // جمعة/سبت

        // نبدأ من أول يوم في الشهر الحالي - 7 أشهر
        $cursor = $today->copy()->startOfMonth()->subMonths(7);
        for ($i = 0; $i < 8; $i++) {
            $mStart = $cursor->copy()->startOfMonth();
            $mEnd   = $cursor->copy()->endOfMonth();

            // اسم الشهر (مختصر بالعربية)
            $attendanceSeries['labels'][] = $mStart->translatedFormat('M');

            // 1) شهر مستقبلي بالكامل => 0
            if ($mStart->isFuture()) {
                $attendanceSeries['present'][] = 0;
                $attendanceSeries['late'][]    = 0;
                $attendanceSeries['absent'][]  = 0;
                $cursor->addMonth();
                continue;
            }

            // 2) شهور قبل بداية نظام الحضور => غير مُحصاة => 0
            if ($firstCountedMonthStart && $mEnd->lt($firstCountedMonthStart)) {
                $attendanceSeries['present'][] = 0;
                $attendanceSeries['late'][]    = 0;
                $attendanceSeries['absent'][]  = 0;
                $cursor->addMonth();
                continue;
            }

            // cutoff للشهر الحالي: أمس حتى لا يُحسب اليوم غيابًا قبل انتهاء الدوام
            if ($mStart->isSameMonth($today)) {
                $cutoff = $today->copy()->subDay();
            } elseif ($mEnd->lt($today)) {
                $cutoff = $mEnd->copy(); // شهر ماضٍ
            } else {
                // نظريًا لن نصل هنا، لكن للاكتمال
                $cutoff = $mStart->copy()->subDay();
            }

            // لو cutoff قبل بداية الشهر (مثل بداية النظام اليوم الأول) => 0
            if ($cutoff->lt($mStart)) {
                $attendanceSeries['present'][] = 0;
                $attendanceSeries['late'][]    = 0;
                $attendanceSeries['absent'][]  = 0;
                $cursor->addMonth();
                continue;
            }

            // --- الحضور (حاضر صراحة أو دخول مبكر حتى 08:15) ---
            $presentCount = Attendance::whereBetween('attendance_date', [$mStart->toDateString(), $cutoff->toDateString()])
                ->where(function ($q) {
                    $q->whereIn('status', ['حاضر', 'present', 'Present', 'P', 'حضور'])
                      ->orWhere(function ($q2) {
                          $q2->whereNull('status')
                             ->whereNotNull('check_in_time')
                             ->where('check_in_time', '<=', '08:15:00');
                      });
                })
                ->count();

            // --- التأخير (متأخر صراحة أو دخول بين 08:15:01 و 10:30) ---
            $lateCount = Attendance::whereBetween('attendance_date', [$mStart->toDateString(), $cutoff->toDateString()])
                ->where(function ($q) {
                    $q->whereIn('status', ['متأخر', 'late', 'Late', 'L', 'تأخير'])
                      ->orWhere(function ($q2) {
                          $q2->whereNull('status')
                             ->whereNotNull('check_in_time')
                             ->whereBetween('check_in_time', ['08:15:01', '10:30:00']);
                      });
                })
                ->count();

            // --- إجمالي أيام الإجازات المقبولة لجميع الموظفين (بدون جمعة/سبت) ---
            $vacDays = $this->countAllVacationDays($mStart, $cutoff, $weekendDays);

            // إن لم توجد أي بيانات في الشهر (لا حضور/تأخير ولا إجازات) نعتبره "غير مُحصى" => غياب = 0
            $hasAnyDataThisMonth = ($presentCount + $lateCount + $vacDays) > 0;

            // --- قابلية العمل = (أيام العمل حتى cutoff) × (عدد الموظفين) ---
            $workable = $this->countWorkingDays($mStart, $cutoff, $weekendDays) * $totalEmployees;

            // --- الغياب الاستنباطي ---
            $absentCount = $hasAnyDataThisMonth
                ? max($workable - ($presentCount + $lateCount) - $vacDays, 0)
                : 0;

            // أضف السلاسل
            $attendanceSeries['present'][] = (int) $presentCount;
            $attendanceSeries['late'][]    = (int) $lateCount;
            $attendanceSeries['absent'][]  = (int) $absentCount;

            $cursor->addMonth();
        }

        // الإجازات المعلّقة بانتظار الاعتماد (أحدث 10)
        $pendingVacations = Vacation::with(['employee.person', 'employee.subSection'])
            ->where('accept', 0)
            ->latest('id')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'cards'            => $cards,
            'totalEmployees'   => $totalEmployees,
            'maleEmployees'    => $maleEmployees,
            'femaleEmployees'  => $femaleEmployees,
            'attendanceSeries' => $attendanceSeries,
            'pendingVacations' => $pendingVacations,
        ]);
    }

    /**
     * عدّ أيام العمل بين تاريخين (استثناء الجمعة/السبت).
     */
    private function countWorkingDays(Carbon $from, Carbon $to, array $weekendDays): int
    {
        if ($to->lt($from)) {
            return 0;
        }

        $count = 0;
        for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
            if (!in_array($d->dayOfWeek, $weekendDays, true)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * مجموع أيام الإجازات المقبولة لجميع الموظفين ضمن النطاق (استثناء الجمعة/السبت).
     */
    private function countAllVacationDays(Carbon $from, Carbon $to, array $weekendDays): int
    {
        if ($to->lt($from)) {
            return 0;
        }

        $vacs = Vacation::where('accept', 1)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_date', [$from->toDateString(), $to->toDateString()])
                  ->orWhereBetween('end_date',   [$from->toDateString(), $to->toDateString()])
                  ->orWhere(function ($q2) use ($from, $to) {
                      $q2->where('start_date', '<=', $from->toDateString())
                         ->where('end_date',   '>=', $to->toDateString());
                  });
            })
            ->get(['start_date', 'end_date']);

        $days = 0;
        foreach ($vacs as $v) {
            $s = Carbon::parse($v->start_date)->startOfDay()->max($from);
            $e = Carbon::parse($v->end_date)->endOfDay()->min($to);

            for ($d = $s->copy(); $d->lte($e); $d->addDay()) {
                if (!in_array($d->dayOfWeek, $weekendDays, true)) {
                    $days++;
                }
            }
        }

        return $days;
    }
}
