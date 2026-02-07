<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id', 'title', 'note', 'number', 'task_res', 'date', 'created_id',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'created_id');
    }

    public function emp(){
        return $this->hasOne(Employee::class, 'id', 'emp_id');
    }

    public function files(){
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'task');
    }
}
