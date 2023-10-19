<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InstituteBranch;
use App\Models\Role;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash as FacadesHash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('institute_id', Helper::getInstituteId())->get();
        return view('role-management.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role_name != "Admin"){
            $notification = array(
                'message' => 'Cannot Create',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        $roles = Role::all();
        $branches = InstituteBranch::all();
        return view('role-management.users.create', compact('roles', 'branches'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'role_id' => 'required',
            'institute_branch_id' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',
        ]);

        $input = $request->all();
        $input['password'] = FacadesHash::make($input['password']);
        $input['institute_id'] = Helper::getInstituteId();

        $user = User::create($input);


        $notification = array(
            'message' => 'Action Successfull ',
            'alert-type' => 'success'
        );

        return redirect()->route('role-management.users.index')
            ->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role_name != "Admin"){
            $notification = array(
                'message' => 'Cannot Edit',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $user  = User::find($id);
        $roles = Role::all();
        $branches = InstituteBranch::all();
        return view('role-management.users.create', compact('user', 'roles', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'role_id' => 'required',
            'institute_branch_id' => 'required',
        ]);

        $input = $request->all();


        $user = User::find($id);
        $user->update($input);



        $notification = array(
            'message' => 'Action Successfull ',
            'alert-type' => 'success'
        );
        return redirect()->route('role-management.users.index')
            ->with($notification);
    }


    public function delete($id)
    {
        if(Auth::user()->role_name != "Admin"){
            $notification = array(
                'message' => 'Cannot Update',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        User::find($id)->delete();

        $notification = array(
            'message' => 'Action Successfull ',
            'alert-type' => 'success'
        );
        return redirect()->route('role-management.users.index')
            ->with($notification);
    }


    public function passwordReset(Request $request)
    {
        if(Auth::user()->role_name != "Admin"){
            $notification = array(
                'message' => 'Cannot Update',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }else{

            $user = User::find($request->id);
            $user->update([
                'password' => FacadesHash::make($request->password)
            ]);
    
            $notification = array(
                'message' => 'Password Update Successfully',
                'alert-type' => 'success'
            );
        }

        return redirect()->back()->with($notification);
    }
}
