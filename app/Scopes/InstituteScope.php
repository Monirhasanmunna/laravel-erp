<?php

namespace App\Scopes;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

class InstituteScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $branchId = Helper::getBranchId();
        $builder->where('institute_id', '=', Helper::getInstituteId() ?? Auth::user()->institute->id);

        $tableName = $model->getTable();

        $exceptTable = ["banners","messages","institute_menus","menu_items","news","notices","ataglances","events","getintouches","school_infos"];

        $exist = in_array($tableName,$exceptTable);

        if(!$exist){
            if (Schema::hasColumn($tableName, 'institute_branch_id')){
                $builder->where('institute_branch_id', '=', $branchId);
            }
        }

    }
}
