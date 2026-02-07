<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\ModelFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{


    function __construct()
    {
         $this->middleware('permission:system-list', ['only' => ['index','show','search']]);
         $this->middleware('permission:system-create', ['only' => ['create','store']]);
         $this->middleware('permission:system-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:system-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $models = ModelFiles::latest()->paginate(25);
        return view('admin.Model.index',['models'=>$models]);
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
            'name' => 'required',
            'files' => 'required',
        ],
        [
            'name.required' => 'يجب ادخال عنوان النموذج',
            'files.required' => 'يجب ادخال الملف',
        ]);

        DB::beginTransaction();
        try {
            $arr = new ModelFiles;
            $arr->name = request('name');
            $arr->save();

            if (request()->hasFile('files')) {
                $files = request()->file('files'); // Get the files
                $finalArray = [];
                foreach ($files as $file) { // Use foreach for simpler syntax
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('MODEL', $fileName, 'public'); // Store the file
                    $filePath = 'MODEL/' . $fileName; // Generate the file path
                    $finalArray[] = [
                        'type' => 'model',
                        'procedure_id' => $arr->id,
                        'path' => $filePath,
                    ];
                }
                if (!empty($finalArray)) {
                    File::insert($finalArray); // Insert the file references
                }
            }



            DB::commit();
            return redirect()->back()->with('success','تمت إضافة النموذج الجديد بنجاح');
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
    public function show(Request $request)
    {
        if(!empty($request)){
            $query = $request->all();
        }
        $models = ModelFiles::where('name', 'LIKE', '%' . request('search') . '%')->latest()->paginate(25);
        return view('admin.Model.index',['models'=>$models,'query'=>$query]);

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
            'name' => 'required',
        ],
        [
            'name.required' => 'يجب ادخال عنوان النموذج',
        ]);

        $arr = ModelFiles::find(request('id'));
        $arr->name = request('name');
        $arr->update();

        if (request()->hasFile('files')) {
            $files = File::where('procedure_id', $arr->id)
            ->where('type', 'model')
            ->get();
            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file->path)) {
                    Storage::disk('public')->delete($file->path);
                }
            }
            File::where('procedure_id', $arr->id)
                ->where('type', 'model')
                ->delete();
            $files = request()->file('files'); // Get the files
            $finalArray = [];
            foreach ($files as $file) { // Use foreach for simpler syntax
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('MODEL', $fileName, 'public'); // Store the file
                $filePath = 'MODEL/' . $fileName; // Generate the file path
                $finalArray[] = [
                    'type' => 'model',
                    'procedure_id' => $arr->id,
                    'path' => $filePath,
                ];
            }
            if (!empty($finalArray)) {
                File::insert($finalArray); // Insert the file references
            }
        }

        return redirect()->back()->with('success','تم تعديل النموذج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $files = File::where('procedure_id', $id)
        ->where('type', 'model')
        ->get();
        foreach ($files as $file) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
        }
        File::where('procedure_id', $id)
            ->where('type', 'model')
            ->delete();


        $models = ModelFiles::find($id);
        $models->delete();
        return redirect()->back()->with('success','تم حذف النموذج بنجاح');
    }

    public function preview($id)
    {
        $file = \App\Models\ModelFiles::findOrFail($id);
    abort_unless($file->path, 404);

    $absolute = storage_path('app/public/'.$file->path);
    abort_unless(file_exists($absolute), 404);

    return response()->file($absolute);
    }

}
