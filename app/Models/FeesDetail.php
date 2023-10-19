<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FeesDetail extends Model
{
    use HasFactory;

    protected $guarded= ['id'];


    public function fees(){
        return $this->belongsTo(Fees::class);
    }

    public function  feesHead()
    {
        return $this->morphMany(FeesHead::class, 'source');
    }

    public function  feesPayment()
    {
        return $this->morphMany(StudentFeeReceivedDetail::class, 'source');
    }

}
