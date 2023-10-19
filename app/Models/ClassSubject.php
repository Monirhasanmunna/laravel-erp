<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use App\Scopes\SubjectOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ClassSubject extends Pivot
{
    use HasFactory;
    use SoftDeletes;



    protected $table = 'class_subjects';


    protected static function booted()
    {
        static::addGlobalScope(new SubjectOrderScope);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function subjectType()
    {
        return $this->belongsTo(SubjectType::class);
    }

    public function assignStudents(){
        return $this->hasMany(StudentSubjectAssign::class,'class_subject_id','id');
    }

    public function markDists(){
        return $this->hasMany(SubMarksDist::class,'class_subject_id','id');
    }
}
