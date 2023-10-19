<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentFeeReceived extends Model
{
    use HasFactory;

    protected $guarded = ['id'];



    public function student(){
        return $this->belongsTo(Student::class);
    }


    public function feeReceivedDetails()
    {
        return $this->hasMany(StudentFeeReceivedDetail::class);
    }

    public function transaction()
    {

        return $this->morphOne(Transaction::class, 'source');
    }
}
