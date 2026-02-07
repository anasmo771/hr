<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    public function employees(){
        return $this->hasMany(Employee::class, 'specialty_id', 'id');
    }
}
