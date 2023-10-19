<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMarksInputDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function studentMarksInput(){
        return $this->belongsTo(StudentMarksInput::class);
    }
     public function subMarksDistDetails()
    {
        return $this->belongsTo(SubMarksDistDetail::class);
    }

}
