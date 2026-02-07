<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitStaffing extends Model
{
    protected $fillable = [
        'unit_id','staffing_id','title','quota','is_manager','sort_order','notes'
    ];

    public function unit()
    {
        return $this->belongsTo(subSection::class, 'unit_id');
    }

    public function staffing()
    {
        return $this->belongsTo(Staffing::class, 'staffing_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'unit_staffing_id');
    }

    // الموظفون النشطون (غير مستقيلين/خارجين)
    public function activeEmployees()
    {
        return $this->employees()->whereNull('startout_data');
    }

    // المتاح = quota - النشطين
    public function getAvailableAttribute(): int
    {
        return max((int)$this->quota - (int)$this->activeEmployees()->count(), 0);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->title ?: ($this->staffing->name ?? '—');
    }
}
