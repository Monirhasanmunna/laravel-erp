<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Average_exam_setting extends Model
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

    public function class()
    {
        return $this->belongsTo(InsClass::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function average_exam_setting_detail()
{
    return $this->hasMany(Average_exam_setting_detail::class);
}
  
}
