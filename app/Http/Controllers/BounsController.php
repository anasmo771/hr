<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Vacation;
use App\Models\Feedback;
use App\Models\Log;

class BounsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bonus-list|bonus-create|bonus-edit|bonus-delete', ['only' => ['index','show']]);
        $this->middleware('permission:bonus-create', ['only' => ['quickStore','show1','create','store','createAll']]); // ← أضفنا createAll
        $this->middleware('permission:bonus-edit',   ['only' => ['edit','update']]);
        $this->middleware('permission:bonus-delete', ['only' => ['destroy']]);
    }
    
    /** صفحة العلاوات (قائمة + إشعارات الاستحقاق) */
    public function index(Request $request)
    {
        // العلاوات المسجّلة (بدون paginate – الواجهة تستخدم تمرير داخلي)
        $bonuses = Bonus::with(['emp.person', 'user'])
                        ->latest('id')
                        ->get();
    
        // بناء الاستحقاقات مع base_date
        $eligibles = $this->buildEligibilityList();
    
        // حساب تاريخ آخر علاوة لكل موظف باستعلام واحد
        $lastByEmp = Bonus::select(
                            'emp_id',
                            DB::raw('MAX(COALESCE(bonus_date, `date`, created_at)) as last_dt')
                        )
                        ->groupBy('emp_id')
                        ->pluck('last_dt', 'emp_id');
    
        // إلحاق last_bonus_date بكل صف في الاستحقاقات
        foreach ($eligibles as &$row) {
            $empId = (int) $row['emp_id'];
            $last  = $lastByEmp[$empId] ?? null;
            if ($last) {
                try { $row['last_bonus_date'] = Carbon::parse($last)->format('Y-m-d'); }
                catch (\Throwable $e) { $row['last_bonus_date'] = (string) $last; }
            } else {
                $row['last_bonus_date'] = null;
            }
        }
        unset($row);
    
        return view('admin.Bonus.index', compact('bonuses', 'eligibles'));
    }
    
    /** شاشة بحث الموظّفين لإضافة علاوة (يستدعيها زر إضافة علاوة العام لو احتجت) */
    public function show1(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $employees = Employee::with('person')
            ->when($q !== '', function ($qry) use ($q) {
                $qry->where(function ($sub) use ($q) {
                    $sub->where('id', $q)
                        ->orWhere('res_num', 'like', "%{$q}%")
                        ->orWhereHas('person', function ($p) use ($q) {
                            $p->where('name', 'like', "%{$q}%")
                              ->orWhere('N_id', 'like', "%{$q}%")
                              ->orWhere('non_citizen_ref_no', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->appends(['q' => $q]);

        return view('admin.Bonus.show', compact('employees', 'q')); // إن كنت تستخدم show كقائمة بحث عامة
    }

    /** عرض علاوات موظف معيّن (زر "العلاوات" من صفحة تفاصيل الموظف) */
    public function show($id, Request $request)
    {
        $emp = Employee::with(['person','subSection'])->findOrFail($id);

        $bouns = Bonus::with(['emp.person','user','files'])
            ->where('emp_id', $id)
            ->latest('id')
            ->paginate(20);

        $query = $request->except('page');

        return view('admin.Bonus.show', compact('emp','bouns','query'));
    }

    /** فتح نموذج إنشاء علاوة لموظف محدد */
    public function create($id)
    {
        $emp = Employee::with(['person','subSection'])->findOrFail($id);

        // رقم العلاوة التالي
        $nextSeq = (int) Bonus::where('emp_id', $id)->max('bonus_num');
        $nextSeq = $nextSeq ? $nextSeq + 1 : 1;

        // آخر تاريخ استحقاق مسجّل (لملء last_date)
        $lastDate = Bonus::where('emp_id', $id)->max('date');

        // درجة الموظف الحالية (snapshot)
        $degree = $emp->degree;

        // اقتراح تقدير من تقرير الكفاية
        $estimate = $this->estimateFromFeedback($id, Carbon::today());

        // تاريخ العلاوة المقترح: اليوم (عدّله حسب سياستك)
        $suggestedDate = Carbon::today()->toDateString();

        return view('admin.Bonus.create', compact('emp','nextSeq','lastDate','degree','estimate','suggestedDate'));
    }

    /** حفظ العلاوة (route: storeBouns) */
    public function store(Request $request, $id)
    {
        $emp = Employee::findOrFail($id);

        $data = $request->validate([
            'bonus_num'  => 'required|integer|min:1',
            'date'       => 'required|date',      // تاريخ الاستحقاق/العلاوة (عمود bonuses.date إلزامي)
            'bonus_date' => 'nullable|date',      // تاريخ القرار/الإصدار إن موجود
            'degree'     => 'nullable|integer',
            'estimate'   => 'nullable|string|max:255',
            'accept'     => 'nullable|in:0,1',
        ]);

        // لو الدرجة مش مرسلة من النموذج، خذ درجة الموظف الحالية
        if (!array_key_exists('degree', $data) || $data['degree'] === null || $data['degree'] === '') {
            $data['degree'] = $emp->degree;
        }

        // آخر تاريخ علاوة سابق لنفس الموظف (لملء last_date)
        $lastDate = Bonus::where('emp_id', $emp->id)->max('date');

        DB::beginTransaction();
        try {
            $bonus = Bonus::create([
                'emp_id'     => $emp->id,
                'bonus_num'  => (int) $data['bonus_num'],
                'date'       => Carbon::parse($data['date'])->toDateString(),
                'last_date'  => $lastDate,
                'bonus_date' => !empty($data['bonus_date']) ? Carbon::parse($data['bonus_date'])->toDateString() : null,
                'degree'     => $data['degree'],
                'estimate'   => $data['estimate'] ?? null,
                'created_id' => Auth::id(),
                'accept'     => array_key_exists('accept', $data) ? (int)$data['accept'] : 1,
            ]);

            if (class_exists(Log::class)) {
                Log::create([
                    'user_id' => Auth::id(),
                    'type'    => 21,
                    'emp_id'  => $emp->id,
                    'title'   => "إضافة علاوة ({$bonus->bonus_num})",
                    'log'     => "تاريخ: {$bonus->date}, آخر: ".($lastDate ?? '-').", درجة: {$bonus->degree}, تقدير: ".($bonus->estimate ?? '-'),
                ]);
            }

            DB::commit();
            // بعد الإضافة نرجّع لعرض علاوات الموظف
            return redirect()->route('bouns.show', $emp->id)->with('success', 'تم إضافة العلاوة بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'تعذر حفظ العلاوة: '.$e->getMessage())->withInput();
        }
    }

    /** إضافة سريعة من إشعار الاستحقاق (من index) */
    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'emp_id'   => 'required|integer|exists:employees,id',
            'due_date' => 'required|date',
        ]);

        $empId   = (int) $data['emp_id'];
        $dueDate = Carbon::parse($data['due_date'])->toDateString();

        $nextSeq  = (int) Bonus::where('emp_id', $empId)->max('bonus_num');
        $nextSeq  = $nextSeq ? $nextSeq + 1 : 1;
        $lastDate = Bonus::where('emp_id', $empId)->max('date');
        $employeeDegree = (int) optional(Employee::find($empId))->degree;
        $estimate = $this->estimateFromFeedback($empId, Carbon::parse($dueDate));

        DB::beginTransaction();
        try {
            Bonus::create([
                'emp_id'     => $empId,
                'bonus_num'  => $nextSeq,
                'date'       => $dueDate,
                'last_date'  => $lastDate,
                'bonus_date' => now()->toDateString(),
                'degree'     => $employeeDegree,
                'estimate'   => $estimate,
                'created_id' => auth()->id(),
                'accept'     => 1,
            ]);

            if (class_exists(Log::class)) {
                Log::create([
                    'user_id' => auth()->id(),
                    'type'    => 21,
                    'emp_id'  => $empId,
                    'title'   => "إضافة علاوة (سريعة)",
                    'log'     => "استحقاق: {$dueDate}, آخر استحقاق: ".($lastDate ?? '-').", درجة: {$employeeDegree}, تقدير: ".($estimate ?? '-'),
                ]);
            }

            DB::commit();
            return back()->with('success', 'تم إضافة العلاوة بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'تعذر إضافة العلاوة: '.$e->getMessage());
        }
    }

    /** تعديل */
    public function edit($id)
    {
        $bonus = Bonus::with('emp.person')->findOrFail($id);
        return view('admin.Bonus.edit', compact('bonus'));
    }

    public function update(Request $request, $id)
    {
        $bonus = Bonus::findOrFail($id);

        $data = $request->validate([
            'bonus_num'  => 'required|integer|min:1',
            'date'       => 'required|date',
            'bonus_date' => 'nullable|date',
            'degree'     => 'nullable|integer',
            'estimate'   => 'nullable|string|max:255',
            'accept'     => 'nullable|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            $bonus->bonus_num  = (int) $data['bonus_num'];
            $bonus->date       = Carbon::parse($data['date'])->toDateString();
            $bonus->bonus_date = !empty($data['bonus_date']) ? Carbon::parse($data['bonus_date'])->toDateString() : null;
            $bonus->degree     = $data['degree']   ?? null;
            $bonus->estimate   = $data['estimate'] ?? null;
            $bonus->accept     = array_key_exists('accept', $data) ? (int)$data['accept'] : (int)$bonus->accept;
            $bonus->save();

            if (class_exists(Log::class)) {
                Log::create([
                    'user_id' => Auth::id(),
                    'type'    => 21,
                    'emp_id'  => $bonus->emp_id,
                    'title'   => "تعديل علاوة #{$bonus->id}",
                    'log'     => "تم تعديل بيانات العلاوة",
                ]);
            }

            DB::commit();
            return redirect()->route('bonuses.index')->with('success', 'تم حفظ التعديلات.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'فشل التحديث: '.$e->getMessage())->withInput();
        }
    }

    /** حذف */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $b = Bonus::findOrFail($id);
            $empId = $b->emp_id;
            $seq   = $b->bonus_num;
            $b->delete();

            if (class_exists(Log::class)) {
                Log::create([
                    'user_id' => Auth::id(),
                    'type'    => 22,
                    'emp_id'  => $empId,
                    'title'   => "حذف علاوة ({$seq})",
                    'log'     => "تم حذف العلاوة رقم {$seq} للموظف #{$empId}",
                ]);
            }

            DB::commit();
            return back()->with('success', 'تم حذف العلاوة.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'تعذر الحذف: '.$e->getMessage());
        }
    }

    // ================== منطق الاستحقاق ==================

    /** يبني قائمة استحقاقات العلاوات */
    protected function buildEligibilityList(): array
    {
        $today = Carbon::today();
        $rows  = [];

        $employees = Employee::with(['person'])->get();

        foreach ($employees as $emp) {
            $base = $this->baseDateForEmployee($emp->id, $emp->start_date);
            if (!$base) continue;

            $yearsElapsed = Carbon::parse($base)->diffInYears($today);
            if ($yearsElapsed <= 0) continue;

            $bonusesSince = Bonus::where('emp_id', $emp->id)
                ->whereDate('date', '>=', $base)
                ->count();

            $pending = $yearsElapsed - $bonusesSince;
            if ($pending <= 0) continue;

            for ($i = $bonusesSince + 1; $i <= $yearsElapsed; $i++) {
                $dueDate = Carbon::parse($base)->addYears($i);
                $rows[] = [
                    'emp_id'    => $emp->id,
                    'name'      => optional($emp->person)->name ?? ("#{$emp->id}"),
                    'base_date' => Carbon::parse($base)->toDateString(),
                    'due_date'  => $dueDate->toDateString(),
                    'years'     => $i,
                    'estimate'  => $this->estimateFromFeedback($emp->id, $dueDate),
                ];
            }
        }

        // الأقدم فالأقرب (لو تبغيه العكس غيّر المقارنة)
        usort($rows, fn($a,$b) => strcmp($a['due_date'], $b['due_date']));
        return $rows;
    }

    /** المرجع الزمني: آخر عودة من إجازة (actual_end_date أو end_date) أو start_date للموظف */
    protected function baseDateForEmployee(int $empId, ?string $employeeStartDate): ?string
    {
        $latestResume = Vacation::where('emp_id', $empId)
            ->where('accept', 1)
            ->orderByRaw('COALESCE(actual_end_date, end_date) DESC')
            ->value(DB::raw('COALESCE(actual_end_date, end_date)'));

        $candidates = [];
        if (!empty($employeeStartDate)) $candidates[] = Carbon::parse($employeeStartDate);
        if (!empty($latestResume))      $candidates[] = Carbon::parse($latestResume);
        if (empty($candidates)) return null;

        $base = collect($candidates)->max(); // الأحدث
        return $base->toDateString();
    }

    /** استنتاج تقدير العلاوة من تقرير الكفاية */
    protected function estimateFromFeedback(int $empId, ?Carbon $asOf = null): ?string
    {
        $q = Feedback::where('emp_id', $empId);
        if ($asOf) {
            try { $q->where('year', '<=', (int) $asOf->year); } catch (\Throwable $e) {}
        }
        $fb = $q->orderByDesc('year')->orderByDesc('created_at')->first();
        if (!$fb) return null;

        foreach (['text_grade','textGrade','grade_text'] as $col) {
            if (isset($fb->{$col}) && $fb->{$col}) return (string)$fb->{$col};
        }
        if (isset($fb->grade) && is_numeric($fb->grade)) {
            $g = (int) $fb->grade;
            return $g >= 90 ? 'ممتاز'
                 : ($g >= 80 ? 'جيد جدًا'
                 : ($g >= 70 ? 'جيد'
                 : ($g >= 60 ? 'مقبول' : 'ضعيف')));
        }
        return null;
    }
}
