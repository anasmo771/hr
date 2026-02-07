<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    // اسمح بالتعبئة الجماعية لهذه الحقول (الحد الأدنى المطلوب لحل مشكلتك)
    protected $fillable = [
        'user_id',
        'type',
        'emp_id',
        'vec_id',   // مُعرّف الإجراء المرتبط (إجازة/دورة/عقوبة... إن وُجد)
        'title',
        'log',
        'delete_at',
    ];

    protected $casts = [
        'user_id'  => 'integer',
        'type'     => 'integer',
        'emp_id'   => 'integer',
        'vec_id'   => 'integer',
        'delete_at'=> 'datetime',
    ];

    // ===== العلاقات =====

    // سجل يخص موظفًا
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    // منشئ السجل (المستخدم)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ارتباط بسجل إجازة (عبر vec_id)
    public function vacation()
    {
        return $this->belongsTo(Vacation::class, 'vec_id', 'id');
    }

    // إبقاء الاسم القديم تجنبًا لكسر أي استدعاءات سابقة
    public function vecation()
    {
        return $this->vacation();
    }

    // قسم فرعي (لو كنت تسجّل عليه باستخدام emp_id — أبقيتها كما هي)
    public function subsection()
    {
        return $this->belongsTo(subSection::class, 'emp_id', 'id');
    }

    // دورة تدريبية (عبر vec_id)
    public function course()
    {
        return $this->belongsTo(Course::class, 'vec_id', 'id');
    }

    // عقوبة (عبر vec_id)
    public function punshe()
    {
        return $this->belongsTo(Punishment::class, 'vec_id', 'id');
    }

    // تخصص مرتبط بالموظف (عبر emp_id)
    public function spec()
    {
        return $this->belongsTo(Specialty::class, 'emp_id', 'id');
    }
}
