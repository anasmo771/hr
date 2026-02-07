<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\File;
use App\Models\Employee;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class VacationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vacation-list|vacation-create|vacation-edit|vacation-delete', ['only' => ['index','show','search']]);
        $this->middleware('permission:vacation-create', ['only' => ['create','createAll','store']]);
        $this->middleware('permission:vacation-edit', ['only' => ['edit','update','endVecation','updateForm']]);
        $this->middleware('permission:vacation-delete', ['only' => ['destroy']]);
        $this->middleware('permission:vacation-approve', ['only' => ['update']]);
    }

    /* -------------------- سياسة الرصيد (أقل تغييرات) -------------------- */

    /** الأنواع التي تخصم من الرصيد */
    private array $deductingTypes = ['إجازة سنوية','سنوية','اعتيادية'];

    /** 15 يوم لكل نصف سنة بعد سنة خدمة */
    private int $halfYearAccrualDays = 15;
    private int $waitingPeriodDays   = 365;

    /** نقاط التراكم السنوية: 1/1 و 1/6 */
    private array $anchors = [[1,1],[6,1]]; // [month, day]

    private function shouldDeduct(string $type): bool
    {
        return in_array($type, $this->deductingTypes, true);
    }

    /** احسب أول نقطة تراكم (1/1 أو 1/6) تلي تاريخ الانتظار */
    private function firstAnchorOnOrAfter(Carbon $waitUntil): Carbon
    {
        $y = $waitUntil->year;
        $candidates = [
            Carbon::create($y, 1, 1)->startOfDay(),
            Carbon::create($y, 6, 1)->startOfDay(),
        ];
        foreach ($candidates as $c) {
            if ($c->greaterThanOrEqualTo($waitUntil)) return $c;
        }
        return Carbon::create($y + 1, 1, 1)->startOfDay();
    }

    /** الأيام المتراكمة حتى الآن */
    private function computeAccruedDays(Employee $emp, ?Carbon $asOf = null): int
    {
        $asOf = $asOf ?: now()->startOfDay();
        if (!$emp->start_date) return 0;

        $waitUntil = Carbon::parse($emp->start_date)->addDays($this->waitingPeriodDays)->startOfDay();
        if ($asOf->lt($waitUntil)) return 0;

        $d = $this->firstAnchorOnOrAfter($waitUntil);
        $count = 0;
        while ($d->lessThanOrEqualTo($asOf)) {
            $count++;
            $d->addMonthsNoOverflow(6)->startOfDay();
        }
        return $count * $this->halfYearAccrualDays;
    }

    /** مجموع الأيام المستهلكة من إجازات معتمدة وتخصم من الرصيد */
    private function sumConsumedDays(Employee $emp): int
    {
        return (int) $emp->vacations()
            ->where('accept', 1)
            ->whereIn('type', $this->deductingTypes)
            ->sum('days');
    }

    /** إعادة حساب الرصيد بالكامل وتخزينه في employees.vacation_balance_days */
    private function recalcAndPersistBalance(Employee $emp): int
    {
        $acc  = $this->computeAccruedDays($emp);
        $used = $this->sumConsumedDays($emp);
        $bal  = max($acc - $used, 0);
        $emp->vacation_balance_days = $bal;
        $emp->saveQuietly();
        return $bal;
    }

    /** تعديل الرصيد بالدلتا مع قفل للسجل لمنع السباقات */
    private function adjustBalance(Employee $emp, int $delta): void
    {
        DB::transaction(function () use ($emp, $delta) {
            /** @var Employee $row */
            $row = Employee::whereKey($emp->id)->lockForUpdate()->first();
            if ($delta < 0 && $row->vacation_balance_days < abs($delta)) {
                throw ValidationException::withMessages(['balance' => 'رصيد غير كافٍ.']);
            }
            $row->vacation_balance_days = max(0, (int)$row->vacation_balance_days + (int)$delta);
            $row->saveQuietly();
        });
    }

    /* -------------------- الشاشات الأساسية -------------------- */

    public function index()
    {
        $vacations = Vacation::with(['employee.person','user','files'])
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('admin.Vacation.index', compact('vacations'));
    }

    public function print(Request $request)
    {
        $vacations = Vacation::with(['employee.person','user'])->orderBy('emp_id')->get();
        return view('admin.Vacation.print', compact('vacations'));
    }

    public function create($id)
    {
        $employee = Employee::findOrFail($id);
        // نحدّث الرصيد قبل العرض (يعتمد على تاريخ اليوم والتراكم)
        $vacationBalance = $this->recalcAndPersistBalance($employee);

        return view('admin.Vacation.create', compact('employee', 'vacationBalance'));
    }

    public function createAll()
    {
        $employees = Employee::with('person')->orderBy('id','desc')->get();
        return view('admin.Vacation.createAll', compact('employees'));
    }

    public function show($id)
    {
        $employee   = Employee::with(['person','vacations.files','vacations.user'])->findOrFail($id);
        $vacations  = $employee->vacations()->orderByDesc('created_at')->get();

        $vacationBalance = $this->recalcAndPersistBalance($employee);

        return view('admin.Vacation.show', compact('employee', 'vacations','vacationBalance'));
    }

    /* -------------------- إنشاء إجازة -------------------- */

    public function store(Request $request, $id)
    {
        $request->validate([
            'type'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'emp_id'     => 'nullable',
            'emp_id.*'   => 'integer|exists:employees,id',
            'files.*'    => 'nullable|file|max:10240',
        ], [
            'type.required'        => 'يجب تحديد نوع الاجازة',
            'start_date.required'  => 'يجب إدخال تاريخ بداية الإجازة',
            'end_date.required'    => 'يجب إدخال تاريخ المباشرة',
            'end_date.after_or_equal' => 'تاريخ المباشرة يجب أن يكون بعد أو يساوي تاريخ البداية',
        ]);

        // جهّز قائمة الموظفين (إما من الراوت أو من الفورم)
        $empIds = [];
        if ($id && (int)$id !== 0) {
            $empIds = [(int)$id];
        } else {
            $val = $request->input('emp_id');
            if ($val === null || $val === '') {
                return back()->with('error', 'الرجاء اختيار موظف واحد على الأقل')->withInput();
            }
            $empIds = is_array($val) ? $val : [$val];
        }

        $type       = $request->input('type');
        $startDate  = Carbon::parse($request->input('start_date'))->toDateString();
        $endDate    = Carbon::parse($request->input('end_date'))->toDateString();
        $reason     = $request->input('reason');
        $companion  = ($type === 'إجازة حج') ? (bool) $request->boolean('person') : false;
        $approved   = (bool) $request->boolean('approve');
        $inputDays  = $request->filled('days') ? (int)$request->input('days') : null;

        $okCount = 0;
        $fails   = [];

        foreach ($empIds as $empId) {
            try {
                DB::transaction(function () use (
                    $empId, $type, $startDate, $endDate, $reason, $companion, $approved, $inputDays, $request, &$okCount
                ) {
                    $emp = Employee::findOrFail((int)$empId);

                    // منع وجود إجازة غير معتمدة قيد الإجراء لنفس الموظف
                    if (Vacation::where([['emp_id', $emp->id], ['accept', false]])->exists()) {
                        throw new \RuntimeException('توجد إجازة قيد الإجراء لهذا الموظف');
                    }

                    $vac = new Vacation();
                    $vac->emp_id          = $emp->id;
                    $vac->type            = $type;
                    $vac->start_date      = $startDate;
                    $vac->end_date        = $endDate;
                    $vac->actual_end_date = null;
                    $vac->reason          = $reason;
                    $vac->companion       = $companion;
                    $vac->accept          = $approved;
                    $vac->created_id      = auth()->id();

                    // حساب الأيام (فرق بسيط + قواعد الأنواع الثابتة)
                    $days = $inputDays ?? Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
                    if ($days <= 0) { $days = 1; }

                    switch ($type) {
                        case 'إجازة بدون مرتب':
                            if (!$companion) $days = 365; break;
                        case 'إجازة وضع':
                            $days = 90; break;
                        case 'إجازة زواج':
                            $days = 14; break;
                        case 'إجازة حج':
                            $days = 90; break;
                        case 'إجازة وفاة الزوج':
                            $days = 130; break;
                        // السنوية تبقى حسب الفرق أو value واردة
                    }
                    $vac->days = (int) $days;

                    // تحقق من رصيد الإجازة السنوية قبل الاعتماد المباشر
                    if ($approved && $this->shouldDeduct($type)) {
                        $available = $this->recalcAndPersistBalance($emp);
                        if ($available < $vac->days) {
                            throw ValidationException::withMessages(['balance' => 'لا يوجد رصيد كافٍ للإجازة.']);
                        }
                    }

                    $vac->save();

                    // مرفقات
                    if ($request->hasFile('files')) {
                        $rows = [];
                        foreach ($request->file('files') as $file) {
                            $fn = 'VAC_' . $vac->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs('VACATION', $fn, 'public');
                            $rows[] = [
                                'type'         => 'vacation',
                                'procedure_id' => $vac->id,
                                'path'         => $path,
                            ];
                        }
                        if ($rows) File::insert($rows);
                    }

                    // خصم الرصيد فورًا إذا تم اعتماد الإجازة عند الإنشاء وكانت تخصم
                    if ($approved && $this->shouldDeduct($type)) {
                        $this->adjustBalance($emp, -$vac->days);
                    }

                    // لوج
                    Log::create([
                        'user_id' => auth()->id(),
                        'type'    => 7,
                        'emp_id'  => $emp->id,
                        'title'   => "إضافة إجازة جديدة ({$type})",
                        'log'     => "تمت إضافة إجازة جديدة ({$type})",
                    ]);

                    // منطقك القديم: إجازة الزواج تغيّر حالة الموظف
                    if ($type === 'إجازة زواج') {
                        $emp->status = 1;
                        $emp->saveQuietly();
                    }

                    $okCount++;
                });
            } catch (\Throwable $e) {
                $name = optional(Employee::with('person')->find($empId))->person->name ?? "#{$empId}";
                $fails[] = $name . ' — ' . $e->getMessage();
            }
        }

        if ($okCount > 0 && empty($fails)) {
            if (count($empIds) === 1) {
                return redirect()->route('vacations.show', [$empIds[0]])->with('success', 'تــمــت إضافة الإجازة بنجاح');
            }
            return back()->with('success', "تم إنشاء {$okCount} إجازة بنجاح.");
        } elseif ($okCount > 0 && !empty($fails)) {
            return back()->with('success', "تم إنشاء {$okCount} إجازة، وفشلت لبعض الموظفين: " . implode(' | ', $fails));
        } else {
            return back()->with('error', 'فشل إنشاء الإجازة: ' . implode(' | ', $fails))->withInput();
        }
    }

    /* -------------------- اعتماد إجازة -------------------- */

    public function update(Request $request)
    {
        $request->validate([
            'id'   => 'required|integer|exists:vacations,id',
            'file' => 'nullable|file|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $vac = Vacation::with('employee')->findOrFail($request->id);

            $wasAccepted = (bool) $vac->accept;
            $vac->accept = true;

            if ($request->hasFile('file')) {
                $ext = $request->file('file')->getClientOriginalExtension();
                $fileName = 'VAC_ACCEPT_' . $vac->id . '_' . uniqid() . '.' . $ext;
                $path = $request->file('file')->storeAs('VACATION_ACCEPT', $fileName, 'public');
                $vac->acceptFile = $path;
            }

            $vac->save();

            // خصم الرصيد عند الاعتماد لأول مرة فقط وللأنواع الخصمية
            if (!$wasAccepted && $this->shouldDeduct($vac->type)) {
                // تحقّق قبل الخصم
                $available = $this->recalcAndPersistBalance($vac->employee);
                if ($available < $vac->days) {
                    DB::rollBack();
                    return back()->with('error','رصيد غير كافٍ للاعتماد.');
                }
                $this->adjustBalance($vac->employee, -$vac->days);
            }

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 8,
                'emp_id'  => $vac->emp_id,
                'title'   => "اعتماد إجازة ({$vac->type})",
                'log'     => "تم اعتماد إجازة ({$vac->type})",
            ]);

            DB::commit();
            return back()->with('success', 'تم اعتماد الإجازة بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'تعذر اعتماد الإجازة');
        }
    }

    /* -------------------- الرجوع من الإجازة -------------------- */

    public function endVecation($id)
    {
        $vac = Vacation::with('employee')->findOrFail($id);

        if (!$vac->accept)          return back()->with('error','لا يمكن الرجوع من إجازة غير معتمدة.');
        if ($vac->actual_end_date)  return back()->with('error','تم تسجيل الرجوع مسبقًا.');
        if (!$vac->start_date || !$vac->end_date) return back()->with('error','تواريخ الإجازة غير مكتملة.');

        $today = now()->startOfDay();
        $start = Carbon::parse($vac->start_date)->startOfDay();
        $plannedEnd = Carbon::parse($vac->end_date)->startOfDay();

        if (!($start->lte($today) && $today->lte($plannedEnd))) {
            return back()->with('error','يمكن تسجيل الرجوع فقط أثناء فترة الإجازة.');
        }

        DB::beginTransaction();
        try {
            $vac->actual_end_date = $today->toDateString();

            $oldDays = (int) $vac->days;
            $usedDays = $start->diffInDays($today);
            if ($usedDays < 0) $usedDays = 0;

            $vac->days = (int) $usedDays;
            $vac->save();

            // استرجاع الفرق للرصيد إن كانت الإجازة تخصم
            if ($this->shouldDeduct($vac->type) && $oldDays > $vac->days) {
                $this->adjustBalance($vac->employee, +($oldDays - $vac->days));
            }

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 12,
                'emp_id'  => $vac->emp_id,
                'title'   => "الرجوع من الإجازة ({$vac->type})",
                'log'     => "تم تسجيل الرجوع الفعلي بتاريخ {$vac->actual_end_date} وعدد الأيام المحتسبة الفعلية {$vac->days}",
            ]);

            DB::commit();
            return back()->with('success','تم تسجيل الرجوع من الإجازة بنجاح وتم حفظ الأيام المتبقية في الرصيد.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','تعذر تسجيل الرجوع من الإجازة.');
        }
    }

    /* -------------------- تعديل بيانات إجازة (غير الاعتماد) -------------------- */

    public function edit($id)
    {
        $vac = Vacation::with(['employee.person','employee.vacations'])->findOrFail($id);
        $employee = $vac->employee;

        $vacationBalance = $this->recalcAndPersistBalance($employee);

        return view('admin.Vacation.edit', compact('vac','employee','vacationBalance'));
    }

    public function updateForm(Request $request, $id)
    {
        $request->validate([
            'type'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:255',
            'person'     => 'nullable|boolean',
        ]);

        $vac = Vacation::with('employee')->findOrFail($id);
        if ($vac->delete_at) {
            return back()->with('error','لا يمكن تعديل سجل محذوف.');
        }

        $oldDays = (int) $vac->days;
        $oldType = (string) $vac->type;

        $vac->type        = $request->type;
        $vac->start_date  = Carbon::parse($request->start_date)->toDateString();
        $vac->end_date    = Carbon::parse($request->end_date)->toDateString();
        $vac->reason      = $request->input('reason');
        $vac->companion   = ($request->type === 'إجازة حج') ? (bool)$request->boolean('person') : false;

        $days = Carbon::parse($vac->start_date)->diffInDays(Carbon::parse($vac->end_date));
        if ($days <= 0) $days = 1;

        switch ($vac->type) {
            case 'إجازة بدون مرتب':
                if (!$vac->companion) $days = 365; break;
            case 'إجازة وضع':
                $days = 90; break;
            case 'إجازة زواج':
                $days = 14; break;
            case 'إجازة حج':
                $days = 90; break;
            case 'إجازة وفاة الزوج':
                $days = 130; break;
        }
        $vac->days = (int)$days;

        DB::transaction(function () use ($vac, $oldDays, $oldType) {
            $vac->save();

            // لو كانت الإجازة معتمدة، عدّل الرصيد حسب التغيير
            if ($vac->accept) {
                $wasDed = $this->shouldDeduct($oldType);
                $nowDed = $this->shouldDeduct($vac->type);

                if ($wasDed && !$nowDed) {
                    $this->adjustBalance($vac->employee, +$oldDays); // رجّع كامل القديم
                } elseif (!$wasDed && $nowDed) {
                    $this->adjustBalance($vac->employee, -$vac->days); // اخصم الجديد
                } elseif ($wasDed && $nowDed && $vac->days !== $oldDays) {
                    $this->adjustBalance($vac->employee, $oldDays - $vac->days); // موجب=رجوع، سالب=خصم
                }
            }
        });

        Log::create([
            'user_id' => auth()->id(),
            'type'    => 9,
            'emp_id'  => $vac->emp_id,
            'title'   => "تعديل إجازة ({$vac->type})",
            'log'     => "تم تعديل بيانات الإجازة",
        ]);

        return redirect()->route('vacations.show', [$vac->emp_id])->with('success','تم حفظ التعديلات.');
    }

    /* -------------------- حذف إجازة -------------------- */

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            File::where('procedure_id', $id)->where('type', 'vacation')->update(['delete_at'=> now()]);

            $vac = Vacation::with('employee')->findOrFail($id);

            // لو كانت الإجازة معتمدة وتخصم، رجّع أيامها قبل الحذف
            if ($vac->accept && $this->shouldDeduct($vac->type)) {
                $this->adjustBalance($vac->employee, + (int) $vac->days);
            }

            $vac->delete_at = now();
            $vac->delete();

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 11,
                'emp_id'  => $vac->emp_id,
                'title'   => "حذف بيانات إجازة ({$vac->type})",
                'log'     => "تم حذف بيانات إجازة ({$vac->type})",
            ]);

            DB::commit();
            return back()->with('success','تـم حـذفـ إجــازة الـمـوظـف بـنـجـاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','حدث خطأ أثناء الحذف.');
        }
    }
}
