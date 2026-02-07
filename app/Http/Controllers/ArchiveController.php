<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\File;
use App\Models\Archive;
use App\Models\Employee;
use App\Models\subSection;
use App\Models\ArchiveType;
use App\Models\ArchiveFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchiveController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
          $this->middleware('permission:archive-list', ['only' => ['index','show','search']]);
          $this->middleware('permission:archive-create', ['only' => ['create','store']]);
          $this->middleware('permission:archive-edit', ['only' => ['edit','update']]);
          $this->middleware('permission:archive-delete', ['only' => ['destroy']]);
     }

    public function index($id)
    {
        if($id == 0){
            $page = $id;
            $archives = Archive::with('emp','type')->paginate(25);
        }else{
            $page = $id;
            $archives = Archive::where('type_id', $id)->with('emp','type')->paginate(25);
        }
        $employees = Employee::get();
        $types = ArchiveType::get();
        return view('admin.Archive.index',['page'=>$page,'archives'=>$archives,'types'=>$types,'employees'=>$employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!empty($request)){
            $query = $request->all();
        }
        if(!request('search')){
            return redirect()->route('archives.index',['id'=>0]);
        }
        if(request('type') == 0){
            $page = request('type');
            $archives = Archive::where('desc', 'LIKE', '%' . request('search') . '%')
            ->orWhere('name', 'LIKE', '%' . request('search') . '%')->with('emp','type')->latest()->paginate(25);
        }else{
            $page = request('type');
            $archives = Archive::where([['type_id', request('type')],['desc', 'LIKE', '%' . request('search') . '%']])->
            orWhere([['type_id', request('type')],['name', 'LIKE', '%' . request('search') . '%']])->with('emp','type')->latest()->paginate(25);
        }
        if($archives->count() == 0){
            return redirect()->back()->with('error','عذرا الإرشيف الذي تبحث عنه غير موجود');
        }
        $types = ArchiveType::get();
        $employees = Employee::get();
        return view('admin.Archive.index',['page'=>$page,'archives'=>$archives,'query'=>$query,'types'=>$types,'employees'=>$employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'entity' => 'required',
            'type' => 'required',
            'emp_id' => 'required',
        ],
        [
            'entity.required' => 'يجب ادخال الجهة التابعة',
            'type.required' => 'يجب أختيار نوع الأرشيف',
            'emp_id.required' => 'يجب أختيار الموظف',
        ]);

        DB::beginTransaction();
        try {
            $arr = new Archive;
            $arr->name = request('entity');
            $arr->type_id =request('type');
            $arr->emp_id =request('emp_id');
            $arr->desc =request('desc');

            if(request('date')){
                $date = request('date');
                $date = str_replace('/', '-', $date);
                $formatted_date = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
                $arr->date = $formatted_date;
            } else {
                $arr->date = now()->format('Y-m-d');
            }

            $arr->save();

            if (request()->hasFile('files')) {
                $files = request()->file('files'); // Get the files
                $finalArray = [];
                foreach ($files as $file) { // Use foreach for simpler syntax
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('ARCHIVE', $fileName, 'public'); // Store the file
                    $filePath = 'ARCHIVE/' . $fileName; // Generate the file path
                    $finalArray[] = [
                        'type' => 'archive',
                        'procedure_id' => $arr->id,
                        'path' => $filePath,
                    ];
                }
                if (!empty($finalArray)) {
                    File::insert($finalArray); // Insert the file references
                }
            }

            DB::commit();
            return redirect()->back()->with('success','تمت إضافة أرشيف جديد بنجاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error','للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$type)
    {
        if($type == 0){
            $page = $type;
            $archives = Archive::where('emp_id', $id)->with('emp','type')->latest()->paginate(25);
        }else{
            $page = $type;
            $archives = Archive::where([['type_id', $type],['emp_id', $id]])->with('emp','type')->latest()->paginate(25);
        }
        $emp = Employee::find($id);
        $employees = Employee::get();
        $types = ArchiveType::get();
        return view('admin.Archive.show',['page'=>$page,'emp'=>$emp,'archives'=>$archives,'types'=>$types,'employees'=>$employees]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate([
            'entity' => 'required',
            'type' => 'required',
            'emp_id' => 'required',
        ],
        [
            'entity.required' => 'يجب ادخال الجهة التابعة',
            'type.required' => 'يجب أختيار نوع الأرشيف',
            'emp_id.required' => 'يجب أختيار الموظف',
        ]);

        $arr = Archive::find(request('id'));
        $arr->name = request('entity');
        $arr->type_id =request('type');
        $arr->emp_id =request('emp_id');
        $arr->desc =request('desc');

        $date = request('date');
        $date = str_replace('/', '-', $date);
        if(request('date') && request('date') != $arr->date){
            $arr->date = request('date');
        }elseif(request('date') && request('date') != $arr->date){
            $arr->date = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        }


        if (request()->hasFile('files')) {
            $files = File::where('procedure_id', $arr->id)
            ->where('type', 'archive')
            ->get();
            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }
            File::where('procedure_id', $arr->id)
                ->where('type', 'archive')
                ->delete();
            $files = request()->file('files'); // Get the files
            $finalArray = [];
            foreach ($files as $file) { // Use foreach for simpler syntax
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('ARCHIVE', $fileName, 'public'); // Store the file
                $filePath = 'ARCHIVE/' . $fileName; // Generate the file path
                $finalArray[] = [
                    'type' => 'archive',
                    'procedure_id' => $arr->id,
                    'path' => $filePath,
                ];
            }
            if (!empty($finalArray)) {
                File::insert($finalArray); // Insert the file references
            }
        }


        $arr->update();
        return redirect()->back()->with('success','تم تعديل الأرشيف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $files = File::where('procedure_id', $id)
            ->where('type', 'archive')
            ->update(['delete_at'=> now()]);

            $abs = Archive::find($id);
            $abs->delete_at = now();
            $abs->delete();
            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 11;
            $log->emp_id = $abs->emp_id;
            $log->title = " حذف بيانات الأرشيف ";
            $log->log = " تم حذف بيانات الأرشيف ";
            $log->save();
            DB::commit();
            return redirect()->back()->with('success','تـم حـذفـ الارشـيـف بـنـجـاح');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    public function preview($id): StreamedResponse
    {
        // ⚠️ عدّل اسم الموديل حسبك. إن كان نموذج الملفات اسمه مختلف، بدّله هنا.
        // كثيرًا ما يكون: ArchiveFile أو ArchiveFiles. بما أنّك تستخدم $arr->files، فالأرجح اسمه ArchiveFile.
        $fileModelClass = \App\Models\Archive::class; // غيّرها لو الاسم مختلف
    
        $file = $fileModelClass::findOrFail($id);
    
        $path = $file->file; // حقل المسار في قاعدة البيانات
        // نتأكد من وجود الملف على قرص 'public'
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'الملف غير موجود على التخزين العام.');
        }
    
        // إرجاع الملف inline داخل المتصفح مع اسم مناسب
        return Storage::disk('public')->response($path, basename($path), [
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
}
