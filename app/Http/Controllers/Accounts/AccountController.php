<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return view('template1.accountsmanagement.account.index',compact('accounts'));
    }

    public function create()
    {
        return view('template1.accountsmanagement.account.create');
    }

    public function store(Request $request){

        $data = $request->all();

        Account::create($data);

        //notification
        $notification = array(
            'message' =>'Account Create Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('account.index')->with($notification);

    }

    public function delete($id){
        Account::find($id)->delete();
        //notification
        $notification = array(
            'message' =>'Account Delete Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }
}
