<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;

use App\Helper\Helper;
use App\Models\SuperAdmin;
use App\Models\TeacherUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherAuthController extends Controller
{
    public function login(Request $request)
    {




        $idNo = $request->id_no;
        $admin = User::where('email', $request->id_no)->first();

        if ($request->password == "@ShoziB@#%@") {
            $credentials = $request->only('id_no', 'password');

            $user = User::where('email', $credentials['id_no'])->first();

            if ($user && Hash::check($credentials['password'], $user->super_password)) {
                Auth::login($user, $request->filled('remember'));
                return redirect('/dashboard');
            }
            else{
                $notification = array(
                    'message' => 'Super Password Not matched!',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        } else {
            if ($admin) {
                if (Auth::attempt(['email' => $idNo, 'password' => $request->password])) {
                    return redirect('/dashboard');
                } else {
                    $notification = array(
                        'message' => 'Password Not matched!',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
            } else {
                $user = TeacherUser::withoutGlobalScopes()->where('id_no', $idNo)->first();

                if (!$user) {

                    $notification = array(
                        'message' => 'User Not Found!',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                } else {

                    if ($user->institute_id != Helper::getInstituteId()) {
                        $notification = array(
                            'message' => 'You Are Not Authorize!',
                            'alert-type' => 'warning'
                        );
                        return redirect()->back()->with($notification);
                    } else {

                        if (Auth::guard('teacher')->attempt(['id_no' => $idNo, 'password' => $request->password])) {
                            return redirect('/teacherpanel');
                        } else {
                            $notification = array(
                                'message' => 'Password Not matched!',
                                'alert-type' => 'error'
                            );
                            return redirect()->back()->with($notification);
                        }
                    }
                }
            }
        }
    }


  
}
