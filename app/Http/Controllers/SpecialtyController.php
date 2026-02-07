<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:system-list', ['only' => ['index','show','search']]);
         $this->middleware('permission:system-create', ['only' => ['create','store']]);
         $this->middleware('permission:system-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:system-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Specialty::paginate(25);
        return view('admin.Specialty.index',compact('all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Specialty.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(
        [
            'name'  => "required|string|unique:specialties",
        ],
        [
            'name.required' => 'يجب إدخال اسم التخصص',
            'name.unique' => 'اسم التخصص الذي ادخلته موجود مسبقآ الرجاء التحقق',
        ]);

        $Specialty = new Specialty;
        $Specialty->name = request('name');
        $Specialty->save();

        $log = new Log;
        $log->user_id = auth()->user()->id;
        $log->type = 12;
        $log->title = " اضافة تخصص جديد (".request('name').")";
        $log->log = " تمت إضافة تخصص جديد (".request('name').")";
        $log->save();

        return redirect()->back()->with('success','تــمــت إضــافــة الـتــخــصـص بــنــجــاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $specialty = Specialty::find($id);
        return view('admin.Specialty.edit',compact('specialty'));
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
            'name'  => "required|string|unique:specialties",
        ],
        [
            'name.required' => 'يجب إدخال اسم التخصص',
            'name.unique' => 'اسم التخصص الذي ادخلته موجود مسبقآ الرجاء التحقق',
        ]);
            $specialty = Specialty::find($id);
            $specialty->name = request('name');
            $specialty->update();

            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 13;
            $log->title = " تعديل بيانات التخصص (".request('name').")";
            $log->log = " تم تعديل بيانات التخصص (".request('name').")";
            $log->save();

            return redirect()->route('Specialties.index')->with('success','تــم تـعـديـل بـيـانـات الـتــخــصـص بــنــجــاح');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
