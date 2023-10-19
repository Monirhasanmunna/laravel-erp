<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Average_exam_setting_detail extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function class()
    {
        return $this->belongsTo(InsClass::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function average()
    {
        return $this->belongsTo(Average_exam_setting::class);
    }
}
