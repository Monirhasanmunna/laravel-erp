<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoutineDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function classRoutine()
    {
        return $this->belongsTo(ClassRoutine::class);
    }


    public function periodTime()
    {
        return $this->belongsTo(RoutinePeriodTimeSettingDetail::class, 'period_time_details_id');
    }


    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }


    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }
}
