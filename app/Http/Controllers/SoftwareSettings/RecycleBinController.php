<?php

namespace App\Http\Controllers\SoftwareSettings;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class RecycleBinController extends Controller
{
    public function index()
    {
        return view('template1.software-settings.recycle-bin.index');
    }

    public function getDataByModel(Request $request)
    {

        $model = $request->modelName;

        if ($model === '\App\Models\Student') {
            $data = $model::with('ins_class', 'shift', 'section', 'category', 'group')->onlyTrashed()
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id'       => $item->id,
                            'id_no'    => $item->id_no,
                            'roll_no'  => $item->roll_no,
                            'name'     => $item->name,
                            'class'    => $item->ins_class->name ?? '',
                            'shift'    => $item->shift->name ?? '',
                            'section'  => $item->section->name ?? '',
                            'category' => $item->category->name ?? '',
                            'group'    => $item->group->name ?? '',
                        ];
                    });
        }
        elseif($model === '\App\Models\Teacher'){
            $data = $model::onlyTrashed()->get(['id','id_no','name','mobile_number']);
        }

        return $data;
    }


    public function restoreDataByModel(Request $request)
    {

        $model = $request->model;

        $data = $model::withTrashed()->find($request->id);
        $success = $data->restore();

        if ($success) {
            return response()->json(['message' => "Data Restore Successfully", 'status' => 200]);
        } else {
            return response()->json(['message' => "Data Not Restored", 'status' => 404]);
        }
    }
}
