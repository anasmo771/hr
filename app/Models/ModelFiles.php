<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelFiles extends Model
{
    use HasFactory;

    public function files(){
        return $this->hasMany(File::class, 'procedure_id', 'id')->where('type', 'model');
    }

}
