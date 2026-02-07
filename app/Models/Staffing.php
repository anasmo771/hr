<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staffing extends Model
{
    use HasFactory;

    /**
     * الوحدات/المقاعد التابعة لهذا الملاك
     */
    public function unitStaffings()
    {
        return $this->hasMany(UnitStaffing::class, 'staffing_id', 'id');
    }

    /**
     * الموظفون المنسوبون لهذا الملاك عبر الوحدات الوظيفية
     * employees.unit_staffing_id -> unit_staffings.id -> staffings.id
     */
    public function employees()
    {
        return $this->hasManyThrough(
            Employee::class,      // الموديل البعيد (الموظف)
            UnitStaffing::class,  // جدول العبور (الوحدة/المقعد)
            'staffing_id',        // FK في unit_staffings يُشير إلى staffings.id
            'unit_staffing_id',   // FK في employees يُشير إلى unit_staffings.id
            'id',                 // PK في staffings
            'id'                  // PK في unit_staffings
        );
    }

    /**
     * (اختياري) موظفون يعملون فقط
     */
    public function workingEmployees()
    {
        return $this->employees()->where('status', 'يعمل');
    }
}
