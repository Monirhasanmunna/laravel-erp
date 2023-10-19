<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('template1.accountsmanagement.vendor.index',compact('vendors'));
    }

    public function create()
    {
        return view('template1.accountsmanagement.vendor.create');
    }


    public function store(Request $request){

        $data = $request->all();

        Vendor::create($data);

        //notification
        $notification = array(
            'message' =>'Vendor Create Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('vendor.index')->with($notification);

    }

    public function delete($id){
        Vendor::find($id)->delete();
        //notification
        $notification = array(
            'message' =>'Vendor Delete Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

}
