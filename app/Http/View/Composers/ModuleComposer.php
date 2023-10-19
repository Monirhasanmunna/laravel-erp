<?php

namespace App\Http\View\Composers;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModuleComposer
{
    public function compose(View $view)
    {

        $roleId = Auth::user()->role_id;
        $role   = Role::with('modules')->find($roleId);

        $modules = $role->modules;

        $view->with('modules',$modules);
    }
}