<?php

namespace App\Models;

use App\Helper\Helper;
use App\Scopes\InstituteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class AssignTeacherSubject extends Pivot
{
    use HasFactory;
    protected $guarded=['id'];
   

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    
    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }
 
}
