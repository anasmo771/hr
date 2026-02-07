<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\File;
use App\Models\Person;
use App\Models\Employee;
use App\Models\Punishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PunishmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:punishment-list',   ['only' => ['index','show','search']]);
        $this->middleware('permission:punishment-create', ['only' => ['create','createAll','store']]);
        $this->middleware('permission:punishment-edit',   ['only' => ['edit','update']]);
        $this->middleware('permission:punishment-delete', ['only' => ['destroy']]);
    }

    /** قائمة */
    public function index()
    {
        $punshes = Punishment::with(['emp.person','user','files'])
            ->latest('id')
            ->paginate(25);

        return view('admin.Punishment.index', compact('punshes'));
    }

    /** طباعة */
    public function print(Request $request)
    {
        $punshes = Punishment::with(['emp.person','user'])->orderBy('emp_id')->get();
        return view('admin.Punishment.print', compact('punshes'));
    }

    /** إنشاء لموظف محدد */
    public function create($id)
    {
        $emp = Employee::find($id);
        return view('admin.Punishment.create', compact('emp'));
    }

    /** إنشاء جماعي */
    public function createAll()
    {
        $employees = Employee::whereNull('startout_data')->get();
        return view('admin.Punishment.createAll', compact('employees'));
    }

    /** بحث */
    public function search(Request $request)
    {
        if (!request('name')) {
            return back()->with('error', 'الرجاء تعبئة احدي الخانات علي الاقل');
        }

        $persons = Person::where('name', 'LIKE', '%' . request('name') . '%')->pluck('id');
        $emps    = Employee::whereIn('person_id', $persons)->pluck('id');

        $punshes = Punishment::with('emp.person')
            ->whereIn('emp_id', $emps)
            ->latest()
            ->paginate(20);

        if ($punshes->count() === 0) {
            return back()->with('error', 'الـمـوظـف الــذي تبــحث عنــها غيــر موجــود');
        }

        $query = $request->all();
        return view('admin.Punishment.index', compact('punshes','query'));
    }

    /** حفظ جديد */
    public function store(Request $request, $id)
    {
        $request->validate(
            [
                // أزلنا "إيقاف مؤقت" و"فصل"
                'pun_type'     => 'required|in:العزل من الخدمة,خفض الدرجة,بلا عقوبة,الحرمان من العلاوات,الخصم من المرتب,لفت نظر,اللوم,الإنذار',
                'pun_date'     => 'required|date',
                'index'        => 'required|string',
                'book_num'     => 'required|string',
                'penaltyName'  => 'required|string',
                'reason'       => 'required|string',
                'files.*'      => 'nullable|file|max:10240',
            ],
            [
                'pun_type.required' => 'يجب إدخال نوع العقوبة',
                'pun_type.in'       => 'يجب تحديد نوع العقوبة من الخيارات المعروضة',
                'pun_date.required' => 'يجب ادخال تاريخ العقوبة',
                'index.required'    => 'يجب ادخال الرقم الإشاري',
                'book_num.required' => 'يجب ادخال رقم الكتاب',
                'penaltyName.required' => 'يجب ادخال اسم من اوصي بالعقوبة',
                'reason.required'   => 'يجب ادخال سبب العقوبة',
            ]
        );

        DB::beginTransaction();
        try {
            $employee = ($id == 0)
                ? Employee::findOrFail((int) $request->input('emp_id'))
                : Employee::findOrFail((int) $id);

            // قيد الدرجة لعقوبة الإنذار
            if ($request->pun_type === 'الإنذار' && (int)$employee->degree > 10) {
                DB::rollBack();
                return back()->with('error', 'لا يمكن إعطاء (الإنذار) لمن درجته 11 فما فوق');
            }

            $p = new Punishment();
            $p->emp_id      = $employee->id;
            $p->reason      = $request->reason;
            $p->pun_type    = $request->pun_type;
            $p->pun_date    = Carbon::parse($request->pun_date)->toDateString();
            $p->book_num    = $request->book_num;      // رقم الكتاب
            $p->index       = $request->index;
            $p->penaltyName = $request->penaltyName;
            $p->created_id  = auth()->id();
            $p->notes       = $request->notes;
            $p->save();

            // مرفقات
            if ($request->hasFile('files')) {
                $rows = [];
                foreach ($request->file('files') as $file) {
                    $fn   = 'PUN_' . $p->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('PUNISHMENT', $fn, 'public');
                    $rows[] = [
                        'type'         => 'punishment',
                        'procedure_id' => $p->id,
                        'path'         => $path,
                    ];
                }
                if ($rows) File::insert($rows);
            }

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 9,
                'emp_id'  => $employee->id,
                'title'   => " اضافة عقوبة جديدة ({$p->pun_type})",
                'log'     => " تمت إضافة عقوبة جديدة ({$p->pun_type})",
            ]);

            DB::commit();
            return redirect()->route('punishments.show', [$employee->id])
                ->with('success', 'تــمــت إضــافــة عـقـوبـة للـمـوظــف بــنــجــاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'للأسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    /** عرض */
    public function show($id)
    {
        $emp = Employee::find($id);
        $punshes = Punishment::with(['user','files'])
            ->where('emp_id', $id)
            ->latest('id')
            ->paginate(25);

        return view('admin.Punishment.show', compact('emp', 'punshes'));
    }

    /** تعديل */
    public function edit($id)
    {
        $punshe = Punishment::find($id);
        $emp    = Employee::find($punshe->emp_id);
        return view('admin.Punishment.edit', compact('emp', 'punshe'));
    }

    /** حفظ التعديل */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'pun_type'     => 'required|in:العزل من الخدمة,خفض الدرجة,بلا عقوبة,الحرمان من العلاوات,الخصم من المرتب,لفت نظر,اللوم,الإنذار',
                'pun_date'     => 'required|date',
                'index'        => 'required|string',
                'book_num'     => 'required|string',
                'penaltyName'  => 'required|string',
                'reason'       => 'required|string',
                'files.*'      => 'nullable|file|max:10240',
            ],
            [
                'pun_type.required' => 'يجب إدخال نوع العقوبة',
                'pun_type.in'       => 'يجب تحديد نوع العقوبة من الخيارات المعروضة',
                'pun_date.required' => 'يجب ادخال تاريخ العقوبة',
                'index.required'    => 'يجب ادخال الرقم الإشاري',
                'book_num.required' => 'يجب ادخال رقم الكتاب',
                'penaltyName.required' => 'يجب ادخال اسم من اوصي بالعقوبة',
                'reason.required'   => 'يجب ادخال سبب العقوبة',
            ]
        );

        DB::beginTransaction();
        try {
            $p = Punishment::findOrFail($id);
            $employee = Employee::findOrFail($p->emp_id);

            if ($request->pun_type === 'الإنذار' && (int)$employee->degree > 10) {
                DB::rollBack();
                return back()->with('error', 'لا يمكن إعطاء (الإنذار) لمن درجته 11 فما فوق');
            }

            $p->reason      = $request->reason;
            $p->pun_type    = $request->pun_type;
            $p->pun_date    = Carbon::parse($request->pun_date)->toDateString();
            $p->book_num    = $request->book_num;   // رقم الكتاب
            $p->index       = $request->index;
            $p->penaltyName = $request->penaltyName;
            $p->created_id  = auth()->id();
            $p->notes       = $request->notes;
            $p->save();

            // مرفقات جديدة
            if ($request->hasFile('files')) {
                $rows = [];
                foreach ($request->file('files') as $file) {
                    $fn   = 'PUN_' . $p->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('PUNISHMENT', $fn, 'public');
                    $rows[] = [
                        'type'         => 'punishment',
                        'procedure_id' => $p->id,
                        'path'         => $path,
                    ];
                }
                if ($rows) File::insert($rows);
            }

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 9,
                'emp_id'  => $employee->id,
                'title'   => "تعديل عقوبة ({$p->pun_type})",
                'log'     => "تم تعديل بيانات العقوبة ({$p->pun_type})",
            ]);

            DB::commit();
            return redirect()->route('punishments.show', [$employee->id])
                ->with('success', 'تــم تـعـديــل عـقـوبـة للـمـوظــف بــنــجــاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'للأسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    /** حذف */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            File::where('procedure_id', $id)
                ->where('type', 'punishment')
                ->update(['delete_at'=> now()]);

            $abs = Punishment::findOrFail($id);
            $abs->delete_at = now();
            $abs->delete();

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 11,
                'emp_id'  => $abs->emp_id,
                'title'   => "حذف بيانات العقوبة ({$abs->pun_type})",
                'log'     => "تم حذف بيانات العقوبة ({$abs->pun_type})",
            ]);

            DB::commit();
            return back()->with('success','تـم حـذفـ العقوبة الـمـوظـف بـنـجـاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'للأسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }
}
