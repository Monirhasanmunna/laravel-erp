<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Role;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{


    public function index(Request $request)
    {
        $roles = Role::get();
        return view('role-management.roles.index',compact('roles'));
    }


    public function create()
    {
        if(Auth::user()->role_name != "Admin"){
            $notification = array(
                'message' => 'Cannot Create',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        $permissions = Module::get();
        return view('role-management.roles.create',compact('permissions'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'modules' => 'required',
        ]);

        Role::create(['name' => $request->input('name')])->modules()->sync($request->input('modules'),[]);;

        $notification = array(
            'message' =>'Role Create Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('role-management.roles.index')->with($notification);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
//        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
//            ->where("role_has_permissions.role_id",$id)
//            ->get();

        return view('roles.show',compact('role'));
    }


    public function edit($id)
    {
        $role = Role::with('modules')->find($id);
        if($role->name == "Admin"){

            $notification = array(
                'message' =>'This Role Cannot Be Editablke ',
                'alert-type' =>'error'
            );

            return redirect()->back()->with($notification);
        }
        $permissions = Module::get();
        return view('role-management.roles.create',compact('role','permissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'modules' => 'required',
        ]);

        $role = Role::find($id);

        if($role->name == "Admin"){

            $notification = array(
                'message' =>'This Role Cannot Be Editablke ',
                'alert-type' =>'warning'
            );

            return redirect()->back()->with($notification);
        }

        $role->name = $request->input('name');
        $role->save();

        $role->modules()->sync($request->input('modules'));

        $notification = array(
            'message' =>'Successfull ',
            'alert-type' =>'success'
        );

        return redirect()->route('role-management.roles.index')->with($notification);
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();

        $notification = array(
            'message' =>'Successfull ',
            'alert-type' =>'success'
        );
        return redirect()->route('roles.index')
                        ->with($notification);
    }

    public function delete($id){

        if(Auth::user()->role_name != "Admin"){
            $notification = array(
                'message' => 'Cannot Delete',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        
        $role = Role::find($id);
        $role->delete();

        $notification = array(
            'message' =>'Role Delete Succesfully',
            'alert-type' =>'success'
        );
        return redirect()->back()
            ->with($notification);

    }
}
