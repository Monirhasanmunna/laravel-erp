<?php

namespace App\Models;

use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Helper\Helper;

class TeacherSms extends Model
{
    use HasFactory;

    protected $guarded =['id'];

    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id =  Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }


    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
