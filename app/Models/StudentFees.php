<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use App\Traits\FeesStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentFees extends Model
{
    use HasFactory, FeesStatus;
    protected $guarded=['id'];
    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id = Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }

    protected $appends = [
        'status'
    ];

    public function getStatusAttribute() {
        return $this->studentfees($this->attributes['id'],$this->attributes['month'],$this->attributes['year']);
    }



    public function feestype()
    {
        return $this->belongsTo(FeesType::class,'fees_type_id');
    }
    public function details()
    {
        return $this->hasMany(FeesDetail::class,'fees_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public function  feesPayment()
    {
        return $this->morphMany(StudentFeeReceivedDetail::class, 'source');
    }

    public function  feesHead()
    {
        return $this->morphMany(FeesHead::class, 'source');
    }
}
