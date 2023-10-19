<?php

namespace App\Http\Controllers\WebAdmin;

use App\Http\Controllers\Controller;
use App\Models\SchoolInfo;
use App\Traits\FileSaver;
use Illuminate\Http\Request;

class SchoolInfoController extends Controller
{
    use FileSaver;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $backendTemplate = $this->backendTemplate['template']['path_name'] ;
        $info = SchoolInfo::orderBy('id','desc')->first();
        return view($backendTemplate .'.webadmin.schoolInfo.create',compact('info', 'backendTemplate'));
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
        $request->validate([
            'name'          => 'required',
            'eiin_no'       => 'required',
            'founded_at'    => 'required',
            'address'       => 'required',
            'about'         => 'required',
            'logo'          => 'sometimes',
            'school_photo'  => 'sometimes',
            'favicon'       => 'sometimes',
       ]);


        $info = new SchoolInfo();

        if($request->logo){
            $this->uploadFileLinode($request['logo'],$info,'logo','schoolInfo');
        }else{
            $info->logo = $info->logo ?? 'default.png';
        }

        if($request->school_photo){
            $this->uploadFileLinode($request['school_photo'],$info,'school_photo','schoolInfo');
        }else{
            $info->school_photo = $info->school_photo ?? 'default.png';
        }

        if($request->favicon){
            $this->uploadFileLinode($request['favicon'],$info,'favicon','schoolInfo');
        }else{
            $info->favicon = $info->favicon ?? 'default.png';
        }


        $info->name         = $request->name;
        $info->name2        = $request->name2;
        $info->eiin_no      = $request->eiin_no;
        $info->founded_at   = $request->founded_at;
        $info->about        = $request->about;
        $info->address      = $request->address;
        $info->googlemap    = $request->googlemap;
        $info->save();

        return redirect()->back();
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
        //
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
       //dd($request->all());
       $request->validate([
            'name'          => 'required',
            'eiin_no'       => 'required',
            'founded_at'    => 'required',
            'address'       => 'required',
            'about'         => 'required',
            'logo'          => 'sometimes',
            'school_photo'  => 'sometimes',
            'favicon'       => 'sometimes',
       ]);


        $info = SchoolInfo::findOrfail($id);

        if($request->logo){
            $this->uploadFileLinode($request['logo'],$info,'logo','schoolInfo');
        }else{
            $info->logo = $info->logo;
        }

        if($request->school_photo){
            $this->uploadFileLinode($request['school_photo'],$info,'school_photo','schoolInfo');
        }else{
            $info->school_photo = $info->school_photo;
        }

        if($request->favicon){
            $this->uploadFileLinode($request['favicon'],$info,'favicon','schoolInfo');
        }else{
            $info->favicon = $info->favicon;
        }


        $info->name         = $request->name;
        $info->name2        = $request->name2;
        $info->eiin_no      = $request->eiin_no;
        $info->founded_at   = $request->founded_at;
        $info->about        = $request->about;
        $info->address      = $request->address;
        $info->googlemap    = $request->googlemap;
        $info->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
