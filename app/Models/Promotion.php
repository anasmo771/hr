<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'emp_id','num','type','date','prev_degree','new_degree',
        'created_id','accept','consumed_bonus_ids',
    ];

    protected $casts = [
        'consumed_bonus_ids' => 'array',
    ];

    public function emp(){ return $this->belongsTo(Employee::class,'emp_id'); }
    public function user(){ return $this->belongsTo(User::class,'created_id'); }
}
