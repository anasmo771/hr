<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\File;
use App\Models\Course;
use App\Models\Person;
use App\Models\Employee;
use App\Models\Staffing;
use App\Models\Specialty;
use App\Models\Ndb_Detail;
use App\Models\Punishment;
use App\Models\subSection;
use App\Models\UnitStaffing;
use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;

        $this->middleware('permission:employee-list',  ['only' => ['index','show','EmployeeDetails']]);
        $this->middleware('permission:employee-create',['only' => ['create','store']]);
        $this->middleware('permission:employee-edit',  ['only' => ['edit','update']]);
        $this->middleware('permission:employee-delete',['only' => ['destroy']]);

        $this->middleware('permission:course-list',   ['only' => ['showEmployeeCourse','searchEmployeeCourse']]);
        $this->middleware('permission:course-create', ['only' => ['addCourse','storeCourse']]);
        $this->middleware('permission:course-edit',   ['only' => ['editCourse','updateCourse']]);
        $this->middleware('permission:course-delete', ['only' => ['deleteCourse']]);

        $this->middleware('permission:punishment-list',   ['only' => ['showEmployeePunsh','searchEmployeePunshe']]);
        $this->middleware('permission:punishment-create', ['only' => ['addPunshes','storePunsh']]);
        $this->middleware('permission:punishment-delete', ['only' => ['deletePunsh']]);
    }

    /** فحص أن المقعد المختار فيه مقعد شاغر */
    private function assertUnitHasFreeSeat(int $unitStaffingId, ?int $ignoreEmployeeId = null): void
    {
        $unit = UnitStaffing::lockForUpdate()->findOrFail($unitStaffingId);

        $q = Employee::where('unit_staffing_id', $unitStaffingId)
                     ->whereNull('startout_data');

        if ($ignoreEmployeeId) {
            $q->where('id', '!=', $ignoreEmployeeId);
        }

        $occupied = $q->count();
        $quota    = (int)$unit->quota;

        if ($occupied >= $quota) {
            throw ValidationException::withMessages([
                'unit_staffing_id' => 'المقعد المختار ممتلئ، الرجاء اختيار وحدة/مقعد آخر.'
            ]);
        }
    }

    public function index()
    {
        $employees = Employee::with([
                'person',
                'subSection',
                'user',
                'ndb',
                'unitStaffing.staffing',
            ])
            ->whereNull('startout_data')
            ->latest('id')
            ->get();

        $subsection1 = subSection::all();

        return view('admin.Employee.index', compact('employees', 'subsection1'));
    }

    public function create()
    {
        $sub         = subSection::whereNull('parent_id')->with('sub')->get();
        $staffing    = Staffing::get();
        $Specialties = Specialty::get();

        return view('admin.Employee.create', compact('sub', 'Specialties', 'staffing'));
    }

    public function print(Request $request)
    {
        $query = $request->all();
        if (!$request->filled('type')) {
            return redirect()->route('employees.index')->with('error', 'الرجاء تحديد حالة الموظفيين');
        }

        if(request('type') == "الاجانب"){
            $employees = Employee::whereHas('person',function($q) {
                $q->whereNotNull('non_citizen_ref_no');
            })->with('person')->get();
        }elseif(request('type') == "المستقلين"){
            $employees = Employee::where('status', 'مستقيل')->with('person')->get();
        }elseif(request('type') == "عقود"){
            $employees = Employee::where('type', 'عقد')->with('person')->get();
        }elseif(request('type') == "تعيينات"){
            $employees = Employee::where('type', 'تعيين')->with('person')->get();
        }elseif(request('type') == "إعارة"){
            $employees = Employee::where('type', 'إعارة')->with('person')->get();
        }elseif(request('type') == "ندب"){
            $employees = Employee::where('type', 'ندب')->with('person')->get();
        }elseif(request('type') == "المنقطعين"){
            $employees = Employee::where('status', 'منقطع')->with('person')->get();
        }else{
            $employees = Employee::with('person')->get();
        }

        if($employees->count() == 0){
            return redirect()->route('employees.index')->with('error', 'لا يوجد اي سجلات بالبيانات المختارة');
        }

        $page = request('type');
        return view('admin.Employee.print', compact('employees','page'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $request->validate([
            'section_id'       => ['required','exists:sub_sections,id'],
            'unit_staffing_id' => ['nullable','exists:unit_staffings,id'],
            'type'             => ['required','in:عقد,تعيين,إعارة,ندب'],
            'status'           => ['required','in:يعمل,مفصول,مستقيل,متقاعد,موقوف,منقطع,منتقل'],
            'degree'           => ['required','integer','min:1','max:15'],
            'start_date'       => ['required','string'],
        ]);

        DB::beginTransaction();
        try {
            if ($request->filled('unit_staffing_id')) {
                $this->assertUnitHasFreeSeat((int)$request->unit_staffing_id);
            }

            // ===== Person =====
            $person = new Person();
            $person->name               = $request->name;
            $person->N_id               = $request->N_id;
            $person->non_citizen_ref_no = $request->non_citizen_ref_no;
            $person->email              = $request->email;
            $person->phone              = $request->phone;

            $birth = str_replace('/', '-', $request->birth_date);
            $person->birth_date         = Carbon::createFromFormat('d-m-Y', $birth)->format('Y-m-d');

            $person->country         = $request->country;
            $person->city            = $request->city;
            $person->street_address  = $request->street_address;
            $person->gender          = $request->gender;
            $person->marital_status  = $request->marital_status;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file     = $request->file('image');
                $fileName = time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('public/Employees', $fileName);
                $person->image = 'Employees/'.$fileName;
            }
            $person->save();

            // ===== Employee =====
            $emp = new Employee();
            $emp->person_id        = $person->id;
            $emp->degree           = $request->degree;
            $emp->type             = $request->type;
            $emp->qualification    = $request->qualification;
            $emp->specialty_id     = $request->specialty_id;
            $emp->res_num          = $request->res_num;
            $emp->status           = $request->status;
            $emp->created_id       = auth()->id();
            $emp->unit_staffing_id = $request->unit_staffing_id;

            $emp->start_date = Carbon::createFromFormat('d-m-Y', str_replace('/','-',$request->start_date))->format('Y-m-d');

            foreach (['degree_date','futurepromotion','futureBonus','due'] as $d) {
                if ($request->filled($d)) {
                    $emp->{$d} = Carbon::createFromFormat('d-m-Y', str_replace('/','-',$request->{$d}))->format('Y-m-d');
                }
            }

            // حفظ sub_section_id (إن كان المختار ابن لقسم)
            $selected = subSection::find($request->section_id);
            $emp->sub_section_id = ($selected && $selected->parent_id) ? $selected->id : null;

            $emp->save();

            // ===== ندب/إعارة (حسب حاجتك) =====
            if(in_array($request->type, ['ندب','إعارة'])) {
                if(!Ndb_Detail::where([
                    ['emp_id', $emp->id],
                    ['ndb_transfer_decision', $request->ndb_transfer_decision]
                ])->exists()){
                    $ndb = new Ndb_Detail;
                    $ndb->emp_id = $emp->id;
                    $ndb->ndb_transfer_decision = $request->ndb_transfer_decision;

                    if ($request->filled('ndb_start')) {
                        $ndb->ndb_start = Carbon::createFromFormat('d-m-Y', str_replace('/','-',$request->ndb_start))->format('Y-m-d');
                    }
                    if ($request->filled('ndb_end')) {
                        $ndb->ndb_end   = Carbon::createFromFormat('d-m-Y', str_replace('/','-',$request->ndb_end))->format('Y-m-d');
                    }

                    $ndb->dec_source     = $request->dec_source;
                    $ndb->dec_constraints= $request->dec_constraints;
                    $ndb->ndb_workplace  = $request->ndb_workplace;
                    $ndb->is_ndb         = ($request->type === 'ندب');
                    $ndb->save();

                    if ($request->hasFile('files')) {
                        $final = [];
                        foreach ($request->file('files') as $file) {
                            $fileName = time().'.'.$file->getClientOriginalExtension();
                            $file->storeAs('NDB', $fileName, 'public');
                            $final[] = [
                                'type'         => 'ndb',
                                'procedure_id' => $ndb->id,
                                'path'         => 'NDB/'.$fileName,
                            ];
                        }
                        if ($final) File::insert($final);
                    }
                }
            }

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 1,
                'emp_id'  => $emp->id,
                'title'   => " اضافة موظف جديد ({$request->name})",
                'log'     => " تمت إضافة موظف جديد ({$request->name})",
            ]);

            DB::commit();
            return back()->with('success', 'تــمــت إضــافــة مــوظــف بــنــجــاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    public function show(Request $request)
    {
        if (!empty($request)) { $query = $request->all(); }

        // بناء شروط البحث في people
        $peopleConds = [];
        if ($request->filled('name')) {
            if ($request->type == "name")      $peopleConds[] = ['name', 'LIKE', '%'.$request->name.'%'];
            elseif ($request->type == "id")    $peopleConds[] = ['N_id', 'LIKE', '%'.$request->name.'%'];
        }

        $empQuery = Employee::query();

        if ($peopleConds) {
            $empQuery->whereHas('person', function($q) use ($peopleConds) {
                $q->where($peopleConds);
            });
        }

        if ($request->type == "degree" && $request->filled('name')) {
            $empQuery->where('degree', 'LIKE', '%'.$request->name.'%');
        }

        if ($request->type == "res_num" && $request->filled('name')) {
            $empQuery->where('res_num', 'LIKE', '%'.$request->name.'%');
        }

        // فلترة القسم/الإدارة عبر sub_section_id:
        if ($request->filled('section_id') && $request->section_id != "all") {
            $sectionId = (int)$request->section_id;

            // إن كان المختار قسماً رئيسيًا: اجلب أبناءه
            $childrenIds = subSection::where('parent_id', $sectionId)->pluck('id');
            $empQuery->where(function($q) use ($sectionId, $childrenIds) {
                $q->where('sub_section_id', $sectionId)       // لو كان القسم المختار نفسه ابن
                  ->orWhereIn('sub_section_id', $childrenIds); // أو أحد أبنائه
            });
        }

        $employees = $empQuery->latest()->paginate(25);

        if ($employees->count() == 0) {
            return back()->with('error', 'الــموظــف الــذي تبــحث عنــه غيــر موجــود');
        }
        $subsection1 = subSection::get();
        return view('admin.Employee.index', compact('employees', 'subsection1', 'query'));
    }

    public function edit($id)
    {
        $emp         = Employee::findOrFail($id);
        $sub         = subSection::whereNull('parent_id')->with('sub')->get();
        $staffing    = Staffing::get();
        $Specialties = Specialty::get();

        return view('admin.Employee.edit', compact('sub', 'Specialties', 'emp', 'staffing'));
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $request->validate([
            'section_id'       => ['required','exists:sub_sections,id'],
            'unit_staffing_id' => ['nullable','exists:unit_staffings,id'],
            'type'             => ['required','in:عقد,تعيين,إعارة,ندب'],
            'status'           => ['required','in:يعمل,مفصول,مستقيل,متقاعد,موقوف,منقطع,منتقل'],
            'degree'           => ['required','integer','min:1','max:15'],
        ]);

        DB::beginTransaction();
        try {
            $emp    = Employee::lockForUpdate()->findOrFail($id);
            $person = Person::findOrFail($emp->person_id);

            // لو تغير المقعد افحص السعة
            if ($request->filled('unit_staffing_id')) {
                $newUnit = (int)$request->unit_staffing_id;
                if ($newUnit !== (int)$emp->unit_staffing_id) {
                    $this->assertUnitHasFreeSeat($newUnit, $emp->id);
                    $emp->unit_staffing_id = $newUnit;
                }
            } else {
                $emp->unit_staffing_id = null;
            }

            // ===== Person =====
            $person->name               = $request->name;
            $person->N_id               = $request->N_id;
            $person->non_citizen_ref_no = $request->non_citizen_ref_no;
            $person->email              = $request->email;
            $person->phone              = $request->phone;
            $person->country            = $request->country;
            $person->city               = $request->city;
            $person->street_address     = $request->street_address;
            $person->gender             = $request->gender;
            $person->marital_status     = $request->marital_status;

            if($request->filled('birth_date')){
                $person->birth_date = Carbon::createFromFormat('d-m-Y', str_replace('/','-',$request->birth_date))->format('Y-m-d');
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($person->image && Storage::disk('public')->exists($person->image)) {
                    Storage::disk('public')->delete($person->image);
                }
                $file     = $request->file('image');
                $fileName = time().'.'.$file->getClientOriginalExtension();
                $file->storeAs('public/Employees', $fileName);
                $person->image = 'Employees/'.$fileName;
            }
            $person->save();

            // منع تكرار الرقم الوطني
            if($person->N_id != $request->N_id && Person::where('N_id',$request->N_id)->exists()){
                DB::rollBack();
                return back()->with('error', 'الرقم الوطني الجديد الذي قمت بإدخاله موجود مسبقآ');
            }

            // ===== Employee =====
            $emp->degree        = $request->degree;
            $emp->type          = $request->type;
            $emp->qualification = $request->qualification;
            $emp->res_num       = $request->res_num;
            $emp->specialty_id  = $request->specialty_id;
            $emp->status        = $request->status;
            $emp->created_id    = auth()->id();

            foreach (['start_date','degree_date','futurepromotion','futureBonus','due'] as $d) {
                if ($request->filled($d)) {
                    $emp->{$d} = Carbon::createFromFormat('d-m-Y', str_replace('/','-',$request->{$d}))->format('Y-m-d');
                }
            }

            $selected = subSection::find($request->section_id);
            $emp->sub_section_id = ($selected && $selected->parent_id) ? $selected->id : null;

            $emp->save();

            Log::create([
                'user_id' => auth()->id(),
                'type'    => 2,
                'emp_id'  => $emp->id,
                'title'   => " تعديل بيانات الموظف ({$request->name})",
                'log'     => " تم تعديل بيانات الموظف ({$request->name})",
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'تــمــت تــعــديــل بـيــانـات الـمــوظــف بــنــجــاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    public function EmployeeDetails($id)
    {
        $emp     = Employee::find($id);
        $sub     = subSection::get();
        $courses = Course::whereHas('employees',function($q) use($id) {
                        $q->where('emp_id', $id);
                    })->with('employees.emp')->paginate(20);
        $punshes = Punishment::where('emp_id', $id)->paginate(20);

        return view('admin.Employee.show', compact('emp', 'sub', 'courses', 'punshes'));
    }

    public function showEmployeeCourse($id)
    {
        $emp     = Employee::find($id);
        $courses = Course::whereHas('employees',function($q) use($id) {
                        $q->where('emp_id', $id);
                    })->with('employees.emp')->paginate(20);
        return view('admin.showEmployeeCourse', compact('emp', 'courses'));
    }

    public function showEmployeePunsh($id)
    {
        $emp     = Employee::find($id);
        $punshes = Punishment::where('emp_id', $id)->paginate(20);
        return view('admin.showEmployeePunsh', compact('emp', 'punshes'));
    }

    public function addCourse($id)
    {
        $emp = Employee::find($id);
        return view('admin.addCourse', compact('emp'));
    }

    public function addPunshes($id)
    {
        $emp = Employee::find($id);
        return view('admin.addPunshes', compact('emp'));
    }

    public function deleteCourse($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->delete();
            return back()->with('success', 'تم حذف الدورة بنجاح');
        }
        return back()->with('error', 'الدورة لم تعد موجودة بعد الان');
    }

    public function deletePunsh($id)
    {
        $punsh = Punishment::find($id);
        if ($punsh) {
            $punsh->delete();
            return back()->with('success', 'تم حذف العقوبة بنجاح');
        }
        return back()->with('error', 'العقوبة لم تعد موجودة بعد الان');
    }

    public function editCourse($id)
    {
        $course = Course::find($id);
        return view('admin.editCourse', compact('course'));
    }

    public function updateCourse($id)
    {
        request()->validate(
            [
                'course_name' => "required|string",
                'from_date'   => 'required|string',
                'to_date'     => 'required|string',
                'result'      => "required|string",
            ],
            [
                'course_name.required' => 'يجب إدخال اسم الدورة',
                'from_date.required'   => 'يجب ادخال تاريخ بداية الدورة',
                'to_date.required'     => 'يجب ادخال تاريخ نهاية الدورة',
                'result.required'      => 'يجب ادخال نهاية الدورة',
            ]
        );

        $course = Course::find($id);
        $course->name_course = request('course_name');
        $course->from_date   = request('from_date');
        $course->to_date     = request('to_date');
        $course->result      = request('result');
        $course->notes       = request('notes');
        $course->save();

        Log::create([
            'user_id' => auth()->id(),
            'type'    => 8,
            'emp_id'  => $course->emp_id,
            'title'   => " تعديل بيانات الدورة (".request('course_name').")",
            'log'     => " تمت تعديل بيانات الدورة (".request('course_name').")",
        ]);

        return back()->with('success', ' تــمــت تعديل دورة بنجاح');
    }

    public function storeCourse(Request $request, $id)
    {
        request()->validate(
            [
                'course_name' => "required|string",
                'from_date'   => 'required|string',
                'to_date'     => 'required|string',
                'result'      => "required|string",
            ],
            [
                'course_name.required' => 'يجب إدخال اسم الدورة',
                'from_date.required'   => 'يجب ادخال تاريخ بداية الدورة',
                'to_date.required'     => 'يجب ادخال تاريخ نهاية الدورة',
                'result.required'      => 'يجب ادخال نهاية الدورة',
            ]
        );

        $course              = new Course();
        $course->emp_id      = $id;
        $course->name_course = request('course_name');
        $course->from_date   = request('from_date');
        $course->to_date     = request('to_date');
        $course->result      = request('result');
        $course->created_id  = auth()->id();
        $course->notes       = request('notes');
        $course->save();

        Log::create([
            'user_id' => auth()->id(),
            'type'    => 8,
            'emp_id'  => $id,
            'title'   => " اضافة دورة جديدة (".request('course_name').")",
            'log'     => " تمت إضافة دورة جديدة (".request('course_name').")",
        ]);

        return back()->with('success', 'تــمــت إضــافــة دورة للـمـوظــف بــنــجــاح');
    }

    public function storePunsh(Request $request, $id)
    {
        request()->validate(
            [
                'pun_type'    => "required|string",
                'pun_date'    => 'required|string',
                'book_num'    => 'required|string',
                'penaltyName' => "required|string",
            ],
            [
                'pun_type.required'    => 'يجب إدخال نوع العقوبة',
                'pun_date.required'    => 'يجب ادخال تاريخ العقوبة',
                'book_num.required'    => 'يجب ادخال رقم الكتاب',
                'penaltyName.required' => 'يجب ادخال اسم من اوصي بالعقوبة',
            ]
        );

        $pun              = new Punishment();
        $pun->emp_id      = $id;
        $pun->pun_type    = request('pun_type');
        $pun->pun_date    = request('pun_date');
        $pun->book_num    = request('book_num');
        $pun->penaltyName = request('penaltyName');
        $pun->created_id  = auth()->id();
        $pun->notes       = request('notes');
        $pun->save();

        Log::create([
            'user_id' => auth()->id(),
            'type'    => 9,
            'emp_id'  => $id,
            'title'   => " اضافة عقوبة جديدة (".request('pun_type').")",
            'log'     => " تمت إضافة عقوبة جديدة (".request('pun_type').")",
        ]);

        return redirect()->route('showEmployeePunsh', ['id' => $id])
               ->with('success', 'تــمــت إضــافــة عـقـوبـة للـمـوظــف بــنــجــاح');
    }

    public function searchEmployeePunshe(Request $request)
    {
        $punshes = Punishment::with('emp')->wherehas('emp', function ($qurey) {
                        return $qurey->where('name', 'LIKE', '%' . request('name') . '%');
                    })->latest()->paginate(20);

        if ($punshes->count() == 0) {
            return back()->with('error', 'الـمـوظـف الــذي تبــحث عنــها غيــر موجــود');
        }
        return redirect()->route('showPunches', compact('punshes'));
    }

    public function searchEmployeeCourse(Request $request)
    {
        $courses = Course::with('employees.emp')
                    ->wherehas('emp', function ($qurey) {
                        return $qurey->where('name', 'LIKE', '%' . request('name') . '%');
                    })
                    ->orWhere('name_course', 'LIKE', '%' . request('name') . '%')
                    ->latest()->paginate(20);

        if ($courses->count() == 0) {
            return back()->with('error', 'الـمـوظـف او الــدورة الــذي تبــحث عنــها غيــر موجــود');
        }
        return redirect()->route('showCources', compact('courses'));
    }

    public function destroy($id)
    {
        //
    }
}
