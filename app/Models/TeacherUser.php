<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class TeacherUser extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    protected static function booted()
    {
        // static::addGlobalScope(new InstituteScope);
        static::creating(function ($item) {
            $item->institute_id = Helper::getInstituteId();
            $item->institute_branch_id = Helper::getBranchId();
        });
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function application()
    {
        return $this->morphOne(LeaveApplication::class, 'source');
    }
}
