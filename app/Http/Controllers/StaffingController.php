<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Staffing;
use App\Models\UnitStaffing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StaffingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-list',   ['only' => ['index','show','search']]);
        $this->middleware('permission:system-create', ['only' => ['create','store']]);
        $this->middleware('permission:system-edit',   ['only' => ['edit','update']]);
        $this->middleware('permission:system-delete', ['only' => ['destroy']]);
    }

    /**
     * عرض قائمة الملاك الوظيفي.
     * تم إضافة withCount للوحدات والموظفين (عبر hasManyThrough).
     */
    public function index()
    {
        $all = Staffing::query()
            ->withCount([
                'unitStaffings as units_count',
                // إجمالي الموظفين المنسوبين لهذا الملاك عبر الوحدات
                'employees as employees_count',
                // الموظفون الذين يعملون فقط (اختياري)
                'employees as working_employees_count' => function ($q) {
                    $q->where('status', 'يعمل');
                },
            ])
            ->latest('id')
            ->paginate(25);

        return view('admin.Staffing.index', compact('all'));
    }

    /**
     * نموذج إنشاء ملاك وظيفي.
     */
    public function create()
    {
        return view('admin.Staffing.create');
    }

    /**
     * حفظ ملاك وظيفي جديد.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required','string','max:255','unique:staffings,name'],
            ],
            [
                'name.required' => 'يجب إدخال اسم الملاك الوظيفي',
                'name.unique'   => 'اسم الملاك الوظيفي الذي ادخلته موجود مسبقًا، الرجاء التحقق',
            ]
        );

        $staffing       = new Staffing();
        $staffing->name = $request->name;
        $staffing->save();

        $log = new Log();
        $log->user_id = auth()->id();
        $log->type    = 14;
        $log->title   = " اضافة ملاك وظيفي جديد ({$request->name})";
        $log->log     = " تمت إضافة ملاك وظيفي جديد ({$request->name})";
        $log->save();

        return back()->with('success', 'تــمــت إضــافــة الـمـلاك الـوظـيـفـي بــنــجــاح');
    }

    /**
     * عرض تفاصيل ملاك وظيفي محدد (اختياري).
     * يُظهر الوحدات التابعة له مع عدد الموظفين بكل وحدة.
     */
    public function show($id)
    {
        $staffing = Staffing::with([
            'unitStaffings' => function ($q) {
                $q->withCount('employees');
            },
        ])->findOrFail($id);

        return view('admin.Staffing.show', compact('staffing'));
    }

    /**
     * نموذج تعديل ملاك وظيفي.
     */
    public function edit($id)
    {
        $Staffing = Staffing::findOrFail($id);
        return view('admin.Staffing.edit', compact('Staffing'));
    }

    /**
     * تحديث ملاك وظيفي.
     * ملاحظة: تم تعديل التحقق من الفرادة ليتجاهل السجل الحالي.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => [
                    'required', 'string', 'max:255',
                    Rule::unique('staffings', 'name')->ignore($id),
                ],
            ],
            [
                'name.required' => 'يجب إدخال اسم الملاك الوظيفي',
                'name.unique'   => 'اسم الملاك الوظيفي الذي ادخلته موجود مسبقًا، الرجاء التحقق',
            ]
        );

        $staffing       = Staffing::findOrFail($id);
        $staffing->name = $request->name;
        $staffing->save();

        $log = new Log();
        $log->user_id = auth()->id();
        $log->type    = 15;
        $log->title   = " تعديل بيانات الملاك الوظيفي ({$request->name})";
        $log->log     = " تم تعديل بيانات الملاك الوظيفي ({$request->name})";
        $log->save();

        return redirect()->route('Staffing.index')->with('success', 'تــم تـعـديـل بـيـانـات الملاك الـوظـيـفـي بــنــجــاح');
    }

    /**
     * حذف ملاك وظيفي بشرط عدم وجود وحدات مرتبطة به.
     */
    public function destroy($id)
    {
        $staffing = Staffing::withCount('unitStaffings')->findOrFail($id);

        if ($staffing->unit_staffings_count > 0) {
            return back()->with('error', 'لا يمكن حذف الملاك لوجود وحدات وظيفية مرتبطة به.');
        }

        DB::transaction(function () use ($staffing) {
            $name = $staffing->name;
            $staffing->delete();

            $log = new Log();
            $log->user_id = auth()->id();
            $log->type    = 16;
            $log->title   = " حذف ملاك وظيفي ({$name})";
            $log->log     = " تم حذف ملاك وظيفي ({$name})";
            $log->save();
        });

        return back()->with('success', 'تم حذف الملاك الوظيفي بنجاح.');
    }

    /**
     * البحث بالاسم (اختياري إن كان لديك فورم بحث).
     */
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        $all = Staffing::query()
            ->when($q !== '', fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->withCount([
                'unitStaffings as units_count',
                'employees as employees_count',
            ])
            ->latest('id')
            ->paginate(25)
            ->appends(['q' => $q]);

        return view('admin.Staffing.index', compact('all', 'q'));
    }
}
