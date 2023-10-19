<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class,'section_id','id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(InsClass::class,'ins_class_id');
    }
}
