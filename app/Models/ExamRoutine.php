<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoutine extends Model
{
    use HasFactory;

    protected $guarded=['id'];
    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id =  Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }

    public function classSubjects()
    {
        return $this->belongsToMany(ClassSubject::class,'exam_routine_class_subject','exam_routine_id','class_subject_id')
            ->withPivot('date','start_time','end_time','room')->orderBy('date');
    }



    public function class()
    {
        return $this->belongsTo(InsClass::class,'ins_class_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
