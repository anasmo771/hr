<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\File;
use App\Models\Course;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\CourseEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:course-list', ['only' => ['index','show','searchEmployeeCourse','search']]);
         $this->middleware('permission:course-create', ['only' => ['create','createAll','store']]);
         $this->middleware('permission:course-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:course-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with('employees.emp.person')->paginate(25);
        return view('admin.Course.index', compact('courses'));
    }

    public function searchEmployeeCourse(Request $request)
    {
        $courses = Course::with('employees.emp')->wherehas('emp', function ($qurey) {
            return $qurey->where('name', 'LIKE', '%' . request('name') . '%');
        })->orWhere('name_course', 'LIKE', '%' . request('name') . '%')->paginate(25);
        if ($courses->count() == 0) {
            return redirect()->back()->with('error', 'الـمـوظـف او الــدورة الــذي تبــحث عنــها غيــر موجــود');
        } else {
            return view('admin.Course.index', compact('courses'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $emp = Employee::find($id);
        return view('admin.Course.create', compact('emp'));
    }

    public function createAll()
    {
        $employees = Employee::where('startout_data', NULL)->get();
        return view('admin.Course.createAll',compact('employees'));
    }

    public function print(Request $request)
    {
        $courses = Course::all();
        return view('admin.Course.print', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        request()->validate(
            [
                'course_name'  => "required|string",
                'course_type'  => "required|string",
                'number'  => "required|string",
                'place'  => "required|string",
                'agency'  => "required|string",
                'from_date' => 'required|string',
                'to_date' => 'required|string',
            ],
            [
                'course_name.required' => 'يجب إدخال اسم الدورة',
                'course_type.required' => 'الرجاء اختيار نوع الدورة',
                'number.required' => 'يجب إدخال رقم القرار',
                'place.required' => 'يجب إدخال مكان الدورة',
                'agency.required' => 'يجب إدخال الجهة المنفدة',
                'from_date.required' => 'يجب ادخال تاريخ بداية الدورة',
                'to_date.required' => 'يجب ادخال تاريخ نهاية الدورة',
            ]
        );

        DB::beginTransaction();
        try {

            if(Course::where([['name_course', request('course_name')],['from_date', request('from_date')]])->exists()){
                DB::rollback();
                return redirect()->back()->with('error', 'عذرآ ولكن هنالك دورة بنفس الاسم وبنفس التاريخ مضافة مسبقآ');
            }

            if($id == 0){
                $employee = Employee::find(request('emp_id'));
            }else{
                $employee = Employee::find($id);
            }

            $course = new Course();
            $course->name_course = request('course_name');
            $course->course_type = request('course_type');
            $course->number = request('number');
            $course->place = request('place');
            $course->agency = request('agency');

            $from_date = request('from_date');
            $from_date = str_replace('/', '-', $from_date);
            $formatted_date = Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
            $course->from_date = $formatted_date;

            $to_date = request('to_date');
            $to_date = str_replace('/', '-', $to_date);
            $formatted_date = Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
            $course->to_date = $formatted_date;

            $course->created_id = auth()->user()->id;
            $course->notes = request('notes');
            $course->save();

            $employees = request('emp_id');
            $results = request('result');
            $notes = request('note');
            $finalArray = array();

            if($employees){
                $uniqueArray = []; // To store emp_id as keys
                for ($i=0; $i < count($employees); $i++) {
                    if (!isset($uniqueArray[$employees[$i]])) {
                        $uniqueArray[$employees[$i]] = true;
                        array_push($finalArray, array(
                            'emp_id'=>$employees[$i],
                            'course_id'=>$course->id,
                            'result'=>$results[$i],
                            'notes'=>$notes[$i]
                            )
                        );
                    }
                }
            }

            if($employees){
                CourseEmployee::insert($finalArray);
            }

            if (request()->hasFile('files')) {
                $files = request()->file('files'); // Get the files
                $finalArray = [];
                foreach ($files as $file) { // Use foreach for simpler syntax
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('COURSE', $fileName, 'public'); // Store the file
                    $filePath = 'COURSE/' . $fileName; // Generate the file path
                    $finalArray[] = [
                        'type' => 'course',
                        'procedure_id' => $course->id,
                        'path' => $filePath,
                    ];
                }
                if (!empty($finalArray)) {
                    File::insert($finalArray); // Insert the file references
                }
            }


            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 8;
            $log->title = " اضافة دورة جديدة (".request('name_course').")";
            $log->log = " تمت إضافة دورة جديدة (".request('name_course').")";
            $log->save();

            DB::commit();
            return redirect()->route('courses.index')->with('success', 'تــمــت إضــافــة دورة بــنــجــاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emp = Employee::find($id);
        $courses = Course::whereHas('employees',function($q) use($id) {
            $q->where('emp_id', $id);
        })->with('employees.emp')->paginate(20);
        return view('admin.Course.show', compact('emp', 'courses'));
    }

    /**
     * Show the form for editing the specified resource.d
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::find($id);
        $employees = Employee::where('startout_data', NULL)->get();
        return view('admin.Course.edit', compact('course','employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate(
            [
                'course_name'  => "required|string",
                'course_type'  => "required|string",
                'number'  => "required|string",
                'place'  => "required|string",
                'agency'  => "required|string",
                'from_date' => 'required|string',
                'to_date' => 'required|string',
            ],
            [
                'course_name.required' => 'يجب إدخال اسم الدورة',
                'course_type.required' => 'الرجاء اختيار نوع الدورة',
                'number'  => "required|string",
                'place'  => "required|string",
                'agency'  => "required|string",
                'from_date.required' => 'يجب ادخال تاريخ بداية الدورة',
                'to_date.required' => 'يجب ادخال تاريخ نهاية الدورة',
            ]
        );
        DB::beginTransaction();
        try {
            $course = Course::find($id);
            $course->name_course = request('course_name');
            $course->course_type = request('course_type');
            $course->number = request('number');
            $course->place = request('place');
            $course->agency = request('agency');


            $from_date = request('from_date');
            $from_date = str_replace('/', '-', $from_date);
            if(request('from_date') && request('from_date') != $course->from_date){
                $course->from_date = request('from_date');
            }elseif(request('from_date') && request('from_date') != $course->from_date){
                $course->from_date = Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
            }

            $to_date = request('to_date');
            $to_date = str_replace('/', '-', $to_date);
            if(request('to_date') && request('to_date') != $course->to_date){
                $course->to_date = request('to_date');
            }elseif(request('to_date') && request('to_date') != $course->to_date){
                $course->to_date = Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
            }

            $course->notes = request('notes');
            $course->save();

            $employees = request('emp_id');
            $results = request('result');
            $notes = request('note');
            $finalArray = array();

            if($employees){
                $uniqueArray = []; // To store emp_id as keys
                for ($i=0; $i < count($employees); $i++) {
                    if (!isset($uniqueArray[$employees[$i]])) {
                        $uniqueArray[$employees[$i]] = true;
                        array_push($finalArray, array(
                            'emp_id'=>$employees[$i],
                            'course_id'=>$course->id,
                            'result'=>$results[$i],
                            'notes'=>$notes[$i]
                            )
                        );
                    }
                }
            }

            CourseEmployee::where('course_id', $id)->delete();
            if($employees){
                CourseEmployee::insert($finalArray);
            }


            if (request()->hasFile('files')) {
                $files = File::where('procedure_id', $course->id)
                ->where('type', 'course')
                ->get();
                foreach ($files as $file) {
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                }
                File::where('procedure_id', $course->id)
                    ->where('type', 'course')
                    ->delete();
                $files = request()->file('files'); // Get the files
                $finalArray = [];
                foreach ($files as $file) { // Use foreach for simpler syntax
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('COURSE', $fileName, 'public'); // Store the file
                    $filePath = 'COURSE/' . $fileName; // Generate the file path
                    $finalArray[] = [
                        'type' => 'course',
                        'procedure_id' => $course->id,
                        'path' => $filePath,
                    ];
                }
                if (!empty($finalArray)) {
                    File::insert($finalArray); // Insert the file references
                }
            }

            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 8;
            $log->emp_id = $course->emp_id;
            $log->title = " تعديل بيانات الدورة (".request('name_course').")";
            $log->log = " تم تعديل بيانات الدورة (".request('name_course').")";
            $log->save();

            DB::commit();
            return redirect()->route('courses.index')->with('success', ' تــمــت تعديل دورة بنجاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }


    public function destroy($id)
    {
    DB::beginTransaction();
    try {
        $files = File::where('procedure_id', $id)
        ->where('type', 'course')
        ->update(['delete_at', now()]);
        $abs = Course::find($id);
        $abs->delete_at = now();
        $abs->update();
        $log = new Log;
        $log->user_id = auth()->user()->id;
        $log->type = 11;
        $log->emp_id = $abs->emp_id;
        $log->title = " حذف بيانات الـدورة (".$abs->name_course.")";
        $log->log = " تم حذف بيانات الـدورة (".$abs->name_course.")";
        $log->save();
        DB::commit();
        return redirect()->back()->with('success','تـم حـذفـ الـدورة الـمـوظـف بـنـجـاح');
        // all good
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
    }

}

}
