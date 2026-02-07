<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    public function type(){
        return $this->hasOne(ArchiveType::class,'id', 'type_id');
    }

    public function emp(){
        return $this->hasOne(Employee::class,'id', 'emp_id');
    }

    public function files(){
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'archive');
    }

}
