<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\InstituteBranch;
use App\Models\Institution;
use Illuminate\Support\Facades\Session;

class InstituteBranchService
{


    public static function init(){
     

        $branch = InstituteBranch::where('institute_id',Helper::getInstituteId())->first();
        if(!Session::has('branch_id')){
            Session::put('branch_id',$branch->id ?? 1);
        }

    }
}
