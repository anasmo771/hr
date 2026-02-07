<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Employee;
use App\Models\subSection;
use App\Models\Staffing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class subSectionController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:system-list', ['only' => ['index','show','search']]);
         $this->middleware('permission:system-create', ['only' => ['create','subSectionStore','store']]);
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
        $roots = \App\Models\subSection::where(function($q){
                $q->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->with(['sub', 'unitPositions.staffing'])   // لا حاجة sub.sub الآن
            ->orderBy('sort_order')
            ->get();

        $staffings = \App\Models\Staffing::orderBy('name')->get();
        return view('admin.Section.index', compact('roots','staffings'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = subSection::where('parent_id', NULL)->paginate(20);
        return view('admin.Section.create',compact('sections'));
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
            'name'  => "required|string",
        ],
        [
            'name.required' => 'يجب إدخال اسم الجهة التابعة',
        ]);
        $sub = new subSection;
        $sub->name = request('name');
        if(request()->file('logo')){
            $file = $request->file('logo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('Sections', $fileName, 'public');
            $sub->logo = 'Sections/' . $fileName;
        }
        $sub->save();

        $log = new Log;
        $log->user_id = auth()->user()->id;
        $log->type = 5;
        $log->title = " اضافة جهة جديدة (".request('name').")";
        $log->log = " تمت إضافة جهة جديدة (".request('name').")";
        $log->save();

        return redirect()->back()->with('success','تــمــت إضــافــة الـجـهـة الـتـابـعـة بــنــجــاح');
    }

    public function subSectionStore(Request $request)
    {
        request()->validate(
        [
            'name'  => "required|string",
            'section_id'  => "required|string",
        ],
        [
            'name.required' => 'يجب إدخال اسم الجهة التابعة',
            'section_id.required' => 'يجب اختيار الإدارة التابعة',
        ]);
        $sub = new subSection;
        $sub->name = request('name');
        $sub->parent_id = request('section_id');
        $sub->save();

        $log = new Log;
        $log->user_id = auth()->user()->id;
        $log->type = 5;
        $log->title = " اضافة قسم جديد (".request('name').")";
        $log->log = " تمت إضافة قسم جديد (".request('name').")";
        $log->save();

        return redirect()
        ->route('subSection.index')
        ->with('success','تــمــت إضــافــة قــســم جــديــد بــنــجــاح')
        ->with('expand', (int)$sub->parent_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employees = Employee::where('section_id', $id)->paginate(25);
        $section = subSection::find($id);
        $subsection1 = subSection::where('parent_id', NULL)->get();
        return view('admin.Section.show',compact('employees','section','subsection1'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub = subSection::find($id);
        return view('admin.Section.edit',compact('sub'));
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
                'name'  => "required|string",
            ],
            [
                'name.required' => 'يجب إدخال اسم الجهة التابعة',
            ]);
            $sub = subSection::find($id);
            $sub->name = request('name');
            if(request()->file('logo')){
                if($sub->logo != "logo.png"){
                    File::delete(public_path('storage/'.$sub->logo));
                }
                $file = $request->file('logo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('Sections', $fileName, 'public');
                $sub->logo = 'Sections/' . $fileName;
            }
            $sub->update();

            $log = new Log;
            $log->user_id = auth()->user()->id;
            $log->type = 6;
            $log->title = " تعديل بيانات الجهة (".request('name').")";
            $log->log = " تم تعديل بيانات الجهة (".request('name').")";
            $log->save();

            return redirect()->route('subSection.index')->with('success','تــم تـعـديـل بـيـانـات الجـهـة الـتـابـعـة بــنــجــاح');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section = subSection::find($id);
        if($section->logo != "logo.png"){
            File::delete(public_path('storage/'.$section->logo));
        }
        $section->delete();
        return redirect()->route('subSection.index')->with('success','تـم حـذفـ الجـهـة الـتـابـعـة بـنـجـاح');
    }
}
