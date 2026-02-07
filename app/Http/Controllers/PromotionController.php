<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:promotion-list|promotion-create|promotion-edit|promotion-delete', ['only' => ['index']]);
        $this->middleware('permission:promotion-create', ['only' => ['create','store','quickStore']]);
        $this->middleware('permission:promotion-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:promotion-delete', ['only' => ['destroy']]);
    }

    /** قائمة الترقيات + إشعارات الاستحقاق */
    public function index()
    {
        $bonuses = Bonus::with(['emp.person'])->latest('id')->get();

        // إشعارات الاستحقاق (نظامية/استثنائية/ندب)
        $eligibles = $this->buildPromotionEligibles();

        // الترقيات المسجلة
        $promotions = Promotion::with(['emp.person','user'])
            ->latest('id')
            ->get();

        return view('admin.Promotion.index', compact('bonuses','eligibles','promotions'));
    }

    /** شاشة إنشاء (يدوي) */
    public function create($empId = null)
    {
        $employees = Employee::with('person')->whereNull('startout_data')->get();
        $employee  = $empId ? Employee::with('person')->findOrFail($empId) : null;

        // للعرض فقط
        $bonusesCount = $employee
            ? Bonus::where('emp_id', $employee->id)->count()
            : null;

        return view('admin.Promotion.create', compact('employees','employee','bonusesCount'));
    }

    public function store(Request $request, $empId = null)
    {
        $empId = $empId ?: $request->input('emp_id');

        $data = $request->validate([
            'emp_id'      => ['required','exists:employees,id'],
            'num'         => ['nullable','string','max:255'],
            'type'        => ['required','in:regular,exceptional,acting'],
            'date'        => ['nullable','date'],
            'prev_degree' => ['required','integer'],
            'new_degree'  => [
                'required','integer',
                Rule::unique('promotions')->where(fn($q) => $q->where('emp_id',$empId))
            ],
        ],[
            'type.required'    => 'نوع الترقية مطلوب.',
            'new_degree.unique'=> 'هناك ترقية لنفس الدرجة مسبقًا لهذا الموظف.',
        ]);

        $employee = Employee::with('unitStaffing.staffing')->findOrFail($empId);

        DB::beginTransaction();
        try {
            $consumed = null;

            if ($data['type'] === 'regular') {
                [$ok, $used] = $this->findRegularSet($employee->id);
                if (!$ok) throw new \RuntimeException('لا تتوفر شروط الترقية النظامية (4 علاوات منها 3 بتقدير أعلى من جيد).');
                $consumed = $used;
            } elseif ($data['type'] === 'exceptional') {
                [$ok, $used] = $this->findExceptionalSet($employee->id);
                if (!$ok) throw new \RuntimeException('لا تتوفر شروط الترقية الاستثنائية (3 ممتاز متتالية).');
                $consumed = $used;
            }
            // acting: لا يستهلك علاوات

            $promotion = Promotion::create([
                'emp_id'            => $employee->id,
                'num'               => $data['num'] ?? null,
                'type'              => $data['type'],
                'prev_degree'       => $data['prev_degree'],
                'new_degree'        => $data['new_degree'],
                'date'              => $data['date'] ?? now()->toDateString(),
                'created_id'        => auth()->id(),
                'accept'            => 1,
                'consumed_bonus_ids'=> $consumed ?: [],
            ]);

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 31,
                'emp_id'  => $employee->id,
                'title'   => "إضافة ترقية ({$promotion->type})",
                'log'     => "من {$promotion->prev_degree} إلى {$promotion->new_degree}، قرار: ".($promotion->num ?? '-'),
            ]);

            DB::commit();
            return redirect()->route('promotion.index')->with('success','تمت إضافة الترقية بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','فشل الإضافة: '.$e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $data = $request->validate([
            'num'         => ['nullable','string','max:255'],
            'type'        => ['required','in:regular,exceptional,acting'],
            'date'        => ['nullable','date'],
            'prev_degree' => ['required','integer'],
            'new_degree'  => [
                'required','integer',
                Rule::unique('promotions')->where(fn($q)=>$q->where('emp_id',$promotion->emp_id))->ignore($promotion->id)
            ],
        ]);

        DB::beginTransaction();
        try {
            $consumed = $promotion->consumed_bonus_ids;

            if (in_array($data['type'], ['regular','exceptional'], true) && empty($consumed)) {
                if ($data['type'] === 'regular') {
                    [$ok,$used] = $this->findRegularSet($promotion->emp_id);
                    if (!$ok) throw new \RuntimeException('لا تتوفر شروط الترقية النظامية.');
                    $consumed = $used;
                } else {
                    [$ok,$used] = $this->findExceptionalSet($promotion->emp_id);
                    if (!$ok) throw new \RuntimeException('لا تتوفر شروط الترقية الاستثنائية.');
                    $consumed = $used;
                }
            }

            if ($data['type'] === 'acting') {
                $consumed = null; // ندب لا يستهلك
            }

            $promotion->fill([
                'num'         => $data['num'] ?? null,
                'type'        => $data['type'],
                'date'        => $data['date'] ?? $promotion->date,
                'prev_degree' => $data['prev_degree'],
                'new_degree'  => $data['new_degree'],
                'consumed_bonus_ids' => $consumed,
            ])->save();

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 32,
                'emp_id'  => $promotion->emp_id,
                'title'   => "تعديل ترقية ({$promotion->type})",
                'log'     => "إلى درجة {$promotion->new_degree}",
            ]);

            DB::commit();
            return redirect()->route('promotion.index')->with('success','تم تحديث الترقية.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','فشل التعديل: '.$e->getMessage());
        }
    }

    /** تعديل */
    public function edit($id)
    {
        $promotion = Promotion::with(['emp.person'])->findOrFail($id);
        return view('admin.Promotion.edit', compact('promotion'));
    }

    /** حذف */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        DB::beginTransaction();
        try {
            $promotion->delete();

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 33,
                'emp_id'  => $promotion->emp_id,
                'title'   => "حذف ترقية",
                'log'     => "حُذفت ترقية درجة {$promotion->new_degree}",
            ]);

            DB::commit();
            return back()->with('success','تم حذف الترقية.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','فشل الحذف: '.$e->getMessage());
        }
    }

    /** تنفيذ سريع من إشعار الاستحقاق */
    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'emp_id' => ['required','exists:employees,id'],
            'ptype'  => ['required','in:regular,exceptional,acting'],
        ]);

        $employee = Employee::with('unitStaffing.staffing')->findOrFail($data['emp_id']);
        $today    = now()->toDateString();

        DB::beginTransaction();
        try {
            $used = null;
            $step = 1;

            if ($data['ptype'] === 'regular') {
                [$ok,$used] = $this->findRegularSet($employee->id);
                if (!$ok) throw new \RuntimeException('لا تتوفر شروط الترقية النظامية.');
            } elseif ($data['ptype'] === 'exceptional') {
                [$ok,$used] = $this->findExceptionalSet($employee->id);
                if (!$ok) throw new \RuntimeException('لا تتوفر ثلاث علاوات ممتاز متتالية.');
            } else { // acting
                [$ok,$step,$title] = $this->actingLeadershipStep($employee);
                if (!$ok) throw new \RuntimeException('لا يوجد ملاك قيادي فعّال لهذا الموظف.');
                $used = null; // لا يستهلك
            }

            $prev = (int) $employee->degree;
            $new  = $prev + (int) $step;

            Promotion::create([
                'emp_id'      => $employee->id,
                'num'         => null,
                'type'        => $data['ptype'],
                'prev_degree' => $prev,
                'new_degree'  => $new,
                'date'        => $today,
                'created_id'  => auth()->id(),
                'accept'      => 1,
                'consumed_bonus_ids' => $used,
            ]);

            DB::commit();
            return back()->with('success','تم تنفيذ الترقية.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','فشل تنفيذ الترقية: '.$e->getMessage());
        }
    }

    public function show($empId)
    {
        $emp = Employee::with('person')->findOrFail($empId);

        $promotions = Promotion::with(['user'])
            ->where('emp_id', $empId)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.Promotion.show', compact('emp', 'promotions'));
    }

    public function createAll()
    {
        return $this->create(null);
    }

    public function show1(Request $request)
    {
        $q = trim((string) $request->input('name'));
        if ($q === '') {
            return back()->with('error','الرجاء إدخال اسم للبحث');
        }

        $empIds = Employee::whereHas('person', fn($qq) =>
            $qq->where('name','like',"%{$q}%")
        )->pluck('id');

        if ($empIds->isEmpty()) {
            return back()->with('error','لا يوجد موظفون مطابقون');
        }

        $bonuses = Bonus::with(['emp.person'])
            ->whereIn('emp_id', $empIds)
            ->latest('id')->get();

        $promotions = Promotion::with(['emp.person','user'])
            ->whereIn('emp_id', $empIds)
            ->latest('id')->get();

        $eligibles = $this->buildPromotionEligibles();

        $query = ['name' => $q];
        return view('admin.Promotion.index', compact('bonuses','eligibles','promotions','query'));
    }

    /* ===================== منطق الاستحقاق ===================== */

    /** إشعارات الاستحقاق لكل الموظفين (نظامية/استثنائية/ندب) */
    protected function buildPromotionEligibles(): array
    {
        $rows = [];
        $employees = Employee::with(['person','unitStaffing.staffing'])
            ->whereNull('startout_data')->get();

        foreach ($employees as $emp) {
            // نظامية
            [$okR,$setR] = $this->findRegularSet($emp->id);
            if ($okR) {
                $rows[] = [
                    'type'    => 'regular',
                    'emp_id'  => $emp->id,
                    'name'    => $emp->person->name ?? "#{$emp->id}",
                    'degree'  => $emp->degree,
                    'used'    => $setR,
                    'note'    => '4 علاوات (3 منها أعلى من جيد)',
                ];
            }

            // استثنائية
            [$okE,$setE] = $this->findExceptionalSet($emp->id);
            if ($okE) {
                $rows[] = [
                    'type'    => 'exceptional',
                    'emp_id'  => $emp->id,
                    'name'    => $emp->person->name ?? "#{$emp->id}",
                    'degree'  => $emp->degree,
                    'used'    => $setE,
                    'note'    => '3 ممتاز متتالية',
                ];
            }

            // ندب (قيادي)
            [$okA,$stepA,$titleA] = $this->actingLeadershipStep($emp);
            if ($okA) {
                $rows[] = [
                    'type'    => 'acting',
                    'emp_id'  => $emp->id,
                    'name'    => $emp->person->name ?? "#{$emp->id}",
                    'degree'  => $emp->degree,
                    'used'    => [], // لا يستهلك علاوات
                    'note'    => "ندب قيادي: {$titleA} (+{$stepA})",
                ];
            }
        }

        usort($rows, fn($a,$b)=>strcmp($a['name'],$b['name']));
        return $rows;
    }

    /** مجموعة نظامية: 4 غير مستهلكة، >=3 «جيد جدًا/ممتاز» */
    protected function findRegularSet(int $empId): array
    {
        $unused = $this->unusedBonuses($empId);
        if ($unused->count() < 4) return [false, []];

        $ordered = $unused->sortBy(fn($b)=>$this->bonusDate($b))->values();
        $pick = $ordered->take(4);

        $good = $pick->filter(fn($b)=>$this->isAboveGood($b->estimate))->count();
        if ($good >= 3) return [true, $pick->pluck('id')->all()];

        // نافذة منزلقة
        for ($i=0; $i+3 < $ordered->count(); $i++) {
            $win = $ordered->slice($i,4);
            $good = $win->filter(fn($b)=>$this->isAboveGood($b->estimate))->count();
            if ($good >= 3) return [true, $win->pluck('id')->all()];
        }
        return [false, []];
    }

    /** مجموعة استثنائية: 3 ممتاز متتالية وغير مستهلكة */
    protected function findExceptionalSet(int $empId): array
    {
        $unused = $this->unusedBonuses($empId)->sortBy(fn($b)=>$this->bonusDate($b))->values();
        $streak = [];

        foreach ($unused as $b) {
            if ($this->isExcellent($b->estimate)) {
                $streak[] = $b->id;
                if (count($streak) >= 3) return [true, array_slice($streak,-3)];
            } else {
                $streak = [];
            }
        }
        return [false, []];
    }

    /** علاوات غير مستهلكة منذ آخر ترقية */
    protected function unusedBonuses(int $empId)
    {
        $consumed = Promotion::where('emp_id', $empId)
            ->pluck('consumed_bonus_ids')
            ->filter()
            ->flatMap(function ($val) {
                if (is_array($val)) return $val;
                $dec = is_string($val) ? json_decode($val, true) : null;
                if (is_array($dec)) return $dec;
                if (is_string($val) && strpos($val, ',') !== false) {
                    return array_filter(array_map('intval', explode(',', $val)));
                }
                return [];
            })
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        return Bonus::where('emp_id', $empId)
            ->whereNotIn('id', $consumed)
            ->get();
    }

    protected function bonusDate(Bonus $b)
    {
        return Carbon::parse($b->date ?? $b->bonus_date ?? $b->created_at);
    }

    // تطبيع النص العربي
    protected function normalizeArabic(?string $s): string
    {
        $s = trim((string)$s);
        $s = str_replace(['أ','إ','آ'], 'ا', $s);
        $s = preg_replace('/[ًٌٍَُِّْـ]/u', '', $s) ?? $s;
        $s = preg_replace('/\s+/u', ' ', $s) ?? $s;
        return $s;
    }

    protected function isExcellent(?string $est): bool
    {
        return $this->normalizeArabic($est) === 'ممتاز';
    }

    protected function isAboveGood(?string $est): bool
    {
        $e = $this->normalizeArabic($est);
        if ($e === 'ممتاز') return true;
        return (bool) preg_match('/^جيد\s*جدا$/u', $e);
    }

    /** اقتراح استهلاك تلقائي للعلاوات عند الحفظ اليدوي (لو أردت استعمالها) */
    protected function resolveConsumedBonusesForManualStore(string $type, Employee $emp): ?array
    {
        if ($type === 'regular') {
            [$ok, $used] = $this->findRegularSet($emp->id);
            return $ok ? $used : null;
        }
        if ($type === 'exceptional') {
            [$ok, $used] = $this->findExceptionalSet($emp->id);
            return $ok ? $used : null;
        }
        return null; // acting لا يستهلك تلقائيًا
    }

    /**
     * ندب قيادي: يقرأ من علاقة الموظف -> unitStaffing -> staffing
     * - يشترط is_manager=true أو عنوان وظيفي قيادي.
     * - يعيد [eligible, step, title] حيث step=2 لمدير، 1 لرئيس.
     */
    protected function actingLeadershipStep(Employee $employee): array
    {
        $u = $employee->unitStaffing;            // App\Models\UnitStaffing
        $title = $u?->staffing?->name ?? '';     // المسمّى (من staffing)
        $isManager = (bool) ($u?->is_manager ?? false);

        if (!$u) return [false, 0, null];

        [$mappedTitle, $step] = $this->mapLeadershipTitleToStep($title, $isManager);
        if ($step <= 0) return [false, 0, null];

        return [true, $step, $mappedTitle];
    }

    /** يطابق المسمى الوظيفي مع مقدار الزيادة */
    protected function mapLeadershipTitleToStep(string $title, bool $isManager): array
    {
        $t = mb_strtolower($title, 'UTF-8');

        // مدير إدارة/عام => درجتان
        if (str_contains($t, 'مدير') || str_contains($t, 'director')) return [$title, 2];

        // رئيس قسم/شعبة => درجة واحدة
        if (str_contains($t, 'رئيس') || str_contains($t, 'head'))     return [$title, 1];

        // لو العَلَم is_manager=true بس العنوان غير واضح، اعتبرها درجة واحدة
        if ($isManager) return [$title ?: 'مسمى قيادي', 1];

        return [$title, 0];
    }
}
