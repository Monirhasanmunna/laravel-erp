<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use App\Scopes\TeacherScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id = Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institution::class,'institute_id');
    }
  
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'assign_teacher_subject')->distinct();
    }

    public function teacherTime()
    {
        return $this->hasOne(TeacherTimesetting::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function leaveApplication()
    {
        return $this->hasMany(LeaveTemplate::class);
    }

    public function qualifications()
    {
        return $this->hasMany(Qualification::class);
    }

    public function attendance()
    {
        return $this->morphOne(Attendance::class, 'source');
    }

    public function smshistory()
    {
        return $this->morphOne(SmsHistory::class, 'source');
    }


    public function teacherUser()
    {
        return $this->hasOne(TeacherUser::class);
    }
}
