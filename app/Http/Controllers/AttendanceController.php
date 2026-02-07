<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Vacation;
use Illuminate\Http\Request;
use App\Models\subSection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceController extends Controller
{
    // قواعد الدوام الجديدة
    private const CHECK_IN_OPEN            = '07:30:00'; // فتح تسجيل الحضور
    private const CHECK_IN_PRESENT_LIMIT   = '08:15:00'; // حتى هذا الوقت = حاضر
    private const CHECK_IN_LAST            = '10:30:00'; // آخر وقت مسموح لتسجيل الحضور (بعده يُعد غياب)

    private const CHECK_OUT_OPEN           = '08:00:00'; // بدء قبول الانصراف
    private const CHECK_OUT_FIRST_END      = '10:30:00'; // انصراف النصف الأول
    private const CHECK_OUT_SECOND_END     = '13:59:59'; // انصراف النصف الثاني
    private const WORK_END                 = '14:00:00'; // نهاية الدوام الرسمي
    private const CHECK_OUT_DEADLINE       = '14:30:00'; // آخر وقت لتسجيل الانصراف


    /** شاشة الحضور والانصراف */
    public function index()
    {
        $employees = Employee::query()
            ->leftJoin('people', 'people.id', '=', 'employees.person_id')
            ->select('employees.id', 'employees.person_id')
            ->orderByRaw('COALESCE(people.name, "") asc')
            ->with(['person:id,name'])
            ->get();

        return view('admin.attendance.index', compact('employees'));
    }

    /** تسجيل حضور */
    public function checkIn(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
    
        $now   = \Carbon\Carbon::now();
        $today = $now->toDateString();
        $t     = $now->format('H:i:s');
    
        // عطلة أسبوعية: الجمعة/السبت
        $weekendDays = [\Carbon\Carbon::FRIDAY, \Carbon\Carbon::SATURDAY];
        if (in_array($now->dayOfWeek, $weekendDays, true)) {
            return back()->with('error', 'لا يمكن تسجيل الحضور في عطلة نهاية الأسبوع.');
        }
    
        // نافذة الحضور: 07:30–10:30
        if (!($t >= self::CHECK_IN_OPEN && $t <= self::CHECK_IN_LAST)) {
            return back()->with('error', 'نافذة تسجيل الحضور: 7:30 ص حتى 10:30 ص.');
        }
    
        // لا تكرار في نفس اليوم
        $exists = \App\Models\Attendance::where('employee_id', $request->employee_id)
            ->whereDate('attendance_date', $today)
            ->exists();
    
        if ($exists) {
            return back()->with('error', 'تم تسجيل الحضور مسبقًا لهذا اليوم.');
        }
    
        // الحالة: حاضر حتى 08:15، خلافه متأخر
        $status = ($t <= self::CHECK_IN_PRESENT_LIMIT) ? 'حاضر' : 'متأخر';
    
        \App\Models\Attendance::create([
            'employee_id'     => (int) $request->employee_id,
            'attendance_date' => $today,
            'check_in_time'   => $t,
            'status'          => $status,
        ]);
    
        return back()->with('success', 'تم تسجيل الحضور بنجاح.');
    }    

    /** تسجيل انصراف */
    public function checkOut(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
    
        $now   = \Carbon\Carbon::now();
        $today = $now->toDateString();
        $t     = $now->format('H:i:s');
    
        // نافذة الانصراف المسموح بها: 08:00–14:30
        if (!($t >= self::CHECK_OUT_OPEN && $t <= self::CHECK_OUT_DEADLINE)) {
            return back()->with('error', 'نافذة تسجيل الانصراف: 8:00 ص حتى 2:30 م.');
        }
    
        $attendance = \App\Models\Attendance::where('employee_id', $request->employee_id)
            ->whereDate('attendance_date', $today)
            ->first();
    
        if (!$attendance) {
            return back()->with('error', 'لم يتم تسجيل حضور اليوم، الرجاء تسجيل الحضور أولاً.');
        }
    
        $attendance->update([
            'check_out_time' => $t,
        ]);
    
        return back()->with('success', 'تم تسجيل الانصراف بنجاح.');
    }
    

    /** نموذج تقرير موظف شهري */
    public function showReportForm()
    {
        $employees = Employee::query()
            ->leftJoin('people', 'people.id', '=', 'employees.person_id')
            ->select('employees.id', 'employees.person_id')
            ->orderByRaw('COALESCE(people.name, "") asc')
            ->with(['person:id,name'])
            ->get();

        return view('admin.attendance.report', compact('employees'));
    }

    /**
     * توليد تقرير موظف لفترة شهر:
     * - workingDays = جميع أيام العمل في الشهر (استثناء الجمعة/السبت).
     * - presentDays = أيام بها حضور (distinct by date).
     * - absenceDays = (أيام العمل حتى اليوم فقط) - (الحضور حتى اليوم) - (أيام الإجازة حتى اليوم).
     * - يستثني الإجازات المقبولة من الغياب.
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|date_format:Y-m',
        ]);

        [$year, $month] = explode('-', $request->input('month'));
        $year  = (int) $year;
        $month = (int) $month;

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();
        $today = Carbon::now()->startOfDay();

        // cutoff: حتى اليوم لو شهر حالي، وإلا كامل الشهر للماضي، ولا شيء للمستقبل
        if ($year === (int)$today->year && $month === (int)$today->month) {
            $cutoff = $today;
        } elseif ($start->gt($today)) {
            $cutoff = $start->copy()->subDay(); // مستقبل
        } else {
            $cutoff = $end; // شهر ماضٍ
        }

        $weekendDays = [Carbon::FRIDAY, Carbon::SATURDAY];

        // 1) عدّ كل أيام العمل في الشهر (للعرض فقط)
        $workingDaysFull = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!in_array($d->dayOfWeek, $weekendDays, true)) {
                $workingDaysFull++;
            }
        }

        // 2) أيام العمل حتى الـ cutoff
        $workingDaysTillCutoff = 0;
        for ($d = $start->copy(); $d->lte($end) && $d->lte($cutoff); $d->addDay()) {
            if (!in_array($d->dayOfWeek, $weekendDays, true)) {
                $workingDaysTillCutoff++;
            }
        }

        // 3) أيام الحضور حتى الـ cutoff
        $presentDaysTillCutoff = Attendance::where('employee_id', $request->employee_id)
            ->whereBetween('attendance_date', [$start->toDateString(), min($cutoff->toDateString(), $end->toDateString())])
            ->select('attendance_date')
            ->distinct()
            ->count();

        // 4) أيام الإجازة المقبولة ضمن الشهر وحتى الـ cutoff
        $vacationDaysTillCutoff = $this->countApprovedVacationDays($request->employee_id, $start, min($cutoff, $end), $weekendDays);

        // 5) الغياب = أيام العمل – حضور – إجازة
        $absenceDays = max($workingDaysTillCutoff - $presentDaysTillCutoff - $vacationDaysTillCutoff, 0);

        $employee = Employee::with('person:id,name')->find($request->employee_id);

        $reportData = [
            'employee'     => $employee,
            'month'        => $start->translatedFormat('F Y'),
            'workingDays'  => $workingDaysFull,       // كامل الشهر (لعرض الصورة العامة)
            'presentDays'  => $presentDaysTillCutoff, // حتى اليوم
            'absenceDays'  => $absenceDays,           // حتى اليوم
        ];

        $employees = Employee::query()
            ->leftJoin('people', 'people.id', '=', 'employees.person_id')
            ->select('employees.id', 'employees.person_id')
            ->orderByRaw('COALESCE(people.name, "") asc')
            ->with(['person:id,name'])
            ->get();

        return view('admin.attendance.report', compact('employees', 'reportData'));
    }

    public function monthlyReport(Request $request)
    {
        // قراءات الفلاتر (قد يأتي month بصيغة YYYY-MM من الواجهة)
        $reqMonth = $request->input('month');           // "2025-08" أو "8"
        $reqYear  = $request->input('year');
    
        $today = Carbon::now()->startOfDay();
    
        // تفكيك الشهر والسنة بأمان
        if (is_string($reqMonth) && preg_match('/^\d{4}-\d{2}$/', $reqMonth)) {
            [$year, $month] = array_map('intval', explode('-', $reqMonth));
        } else {
            $month = (int) ($reqMonth ?: $today->month);
            $year  = (int) ($reqYear  ?: $today->year);
        }
    
        $start  = Carbon::create($year, $month, 1)->startOfMonth();
        $end    = $start->copy()->endOfMonth();
        $period = CarbonPeriod::create($start, $end);
    
        // عطلة أسبوعية: جمعة/سبت
        $weekendDays = [Carbon::FRIDAY, Carbon::SATURDAY];
    
        // نستخدم قطعين (cutoff):
        // 1) cutoff العرض: يسمح بعرض اليوم الحالي ضمن الجدول (ليس مستقبلًا)
        // 2) cutoff الغياب/الإحصاءات: يستبعد اليوم الحالي حتى لا يُحسب غيابًا
        if ($year === (int)$today->year && $month === (int)$today->month) {
            $renderCutoff  = $today;                 // للعرض
            $absenceCutoff = $today->copy()->subDay(); // للإحصاءات (يوم أمس)
        } elseif ($start->gt($today)) {
            $renderCutoff  = $start->copy()->subDay();  // شهر مستقبلي بالكامل
            $absenceCutoff = $renderCutoff;
        } else {
            $renderCutoff  = $end;   // شهر ماضٍ كامل
            $absenceCutoff = $end;
        }
    
        // فلاتر إضافية
        $employeeSearch = trim((string) $request->input('employee_search', ''));
        $subSectionId   = $request->input('sub_section_id');
        $employmentType = $request->input('employment_type');
    
        // مصادر القوائم للفلاتر
        $subSections = subSection::orderBy('name')->get(['id','name']);
        $employmentTypes = Employee::query()
            ->select('type')->whereNotNull('type')->distinct()->orderBy('type')->pluck('type');
    
        // جلب الموظفين + تطبيق الفلاتر
        $employeesQuery = Employee::query()
            ->leftJoin('people', 'people.id', '=', 'employees.person_id')
            ->select('employees.*')
            ->orderByRaw('COALESCE(people.name, "") asc')
            ->with(['person:id,name']);
    
        if ($employeeSearch !== '') {
            $like = '%' . str_replace('%', '\\%', $employeeSearch) . '%';
            $employeesQuery->where('people.name', 'like', $like);
        }
        if ($subSectionId) {
            $employeesQuery->where('employees.sub_section_id', $subSectionId);
        }
        if ($employmentType) {
            $employeesQuery->where('employees.type', $employmentType);
        }
    
        $employees   = $employeesQuery->get();
        $employeeIds = $employees->pluck('id')->all();
    
        // سجلات الحضور الخام ضمن الشهر
        $attRaw = Attendance::query()
            ->select('employee_id','attendance_date','check_in_time','check_out_time','status')
            ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
            ->when(!empty($employeeIds), fn($q) => $q->whereIn('employee_id', $employeeIds))
            ->orderBy('attendance_date')
            ->get();

        // نبني شبكة حالات لسهولة العرض: stateGrid[empId][Y-m-d] = 'P'/'L'/'A'/'V'
        $stateGrid = [];
        $attMap    = []; // للاحتفاظ بالسجل نفسه لو أردنا التلميحات (وقت الدخول/الخروج)
        foreach ($attRaw as $r) {
            $eid = (int) $r->employee_id;
            $d = $r->attendance_date instanceof \Carbon\Carbon
                ? $r->attendance_date->toDateString()
                : \Carbon\Carbon::parse($r->attendance_date)->toDateString();
            $status = trim((string) $r->status);
            $sLower = mb_strtolower($status, 'UTF-8');

            // طبيع الحالات: عربي/إنجليزي
            if (in_array($status, ['حاضر','present','Present','P'], true)) {
                $state = 'P';
            } elseif (in_array($status, ['متأخر','late','Late','L'], true)) {
                $state = 'L';
            } elseif (in_array($status, ['vacation','إجازة','اجازة'], true)) {
                $state = 'V';
            } elseif (in_array($status, ['absent','غياب','A'], true)) {
                $state = 'A';
            } else {
                // لو حالة غير معروفة نعتبرها حضور افتراضيًا
                $state = 'P';
            }

            // أولوية الدمج: V > L > P > A
            $prio = ['V'=>3,'L'=>2,'P'=>1,'A'=>0];
            $old  = $stateGrid[$eid][$d] ?? null;
            if ($old === null || $prio[$state] > $prio[$old]) {
                $stateGrid[$eid][$d] = $state;
                $attMap[$eid][$d]    = $r; // لنص التلميح (IN/OUT)
            }
        }

        // طبّق الإجازات المقبولة من جدول vacations كـ V (تغلّب كل شيء)
        $weekendDays = [Carbon::FRIDAY, Carbon::SATURDAY];
        $vacDaysByEmp = [];
        foreach ($employees as $emp) {
            $vacDays = $this->listApprovedVacationDays($emp->id, $start, $end, $weekendDays);
            $vacDaysByEmp[$emp->id] = $vacDays;
            foreach ($vacDays as $d) {
                $stateGrid[$emp->id][$d] = 'V'; // تغلّب
            }
        }

        // -------- الإحصاءات (تستبعد اليوم الحالي من احتساب الغياب) --------
        $today = Carbon::now()->startOfDay();
        if ($year === (int)$today->year && $month === (int)$today->month) {
            $renderCutoff  = $today;                 // للعرض
            $absenceCutoff = $today->copy()->subDay(); // للإحصاء
        } elseif ($start->gt($today)) {
            $renderCutoff  = $start->copy()->subDay();
            $absenceCutoff = $renderCutoff;
        } else {
            $renderCutoff  = $end;
            $absenceCutoff = $end;
        }

        $workDaysInMonthTillCutoff = 0;
        foreach (CarbonPeriod::create($start, $end) as $day) {
            if ($day->lte($absenceCutoff) && !in_array($day->dayOfWeek, $weekendDays, true)) {
                $workDaysInMonthTillCutoff++;
            }
        }
        $totalEmployees    = $employees->count();
        $totalWorkableDays = $workDaysInMonthTillCutoff * $totalEmployees;

        // أي خانة ليست WK ولا V ولها سجل P/L تُحسب حضور
        $totalAttended = 0;
        foreach ($employees as $emp) {
            for ($d = $start->copy(); $d->lte($absenceCutoff); $d->addDay()) {
                if (in_array($d->dayOfWeek, $weekendDays, true)) continue;
                $state = $stateGrid[$emp->id][$d->toDateString()] ?? null;
                if (in_array($state, ['P','L'], true)) $totalAttended++;
                // V لا تُحسب غياب — لكن لا تزود الحضور
            }
        }

        $stats = [
            'total_employees'       => $totalEmployees,
            'total_workable_days'   => $totalWorkableDays,
            'total_attended'        => $totalAttended,
            'attendance_percentage' => ($totalWorkableDays > 0)
                ? (int) round(($totalAttended / $totalWorkableDays) * 100)
                : 0,
        ];

        // حدود التوقيت للواجهة
        $uiTimes = [
            'check_in_open'          => self::CHECK_IN_OPEN,
            'check_in_present_limit' => self::CHECK_IN_PRESENT_LIMIT,
            'check_in_last'          => self::CHECK_IN_LAST,
            'work_end'               => self::WORK_END,
            'check_out_open'         => self::CHECK_OUT_OPEN,
            'check_out_first_end'    => self::CHECK_OUT_FIRST_END,
            'check_out_second_end'   => self::CHECK_OUT_SECOND_END,
            'check_out_deadline'     => self::CHECK_OUT_DEADLINE,
        ];

        $date = ['month'=>$month,'year'=>$year,'current_date'=>$today];

        // تمرير القيم للواجهة
        return view('admin.attendance.monthly_report', [
            'employees'        => $employees,
            'stateGrid'        => $stateGrid,   // <= الجديد
            'attMap'           => $attMap,      // <= لتلميحات IN/OUT
            'date'             => $date,
            'stats'            => $stats,
            'weekendDays'      => $weekendDays,
            'cutoff'           => $renderCutoff,
            'subSections'      => $subSections,
            'employmentTypes'  => $employmentTypes,
            'vacDaysByEmp'     => $vacDaysByEmp,
            'uiTimes'          => $uiTimes,
        ]);
    }
    
    

    /** تقرير الغياب السنوي (يعرض حضور/غياب/تأخير لكل شهر مع استثناء الإجازات) */
    public function absenceReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'year'        => 'required|digits:4',
        ]);
    
        $employee = Employee::with('person')->findOrFail($request->employee_id);
        $year     = (int) $request->year;
    
        $today = Carbon::now()->startOfDay();
        $weekendDays = [Carbon::FRIDAY, Carbon::SATURDAY];
    
        $report = [];
    
        for ($m = 1; $m <= 12; $m++) {
            $start = Carbon::createFromDate($year, $m, 1)->startOfMonth();
            $end   = $start->copy()->endOfMonth();
    
            // cutoff شهري: اليوم الحالي إن كنا في نفس الشهر، وإلا كامل الشهر للماضي، وصفر للمستقبل
            if ($year === (int)$today->year && $m === (int)$today->month) {
                $monthCutoff = $today;
            } elseif ($start->gt($today)) {
                $monthCutoff = $start->copy()->subDay(); // مستقبل
            } else {
                $monthCutoff = $end; // ماضٍ
            }
    
            // أيام العمل حتى cutoff
            $workingDays = 0;
            foreach (CarbonPeriod::create($start, $end) as $d) {
                if ($d->lte($monthCutoff) && !in_array($d->dayOfWeek, $weekendDays, true)) {
                    $workingDays++;
                }
            }
    
            // حضور/تأخر من السجلات
            $att = Attendance::where('employee_id', $employee->id)
                ->whereBetween('attendance_date', [$start->toDateString(), min($monthCutoff, $end)->toDateString()])
                ->get();
    
            // نقبل العربي والإنجليزي احتياطًا
            $present = $att->filter(fn($r) => in_array($r->status, ['حاضر','present','Present','P'], true))->count();
            $late    = $att->filter(fn($r) => in_array($r->status, ['متأخر','late','Late','L'], true))->count();
    
            // إجازات مقبولة حتى cutoff
            $vacationDays = $this->countApprovedVacationDays($employee->id, $start, min($monthCutoff, $end), $weekendDays);
    
            // الغياب = أيام العمل - (حضور + تأخر) - إجازة
            $absent  = max($workingDays - ($present + $late) - $vacationDays, 0);
    
            $report[$start->translatedFormat('F')] = [
                'present' => $present,
                'late'    => $late,
                'absent'  => $absent,
            ];
        }
    
        return view('admin.attendance.absence_report', compact('employee', 'year', 'report'));
    }
      

    /** إرجاع عدد أيام الإجازات المقبولة ضمن نطاق معين (يُستثنى الجمعة/السبت) */
    private function countApprovedVacationDays(int $empId, Carbon $from, Carbon $to, array $weekendDays): int
    {
        if ($to->lt($from)) return 0;

        $vacations = Vacation::where('emp_id', $empId)
            ->where('accept', true)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_date', [$from->toDateString(), $to->toDateString()])
                  ->orWhereBetween('end_date',   [$from->toDateString(), $to->toDateString()])
                  ->orWhere(function ($q2) use ($from, $to) {
                      $q2->where('start_date', '<=', $from->toDateString())
                         ->where('end_date',   '>=', $to->toDateString());
                  });
            })
            ->get();

        $days = 0;
        foreach ($vacations as $v) {
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

    /** قائمة الأيام (Y-m-d) لإجازات الموظف المقبولة ضمن نطاق معين */
    private function listApprovedVacationDays(int $empId, Carbon $from, Carbon $to, array $weekendDays): array
    {
        if ($to->lt($from)) return [];

        $vacations = Vacation::where('emp_id', $empId)
            ->where('accept', true)
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('start_date', [$from->toDateString(), $to->toDateString()])
                  ->orWhereBetween('end_date',   [$from->toDateString(), $to->toDateString()])
                  ->orWhere(function ($q2) use ($from, $to) {
                      $q2->where('start_date', '<=', $from->toDateString())
                         ->where('end_date',   '>=', $to->toDateString());
                  });
            })
            ->get();

        $days = [];
        foreach ($vacations as $v) {
            $s = Carbon::parse($v->start_date)->startOfDay()->max($from);
            $e = Carbon::parse($v->end_date)->endOfDay()->min($to);
            for ($d = $s->copy(); $d->lte($e); $d->addDay()) {
                if (!in_array($d->dayOfWeek, $weekendDays, true)) {
                    $days[] = $d->toDateString();
                }
            }
        }
        return array_values(array_unique($days));
    }
}
