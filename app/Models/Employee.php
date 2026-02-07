<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // إن أردت الحماية الشاملة استخدم $fillable بدلاً من guarded
    protected $guarded = [];

    // تحويل التواريخ تلقائياً
    protected $casts = [
        'start_date'       => 'date:Y-m-d',
        'degree_date'      => 'date:Y-m-d',
        'futurepromotion'  => 'date:Y-m-d',
        'futureBonus'      => 'date:Y-m-d',
        'due'              => 'date:Y-m-d',
        'startout_data'    => 'date:Y-m-d',
    ];

    /* ===================== العلاقات الأساسية ===================== */

    // الموظف يَنتمي إلى شخص
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    // يَنتمي إلى قسم/وحدة (sub_section)
    public function subSection()
    {
        return $this->belongsTo(subSection::class, 'sub_section_id');
    }

    // الترقـيات
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'emp_id', 'id');
    }

    // العـلاوات
    public function bouns()
    {
        return $this->hasMany(Bonus::class, 'emp_id', 'id');
    }

    // التخصص
    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    // تفاصيل الندب/الإعارة
    public function ndb()
    {
        return $this->hasMany(Ndb_Detail::class, 'emp_id', 'id');
    }

    // المستخدم الذي أنشأ السجل
    public function user()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    // الإجازات
    public function vacations()
    {
        return $this->hasMany(Vacation::class, 'emp_id', 'id');
    }

    // الدورات
    public function courses()
    {
        return $this->hasMany(Course::class, 'emp_id', 'id');
    }

    // العقوبات
    public function punshes()
    {
        return $this->hasMany(Punishment::class, 'emp_id', 'id');
    }

    // الملفات المرتبطة بالموظف
    public function files()
    {
        return $this->hasMany(File::class, 'procedure_id', 'id')
                    ->where('type', 'employee');
    }

    /* =============== الوحدة/المقعد الوظيفي الجديد =============== */

    // المقعد/الوحدة الوظيفية المعيّن عليها الموظف
    public function unitStaffing()
    {
        return $this->belongsTo(UnitStaffing::class, 'unit_staffing_id', 'id');
    }

    // اسم المسمّى عبر المقعد (للاستخدام السريع في القوائم)
    public function getStaffingNameViaUnitAttribute()
    {
        return $this->unitStaffing?->staffing?->name;
    }

    public function getStaffingIdViaUnitAttribute()
    {
        return $this->unitStaffing?->staffing_id;
    }

    /* ===================== ملحقات/مساعدات ===================== */

    // توافقًا مع الواجهات التي تستعمل $emp->section->name
    // نعيد "القسم الأب" إن وُجد، وإلا نفس الـ subSection
    public function getSectionAttribute()
    {
        // يُفترض أن موديل subSection يحتوي علاقة parent()
        return $this->subSection?->parent ?: $this->subSection;
    }

    // نطاق للموظفين النشطين (لم يخرجوا)
    public function scopeActive($query)
    {
        return $query->whereNull('startout_data');
    }
}
