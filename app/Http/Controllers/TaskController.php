<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Task;
use App\Models\File as systmFiles;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:task-list', ['only' => ['index','show','search']]);
        $this->middleware('permission:task-create', ['only' => ['create','createAll','store']]);
        $this->middleware('permission:task-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:task-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $tasks = Task::paginate(25);
        return view('admin.Task.index',compact('tasks'));
    }

    public function create($id)
    {
        $emp = Employee::find($id);
        return view('admin.Task.create',compact('emp'));
    }

    public function createAll()
    {
        $employees = Employee::whereNull('startout_data')->get();
        return view('admin.Task.createAll',compact('employees'));
    }

    public function store(Request $request,$id)
    {
        $request->validate(
        [
            'title'   => 'required|string',
            'date'    => 'required|date',
            'number'  => 'required|string',
            'task_res'=> 'nullable|string|max:255',
            'note'    => 'nullable|string|max:1000',
            'emp_id'  => 'nullable|integer|exists:employees,id',
        ],
        [
            'title.required'  => 'يجب ادخال الغرض من التكليف',
            'date.required'   => 'يجب إدخال تاريخ التكليف',
            'number.required' => 'يجب إدخال رقم الإشاري الخاص بالتكليف',
        ]);

        DB::beginTransaction();
        try {
            $task = new Task;

            $emp = ($id == 0)
                ? Employee::find($request->input('emp_id'))
                : Employee::find($id);

            if (!$emp) {
                DB::rollBack();
                return back()->with('error','الموظف غير موجود')->withInput();
            }

            $task->emp_id   = $emp->id;
            $task->title    = $request->input('title');
            $task->date     = $request->input('date');
            $task->note     = $request->input('note');
            $task->number   = $request->input('number');
            $task->task_res = $request->input('task_res');
            $task->note     = $request->input('note');
            $task->created_id = auth()->id();
            $task->save();

            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $rows = [];
                foreach ($files as $file) {
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('TASK', $fileName, 'public');
                    $rows[] = [
                        'type'         => 'task',
                        'procedure_id' => $task->id,
                        'path'         => 'TASK/' . $fileName,
                    ];
                }
                if ($rows) systmFiles::insert($rows);
            }

            $log = new Log;
            $log->user_id = auth()->id();
            $log->type    = 7;
            $log->emp_id  = $emp->id;
            $log->title   = " اضافة تكليف جديد (".$request->input('title').")";
            $log->log     = " تمت إضافة تكليف جديد (".$request->input('note').")";
            $log->save();

            DB::commit();
            return redirect()->route('tasks.show',[$emp->id])->with('success','تــمــت إضــافــة تـكـلـيـفـ للــموظــف بــنــجــاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    public function show($id)
    {
        $employee = Employee::find($id);
        $tasks = Task::where('emp_id', $id)->paginate(20);
        return view('admin.Task.show',compact('employee','tasks'));
    }

    public function edit($id)
    {
        $task = Task::find($id);
        return view('admin.Task.edit',compact('task'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
        [
            'title'   => 'required|string',
            'date'    => 'required|date',
            'number'  => 'required|string',
            'task_res'=> 'nullable|string|max:255',
            'note'    => 'nullable|string|max:1000',
        ],
        [
            'title.required'  => 'يجب ادخال الغرض من التكليف',
            'date.required'   => 'يجب إدخال تاريخ التكليف',
            'number.required' => 'يجب إدخال رقم الإشاري الخاص بالتكليف',
        ]);

        DB::beginTransaction();
        try {
            $task = Task::find($id);
            if (!$task) {
                DB::rollBack();
                return back()->with('error','التكليف غير موجود');
            }

            $task->title    = $request->input('title');
            $task->date     = $request->input('date');
            $task->note     = $request->input('note');
            $task->number   = $request->input('number');
            $task->task_res = $request->input('task_res');
            $task->note     = $request->input('note'); 
            $task->save();

            if ($request->hasFile('files')) {
                // احذف القديمة فعليًا
                $oldFiles = systmFiles::where('procedure_id', $task->id)
                    ->where('type', 'task')
                    ->get();
                foreach ($oldFiles as $file) {
                    if (Storage::disk('public')->exists($file->path)) {
                        Storage::disk('public')->delete($file->path);
                    }
                }
                systmFiles::where('procedure_id', $task->id)
                    ->where('type', 'task')
                    ->delete();

                // أضف الجديدة
                $rows = [];
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('TASK', $fileName, 'public');
                    $rows[] = [
                        'type'         => 'task',
                        'procedure_id' => $task->id,
                        'path'         => 'TASK/' . $fileName,
                    ];
                }
                if ($rows) systmFiles::insert($rows);
            }

            $log = new Log;
            $log->user_id = auth()->id();
            $log->type    = 7;
            $log->emp_id  = $task->emp_id;
            $log->title   = " التعديل علي التكليف (".$request->input('title').")";
            $log->log     = " تم التعديل علي بيانات التكليف (".$request->input('title').")";
            $log->save();

            DB::commit();
            return redirect()->route('tasks.index')->with('success','تــمــت الـتـعـدـل عـلي بـيـانـات الـتـكـلـيـفـ للــموظــف بــنــجــاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            systmFiles::where('procedure_id', $id)
                ->where('type', 'task')
                ->update(['delete_at'=> now()]);

            $abs = Task::find($id);
            if (!$abs) {
                DB::rollBack();
                return back()->with('error','التكليف غير موجود');
            }

            $abs->delete_at = now();
            $abs->delete();

            $log = new Log;
            $log->user_id = auth()->id();
            $log->type    = 11;
            $log->emp_id  = $abs->emp_id;
            $log->title   = " حذف بيانات الـتـكـلـيـفـ (".$abs->title.")";
            $log->log     = " تم حذف بيانات الـتـكـلـيـفـ (".$abs->title.")";
            $log->save();

            DB::commit();
            return back()->with('success','تـم حـذفـ الـتـكـلـيـفـ الـمـوظـف بـنـجـاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'للاسف حدث خطأ ما الرجاء اعادة المحاولة');
        }
    }
}
