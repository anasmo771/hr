<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\ArchiveType;

class ArchiveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = ArchiveType::latest()->get();
        return view('admin.archiveTypes.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.archiveTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ], [
            'type.required' => 'يجب إضافة اسم نوع الأرشيف',
        ]);

        $type = new ArchiveType();
        $type->name = $request->type;
        $type->save();

        return redirect()->back()->with('success', 'تمت إضافة نوع أرشيف جديد بنجاح');
    }

    /**
     * Display the specified file (download).
     */
    public function show($id)
    {
        $file = File::find($id);

        if ($file && $file->path) {
            $path = storage_path('app/public/' . $file->path);

            if (file_exists($path)) {
                return response()->download($path);
            } else {
                return redirect()->back()->with('error', 'عذراً، الملف غير موجود في التخزين.');
            }
        }

        return redirect()->back()->with('error', 'عذراً لا يوجد ملف لهذا الأرشيف.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $type = ArchiveType::findOrFail($id);
        return view('admin.archiveTypes.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
        ], [
            'type.required' => 'يجب إدخال اسم نوع الأرشيف',
        ]);

        $type = ArchiveType::findOrFail($id);
        $type->name = $request->type;
        $type->save();

        return redirect()->back()->with('success', 'تم تحديث نوع الأرشيف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $type = ArchiveType::findOrFail($id);
        $type->delete();

        return redirect()->back()->with('success', 'تم حذف نوع الأرشيف بنجاح');
    }
}
