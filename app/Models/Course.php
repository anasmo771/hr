<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function user(){
        return $this->hasOne(User::class, 'id', 'created_id');
    }

    public function employees(){
        return $this->hasMany(CourseEmployee::class, 'course_id', 'id');
    }

    public function files(){
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'course');
    }

}
