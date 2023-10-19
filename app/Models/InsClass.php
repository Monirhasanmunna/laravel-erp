<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InsClass extends Model
{
    use HasFactory;


    protected $guarded=['id'];
    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id = Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }

    protected $table = 'ins_classes';


    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function examRoutines()
    {
        return $this->hasMany(ExamRoutine::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'class_subjects','ins_class_id')->using(ClassSubject::class);
    }


    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class,'ins_class_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class,'class_id');
    }

    public function studentTime()
    {
        return $this->hasOne(StudentTimesetting::class);
    }
}
