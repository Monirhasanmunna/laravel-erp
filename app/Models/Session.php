<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Session extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id = Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }



    public function classes()
    {
        return $this->hasMany(InsClass::class);
    }


    public function section()
    {
        return $this->hasManyThrough(Section::class, InsClass::class);
    }


    public function students(){
        return $this->hasMany(Student::class);
    }


    public function maleStudents(){
        return $this->hasMany(Student::class)->where('gender',"Male");
    }

    public function femaleStudents(){
        return $this->hasMany(Student::class)->where('gender',"Female");
    }
}
