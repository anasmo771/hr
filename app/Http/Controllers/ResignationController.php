<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Employee;
use App\Models\subSection;
use Illuminate\Http\Request;

class ResignationController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:resignation-list', ['only' => ['index','show','search']]);
         $this->middleware('permission:resignation-create', ['only' => ['create','store']]);
         $this->middleware('permission:resignation-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:resignation-delete', ['only' => ['destroy']]);
    }


    public function index()
    {
        $employees = Employee::where('startout_data','!=', null)->paginate(20);
        $subsection1 = subSection::get();
        if(auth()->user()->role_id == 2)
        {

        $employees = Employee::where('startout_data','!=', null)->where('workplace',5)->paginate(20);
        $subsection1 = subSection::where('id',5)->paginate(20);
        }
        return view('admin.Resignation.index',compact('employees','subsection1'));
    }

    public function create()
    {
    }

    public function edit($id)
    {
        $emp = Employee::find($id);
        return view('admin.Resignation.create',compact('emp'));
    }


    public function update(Request $request,$id)
    {
        request()->validate(
        [
            'startout_data'=>"required",
            'ch'=>'required|string|max:1|min:1',
            'num'  => "required|min:1",
        ],
        [
            'startout_data.required'=>'يجب ادخال تاريخ الاستقاله',
            'ch.required' => 'يجب ادخال الحرف ',
            'ch.max' =>'يجب ان يكون حرف 1  ',
            'ch.min' =>'يجب ان يكون حرف 1  ',
            'num.required' => 'يجب ادخال الرقم ',
            'num.min' => 'يجب ادخال رقم 1 على الاقل',

        ]);
        $emp = Employee::find($id);
        $emp->startout_data = request('startout_data');
        $emp->archive_char=request('ch');
        $emp->archive_num=request('num');
        $emp->update();

        $log = new Log;
        $log->user_id = auth()->user()->id;
        $log->type = 4;
        $log->emp_id = $emp->id;
        $log->title = " استقالة بتاريخ (".request('startout_data').")";
        $log->log = " تمت الاستقالة بتاريخ (".request('startout_data').")";
        $log->save();

        return redirect()->route('resignation.index')->with('success','تــمــت إحــالــة الـمــوظــف كــمســتقــيل');

    }

    public function show(Request $request)
    {
        if (!empty($request)) {
            $query = $request->all();
        }
        $conditions = [];
        $conditions2 = [];
        if (request('name'))
        {
            if (request('type') == "name" && request('name')) {
                $conditions[] = ['name', 'LIKE', '%' . request('name') . '%'];
            } elseif (request('type') == "id" && request('name')) {
                $conditions[] = ['N_id', 'LIKE', '%' . request('name') . '%'];
            } elseif (request('type') == "degree" && request('name')) {
                $conditions2[] = ['degree', 'LIKE', '%' . request('name') . '%'];
            } elseif (request('type') == "res_num" && request('name')) {
                $conditions2[] = ['res_num', 'LIKE', '%' . request('name') . '%'];
            }
        }
        if (request('section_id')) {
            if (request('section_id') != "all") {
                $conditions2[] = ['section_id', request('section_id')];
            }
        }
        $conditions2[] = ['startout_data', '!=', NULL];
        if(request('type') == "name" && request('name') || request('type') == "id" && request('name')){
            $employees = Employee::where($conditions2)->whereHas('person',function($q) use($conditions) {
                $q->where($conditions);
            })->latest()->paginate(25);
        }else{
            if (!empty($conditions2)) {
                $employees = Employee::where($conditions2)->latest()->paginate(25);
            } else {
                return redirect()->back()->with('error', 'الـرجاء تعبئة إحدي الخانات علي الاقل');
            }
        }
        if ($employees->count() == 0) {
            return redirect()->back()->with('error', 'الــموظــف الــذي تبــحث عنــه غيــر موجــود');
        }
        $subsection1 = subSection::get();
        return view('admin.Resignation.index', compact('employees', 'subsection1', 'query'));
    }

    public function destroy($id)
    {
        //
    }

}
