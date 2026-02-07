<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\File;
use App\Models\Person;
use App\Models\Employee;
use App\Models\Bonus;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:feedback-list', ['only' => ['index','show','show1','search']]);
         $this->middleware('permission:feedback-create', ['only' => ['create','createAll','store']]);
         $this->middleware('permission:feedback-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:feedback-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $feed = Feedback::with('emp','user')->paginate(25);
        return view('admin.Feedback.index',compact('feed'));
    }

    public function create($id)
    {
        $emp=Employee::find($id);
        return view('admin.Feedback.create',compact('emp'));
    }

    public function createAll()
    {
        $employees = Employee::where('startout_data', NULL)->get();
        return view('admin.Feedback.createAll',compact('employees'));
    }

    public function store(Request $request, $id = null)
    {
        $empId = $id ?: $request->input('emp_id');

        request()->validate(
            [
                'text_grade'  => "required|string",
                'grade'  => "required|numeric",
                'year' => [
                    'required',
                    'numeric',
                    'integer',
                    Rule::unique('feedback')->where(function ($query) use($empId) {
                        return $query->where('emp_id', $empId);
                    }) // Unique validation for year with emp_id
                ],
            ],
            [
                'text_grade.required' => 'يجب إدخال  تقرير الكفاية اللفظي',
                'grade.required' => 'يجب إدخال درجة تقرير الكفاية ',
                'year.required' => 'يجب إدخال سنة تقرير الكفاية ',
                'year.integer' => 'يجب إدخال سنة تقرير الكفاية بالارقام ',
                'year.numeric' => 'يجب إدخال سنة تقرير الكفاية بالارقام ',
                'year.unique' => 'التقرير لهذا الموظف في هذه السنة موجود بالفعل',
            ]);

    DB::beginTransaction();
        try {
            if(Feedback::where([['emp_id', $id],['year', request('year')]])->exists()){
                DB::rollback();
                return redirect()->back()->with('error', 'للآسـف لـقـد تـم إدخـال تقرير الكفاية الـمـوظـف لـهـذة الـسـنـة');
            }
            if($id == 0){
                $emp = Employee::find(request('emp_id'));
            }else{
                $emp = Employee::find($id);
            }
            $feed = new Feedback;
            $feed->emp_id = $emp->id;
            $feed->user_id=Auth()->user()->id;
            $feed->text_grade=request('text_grade');
            $feed->grade11 = request('grade11');
            $feed->grade12 = request('grade12');
            $feed->textGrade1 = request('grade13');
            $feed->grade21 = request('grade21');
            $feed->grade22 = request('grade22');
            $feed->textGrade1 = request('grade23');
            $feed->grade31 = request('grade31');
            $feed->grade32 = request('grade32');
            $feed->textGrade1 = request('grade33');
            $feed->grade41 = request('grade41');
            $feed->grade42 = request('grade42');
            $feed->textGrade1 = request('grade43');
            $feed->grade=request('grade');
            $feed->year=request('year');
            $feed->save();

            // تعبئة تقدير العلاوات الفارغة لنفس السنة (لو كان التقدير غير موجود وقت إنشاء العلاوة)
            $estimate = $this->extractTextEstimateFromFeedback($feed);
            $year     = $this->resolveFeedbackYear($feed);
            $this->backfillBonusEstimates((int)$feed->emp_id, $year, $estimate);

            if (request()->hasFile('files')) {
                $files = request()->file('files'); // Get the files
                $finalArray = [];
                foreach ($files as $file) { // Use foreach for simpler syntax
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('FEEDBACK', $fileName, 'public'); // Store the file
                    $filePath = 'FEEDBACK/' . $fileName; // Generate the file path
                    $finalArray[] = [
                        'type' => 'feedback',
                        'procedure_id' => $feed->id,
                        'path' => $filePath,
                    ];
                }
                if (!empty($finalArray)) {
                    File::insert($finalArray); // Insert the file references
                }
            }


            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 5;
            $log->emp_id = $emp->id;
            $log->title = " اضافة تقرير الكفاية جديد (".request('year').")";
            $log->log = " تمت إضافة تقرير الكفاية جديد (".request('year').")";
            $log->save();

            DB::commit();
            return redirect()->route('feedback.index')->with('success','تــمــت إضــافــة تقرير الكفاية  بــنــجــاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    public function show($id)
    {
        $emp=Employee::find($id);
        $feed = Feedback::where('emp_id', $id)->with('emp','user')->paginate(25);
        return view('admin.Feedback.show',compact('emp','feed'));
    }

    public function edit($id)
    {
        $feed = Feedback::with('emp','user')->find($id);
        return view('admin.Feedback.edit',compact('feed'));
    }

    public function update(Request $request, $id)
    {

        request()->validate(
            [
                'text_grade'  => "required|string",
                'grade'  => "required|numeric",
                'year' => [
                    'required',
                    'numeric',
                    'integer'
                ],
            ],
            [
                'text_grade.required' => 'يجب إدخال  تقرير الكفاية اللفظي',
                'grade.required' => 'يجب إدخال درجة تقرير الكفاية ',
                'year.required' => 'يجب إدخال سنة تقرير الكفاية ',
                'year.integer' => 'يجب إدخال سنة تقرير الكفاية بالارقام ',
                'year.numeric' => 'يجب إدخال سنة تقرير الكفاية بالارقام ',
            ]);


        DB::beginTransaction();
        try {
            $feed=Feedback::find($id);
            if($feed->year != request('year') && Feedback::where([['emp_id', $id],['year', request('year')]])->exists()){
                DB::rollback();
                return redirect()->back()->with('error', 'للآسـف لـقـد تـم إدخـال تقرير الكفاية الـمـوظـف لـهـذة الـسـنـة');
            }
            $feed->text_grade=request('text_grade');
            $feed->grade=request('grade');
            $feed->year=request('year');
            $feed->grade11 = request('grade11');
            $feed->grade12 = request('grade12');
            $feed->textGrade1 = request('grade13');
            $feed->grade21 = request('grade21');
            $feed->grade22 = request('grade22');
            $feed->textGrade1 = request('grade23');
            $feed->grade31 = request('grade31');
            $feed->grade32 = request('grade32');
            $feed->textGrade1 = request('grade33');
            $feed->grade41 = request('grade41');
            $feed->grade42 = request('grade42');
            $feed->textGrade1 = request('grade43');
            $feed->update();

            // تحديث تقدير العلاوات الفارغة لنفس السنة (لو أُضيف/تغيّر تقرير الكفاية لاحقًا)
            $estimate = $this->extractTextEstimateFromFeedback($feed);
            $year     = $this->resolveFeedbackYear($feed);
            $this->backfillBonusEstimates((int)$feed->emp_id, $year, $estimate);

            if (request()->hasFile('files')) {
                $files = File::where('procedure_id', $feed->id)
                ->where('type', 'feedback')
                ->get();
                foreach ($files as $file) {
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                }
                File::where('procedure_id', $feed->id)
                    ->where('type', 'feedback')
                    ->delete();
                $files = request()->file('files'); // Get the files
                $finalArray = [];
                foreach ($files as $file) { // Use foreach for simpler syntax
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('FEEDBACK', $fileName, 'public'); // Store the file
                    $filePath = 'FEEDBACK/' . $fileName; // Generate the file path
                    $finalArray[] = [
                        'type' => 'feedback',
                        'procedure_id' => $feed->id,
                        'path' => $filePath,
                    ];
                }
                if (!empty($finalArray)) {
                    File::insert($finalArray); // Insert the file references
                }
            }

            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 5;
            $log->emp_id = $feed->emp_id;
            $log->title = " تعديل بيانات التقرير الكفاية (".request('year').")";
            $log->log = " تم تعديل بيانات التقرير الكفاية (".request('year').")";
            $log->save();

            DB::commit();
            return redirect()->route('feedback.index')->with('success','تــمــت تــعــديــل بـيـانــات التقرير الكفاية  بــنــجــاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

       public function show1(Request $request)
       {
        if (!empty($request)) {
            $query = $request->all();
        }
        if (!request('name')) {
            return redirect()->back()->with('error', 'الرجاء تعبئة احدي الخانات علي الاقل');
        }
        $persons = Person::where('name', 'LIKE', '%' . request('name') . '%')->pluck('id');
        $emps = Employee::whereIn('person_id', $persons->toArray())->pluck('id');
        $feed = Feedback::whereIn('emp_id', $emps->toArray())->with('emp')->latest()->paginate(25);
        if($feed->count() == 0){
            return redirect()->back()->with('error', 'الـمـوظـف الــذي تبــحث عنــها غيــر موجــود');
        }
        return view('admin.feedback.index',compact('feed','query'));

     }



    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $files = File::where('procedure_id', $id)
            ->where('type', 'feedback')
            ->update(['delete_at', now()]);
            $abs = Feedback::find($id);
            $abs->delete_at = now();
            $abs->update();
            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 11;
            $log->emp_id = $abs->emp_id;
            $log->title = " حذف بيانات التقرير الكفاية (".$abs->year.")";
            $log->log = " تم حذف بيانات التقرير الكفاية (".$abs->year.")";
            $log->save();
            DB::commit();
            return redirect()->back()->with('success','تـم حـذفـ التقرير الكفاية الـمـوظـف بـنـجـاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }


    // استنتاج نص التقدير من تقرير الكفاية.
    private function extractTextEstimateFromFeedback($fb): ?string
    {
        foreach (['text_grade', 'textGrade', 'grade_text'] as $c) {
            if (!empty($fb->{$c})) return (string) $fb->{$c};
        }
        if (isset($fb->grade) && is_numeric($fb->grade)) {
            $g = (int) $fb->grade;
            if    ($g >= 90) return 'ممتاز';
            elseif($g >= 80) return 'جيد جدًا';
            elseif($g >= 70) return 'جيد';
            elseif($g >= 60) return 'مقبول';
            else             return 'ضعيف';
        }
        return null;
    }


    // تعبئة estimate للعلاوات الفارغة لنفس الموظف وفي نفس السنة.
    private function backfillBonusEstimates(int $empId, ?int $year, ?string $estimate): void
    {
        if (!$estimate || !$year) return;

        Bonus::where('emp_id', $empId)
            ->whereNull('estimate')
            // السنة حسب تاريخ الاستحقاق (date) أو تاريخ العلاوة (bonus_date) أو تاريخ الإنشاء
            ->whereRaw('YEAR(COALESCE(`date`, `bonus_date`, `created_at`)) = ?', [$year])
            ->update(['estimate' => $estimate]);
    }


    // استنتاج سنة التقرير.
    private function resolveFeedbackYear($fb): ?int
    {
        if (!empty($fb->year)) return (int) $fb->year;

        // لو عندك حقل تاريخ للتقرير غيّر اسمه هنا
        foreach (['report_date', 'evaluation_date', 'date'] as $dcol) {
            if (!empty($fb->{$dcol})) {
                try { return Carbon::parse($fb->{$dcol})->year; } catch (\Throwable $e) {}
            }
        }
        return optional($fb->created_at)->year;
    }


}
