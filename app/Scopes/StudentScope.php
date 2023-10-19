<?php

namespace App\Scopes;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class StudentScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('roll_no',"ASC");
    }
}
