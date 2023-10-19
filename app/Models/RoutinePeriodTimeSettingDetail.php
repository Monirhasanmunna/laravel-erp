<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutinePeriodTimeSettingDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }


    public function routines(){
        return $this->hasMany(ClassRoutineDetail::class,'period_time_details_id');
    }
}
