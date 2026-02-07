<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEmployee extends Model
{
    use HasFactory;
    
    public function emp(){
        return $this->hasOne(Employee::class, 'id', 'emp_id');
    }
    
    public function course(){
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
