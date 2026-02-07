<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'type',
        'start_date',
        'end_date',         // تاريخ المباشرة المخطط
        'actual_end_date',  // التاريخ الفعلي (جديد)
        'days',
        'companion',
        'reason',
        'accept',
        'acceptFile',
        'created_id',
        'delete_at',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'actual_end_date' => 'date',
        'accept'          => 'boolean',
        'companion'       => 'boolean',
    ];

    // منشئ السجل
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_id');
    }

    // الموظف
    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'emp_id');
    }

    // الملفات المرفقة
    public function files()
    {
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'vacation');
    }

    // هل ما زالت الإجازة جارية الآن (بدون رجوع فعلي)
    public function getIsActiveAttribute(): bool
    {
        if (!$this->accept) return false;
        if ($this->actual_end_date) return false;
        if (!$this->start_date || !$this->end_date) return false;
        $today = now()->toDateString();
        return $this->start_date->toDateString() <= $today && $today <= $this->end_date->toDateString();
    }
}
