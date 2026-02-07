<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitStaffing;
use App\Models\subSection;   // انت مسميه هكذا، فخلّيه كما هو
use App\Models\Staffing;

class UnitStaffingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id'     => ['required','exists:sub_sections,id'],
            'staffing_id' => ['required','exists:staffings,id'],
            'title'       => ['nullable','string','max:255'],
            'quota'       => ['required','integer','min:1'],
            'is_manager'  => ['nullable','boolean'],
            'sort_order'  => ['nullable','integer','min:0'],
            'notes'       => ['nullable','string'],
        ]);
        $data['is_manager'] = $request->boolean('is_manager');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        UnitStaffing::create($data);
        return back()->with('success','تمت إضافة الموضع ضمن الملاك بنجاح.');
    }

    public function update(Request $request, UnitStaffing $unitStaffing)
    {
        $data = $request->validate([
            'unit_id'     => ['required','exists:sub_sections,id'],
            'staffing_id' => ['required','exists:staffings,id'],
            'title'       => ['nullable','string','max:255'],
            'quota'       => ['required','integer','min:1'],
            'is_manager'  => ['nullable','boolean'],
            'sort_order'  => ['nullable','integer','min:0'],
            'notes'       => ['nullable','string'],
        ]);
        $data['is_manager'] = $request->boolean('is_manager');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $unitStaffing->update($data);
        return back()->with('success','تم تحديث الموضع ضمن الملاك بنجاح.');
    }

    public function destroy(UnitStaffing $unitStaffing)
    {
        $unitStaffing->delete();
        return back()->with('success','تم حذف الموضع من الملاك.');
    }

    /**
     * تُرجّع وحدات/مواضع الملاك للوحدة (subSection) بصيغة JSON
     * تُطابق ما يتوقعه الـJS: id, name, code
     */
    public function bySubSection(subSection $subSection)
    {
        $units = $subSection->unitPositions()
            ->with('staffing:id,name')              // جلب اسم الملاك الوظيفي لوظيفة الموضع
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($u) {
                return [
                    'id'   => $u->id,
                    // نعرض اسم الملاك الوظيفي + العنوان إن وجد
                    'name' => trim(($u->staffing->name ?? '') . ($u->title ? (' - ' . $u->title) : '')) ?: 'موضع بدون اسم',
                    // سنستخدم title كـ code لعرض إضافي (إن رغبت)
                    'code' => $u->title ?: null,
                ];
            });

        return response()->json($units);
    }
}
