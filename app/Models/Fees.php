<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\FeesStatus;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @method static whereHas(string $string, \Closure $param)
 */
class Fees extends Model
{

    use HasFactory,FeesStatus;

    protected $guarded = [''];
    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id =  Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feesType()
    {
        return $this->belongsTo(FeesType::class,'fees_type_id');
    }

    public function details()
    {
        return $this->hasMany(FeesDetail::class,'fees_id');
    }
    public function session()
    {
        return $this->belongsTo(Session::class,'session_id');
    }
    public function class()
    {
        return $this->belongsTo(InsClass::class,'class_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class,'section_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class,'group_id');
    }



    public function transactionDetail()
    {
        return $this->morphOne(TransactionDetail::class, 'source');
    }


}
