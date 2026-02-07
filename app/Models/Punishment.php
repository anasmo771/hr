<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'reason',
        'pun_type',
        'pun_date',
        'book_num',
        'index',
        'penaltyName',
        'notes',
        'created_id',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    public function emp(){
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    public function files(){
        return $this->hasMany(File::class, 'procedure_id', 'id')
                    ->where('type', 'punishment');
    }
}
