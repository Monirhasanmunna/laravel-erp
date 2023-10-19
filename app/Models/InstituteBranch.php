<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteBranch extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
    }

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function teachers(){
        return $this->hasMany(Teacher::class);
    }

    public function institute()
    {
        return $this->belongsTo(Institution::class);
    }
}
