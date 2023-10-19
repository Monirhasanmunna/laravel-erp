<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::all();
        return view('template1.routinemanagement.period.index', compact('periods'));
    }


    public function create()
    {
        return view('template1.routinemanagement.period.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Period::create($request->all());

        //notification
        $notification = array(
            'message' =>'Period Create Successfully ',
            'alert-type' =>'success'
        );
              
        return redirect()->route('routine.period.index')->with($notification);
    }


    public function edit($id)
    {
        $period = Period::find($id);

        return view('template1.routinemanagement.period.create', compact('period'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $period = Period::find($id);
        $period->update($request->all());

        //notification
        $notification = array(
            'message' =>'Period Update Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('routine.period.index')->with($notification);
    }
}
