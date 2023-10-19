<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeeReceivedDetail extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    public function source()
    {
        return $this->morphTo();
    }

    public function  studentFeeReceived()
    {
        return $this->belongsTo(StudentFeeReceived::class);
    }

    public function feesType()
    {
        return $this->belongsTo(FeesType::class);
    }

}
