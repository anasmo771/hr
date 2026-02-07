<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $table = 'bonuses';

    protected $fillable = [
        'emp_id',
        'bonus_num',
        'date',        // تاريخ الاستحقاق (إلزامي)
        'last_date',   // تاريخ آخر علاوة
        'bonus_date',  // تاريخ قرار/إدخال العلاوة
        'degree',      // لقطة درجة الموظف الحالية
        'estimate',    // التقدير من تقرير الكفاية
        'created_id',
        'accept',      // له default=1 لكن لا ضرر من تمريره
    ];

    protected $casts = [
        'emp_id'     => 'integer',
        'bonus_num'  => 'integer',
        'degree'     => 'integer',
        'created_id' => 'integer',
        'accept'     => 'integer',
        'date'       => 'date',
        'last_date'  => 'date',
        'bonus_date' => 'date',
    ];

    public function emp()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    public function files()
    {
        // إن كنت تستخدم مرفقات من نوع "promotion" للعلاوات
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'promotion');
    }
}
