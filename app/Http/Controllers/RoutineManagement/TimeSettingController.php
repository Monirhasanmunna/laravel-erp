<?php

namespace App\Http\Controllers\RoutineManagement;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\RoutinePeriodTimeSetting;
use App\Models\RoutinePeriodTimeSettingDetail;
use App\Models\Session;
use Illuminate\Http\Request;
use SebastianBergmann\Template\Template;

class TimeSettingController extends Controller
{

    public function index()
    {
        $timeSettings = RoutinePeriodTimeSetting::get()
            ->map(function ($item) {
                return [
                    'id'      => $item->id,
                    'session' => $item->session->title,
                    'class'   => $item->class->name . "-" . $item->section->shift->name . "-" . $item->section->name,
                    'category' => $item->category->name,
                    'group' => $item->group->name,
                    'day' => $item->day,
                ];
            });
        //dd($timeSettings);
        return view($this->backendTemplate['template']['path_name'] . '.routinemanagement.time_setting.index', compact('timeSettings'));
    }


    public function create()
    {
        $academic_years = Session::all();
        $days = Helper::getDays();
        $periods = Period::all();
        return view($this->backendTemplate['template']['path_name'] . '.routinemanagement.time_setting.create', compact('academic_years', 'days','periods'));
    }


    public function store(Request $request)
    {
        //return $request->all();

        foreach ($request->days as $day) {

            $timeSetting = RoutinePeriodTimeSetting::create([
                'day' => $day,
                'session_id' => $request->academic_year_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'category_id' => $request->category_id,
                'group_id' => $request->group_id,
            ]);

            foreach ($request->period_id as $key => $periodId) {

                RoutinePeriodTimeSettingDetail::create([
                    "time_setting_id" =>  $timeSetting->id,
                    "period_id"       => $periodId,
                    "start_time"      => $request->start_time[$key],
                    "end_time"        => $request->end_time[$key],
                ]);
            }
        }

        //notification
        $notification = array(
            'message' => 'Time Setup Successfully ',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $timeSetting = RoutinePeriodTimeSetting::with('details')->find($id);
        if($timeSetting->details){
               //notification
                $notification = array(
                    'message' => 'Time Settings Already Assigned In Routine',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
        }
        $periods     = Period::all();
        return view('template1.routinemanagement.time_setting.edit', compact('timeSetting','periods'));
    }


    public function update(Request $request, $id)
    {
        $periodTime = RoutinePeriodTimeSetting::find($id);

        RoutinePeriodTimeSettingDetail::where('time_setting_id', $id)->delete();
        
        foreach ($request->period_id as $key => $periodId) {

            RoutinePeriodTimeSettingDetail::create([
                "time_setting_id" => $id,
                "period_id"       => $periodId,
                "start_time"      => $request->start_time[$key],
                "end_time"        => $request->end_time[$key],
            ]);
        }
        

         //notification
         $notification = array(
            'message' => 'Time Setup Successfully ',
            'alert-type' => 'success'
        );
        return redirect()->route('routine.time-setting.index')->with($notification);
    }


    public function destroy($id)
    {
        //
    }
}
