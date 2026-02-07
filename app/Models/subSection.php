<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subSection extends Model
{
    use HasFactory;

    // علاقات الموظفين الحالية
    public function employees()
    {
        return $this->hasMany(Employee::class, 'section_id', 'id');
    }

    public function subEmployees()
    {
        return $this->hasMany(Employee::class, 'sub_section_id', 'id');
    }

    /**
     * الأبناء (متكرّرة Recursively)
     * نحمّل sub داخلها حتى يظهر أي عمق (أبناء، أحفاد، وهكذا)
     * ونحمّل كذلك مواضع الملاك لكل عقدة لعرضها مباشرة.
     */
    public function sub()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->with(['sub', 'unitPositions.staffing'])
            ->orderBy('sort_order');
    }

    // الأب
    public function main()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // مواضع الملاك الخاصة بالوحدة
    public function unitPositions()
    {
        return $this->hasMany(UnitStaffing::class, 'unit_id');
    }
}
