<?php

namespace App\Models;

use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Helper\Helper;

class SmsHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new InstituteScope);
    }

    public function institute()
    {
        return $this->belongsTo(Institution::class, 'institute_id', 'id');
    }

    public function source()
    {
        return $this->morphTo();
    }
}
