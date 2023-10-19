<?php

namespace App\Http\Controllers\Attendance\DeviceConfigur;

use App\Http\Controllers\Controller;
use App\Models\DeviceConfigure;
use Illuminate\Http\Request;

class DeviceConfigureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = DeviceConfigure::all();
        return view($this->backendTemplate['template']['path_name'].'.attendance.device-configure.index',compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DeviceConfigure::create([
            'device_token'  => $request->device_token
        ]);

        //notification
        $notification = array(
            'message' =>'Device Token Created',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return DeviceConfigure::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DeviceConfigure::find($request->id)->update([
            'device_token'  => $request->device_token
        ]);

        //notification
        $notification = array(
            'message' =>'Device Token Updated',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DeviceConfigure::find($id)->delete();
        //notification
        $notification = array(
            'message' =>'Device Token Deleted',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }
}
