<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombindSubject extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function class(){
        return $this->belongsTo(InsClass::class,'class_id','id');
    }

    public function exam(){
        return $this->belongsTo(Exam::class,'exam_id','id');
    }

    public function subject1(){
        return $this->belongsTo(ClassSubject::class,'subject_1','id');
    }
    public function subject2(){
        return $this->belongsTo(ClassSubject::class,'subject_2','id');
    }
}
