<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;


    public function emp(){
        return $this->hasOne(Employee::class, 'id', 'emp_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function files(){
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'feedback');
    }

}
