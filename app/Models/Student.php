<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use App\Scopes\StudentScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=['id'];

     protected static function booted()
     {
         static::addGlobalScope(new InstituteScope);
         static::addGlobalScope(new StudentScope);
         static::creating(function ($item) {
             $item->institute_id = Helper::getInstituteId();
             $item->institute_branch_id = Helper::getBranchId();
         });
     }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function assignSubjects(){
        return $this->hasMany(StudentSubjectAssign::class);
    }


    public function ins_class()
    {
        return $this->belongsTo(InsClass::class, 'class_id', 'id');
    }

    public function student_user(){
        return $this->hasOne(StudentUser::class);
    }


    public function student_fee(){
        return $this->hasMany(StudentFees::class);
    }



    public function  attendance()
    {
        return $this->morphOne(Attendance::class, 'source');
    }



    public function getClassName($class_id)
    {
        $class = InsClass::find($class_id)->first();
        $class_code = substr($class->code,1);
        return $class_code;
    }

    public function class()
    {
        return $this->belongsTo(InsClass::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class,'section_id','id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function meritStudents()
    {
        return $this->hasMany(MeritStudent::class);
    }

    public function smshistory()
    {
        return $this->morphOne(SmsHistory::class, 'source');
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function testimonial()
    {
        return $this->hasOne(studentTestimonial::class,'student_id');
    }

    //accounts
    public function dueAmount(){
        return $this->hasMany(StudentDueAmount::class);
    }

    public function advanceAmount(){
        return $this->hasMany(StudentAdvanceAmount::class);
    }
}
