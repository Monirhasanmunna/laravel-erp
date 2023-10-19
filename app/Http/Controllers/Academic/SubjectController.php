<?php

namespace App\Http\Controllers\Academic;

use App\Helper\Helper;
use App\Http\Controllers\Controller;

use App\Models\ClassSubject;
use App\Models\ExamRoutine;
use App\Models\ExamRoutineSubject;
use App\Models\InsClass;
use App\Models\Student;
use App\Models\StudentSubjectAssign;
use App\Models\Subject;
use App\Models\SubjectType;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index($id)
    {

        $assignSubIds = [];
        $classSubjects = ClassSubject::where('ins_class_id',$id)->get();
        if($classSubjects->count() > 0){
            foreach($classSubjects as $sub){
                array_push($assignSubIds,$sub->subject_id);
            }
        }


        $subjects = Subject::whereNotIn('id',$assignSubIds)->get();



        $subjectTypes = SubjectType::get()->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });

        return view ($this->backendTemplate['template']['path_name'].'.subject.index', compact('subjectTypes','subjects','id'));
    }

    public function subjectCount($id,$classId){
        return ClassSubject::where('ins_class_id',$classId)->where('subject_type_id',$id)->count();
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
        $subject = new Subject();
        $subject->sub_name = $request->name;
        $subject->sub_code = $request->code;

        $subject->save();

        $notification = array(
            'message' =>'Action Successfull ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }


    public function subjectAdd(Request $request)
    {
       foreach ($request->type_id as $typeId){

           $req = "subject_id-".$typeId;
           foreach ($request->$req as $subjectId){
               if(!empty($subjectId)){

                   $classSubject = ClassSubject::insertGetId([
                                      'ins_class_id'    => $request->class_id,
                                      'subject_id'      =>  $subjectId,
                                      'subject_type_id' => $typeId
                                   ]);
                    
                   if($classSubject){
                       $this->assignStudentSubject($classSubject);
                   }

                   //new subject add function call here
                //    if($classSubject){
                //         $this->assignSubjectInExamRoutine($request->class_id, $subjectId);
                //    }

               }
           }
       }

        //notification
        $notification = array(
            'message' =>'Subject Assign Successfully ',
            'alert-type' =>'success'
        );
       return redirect()->route('subject.list',$request->class_id)->with($notification);
    }


    // public function assignSubjectInExamRoutine($classId, $subjectId)
    // {
    //     $class = InsClass::find($classId);
    //     foreach ($class->examRoutines as $key => $routine) {
    //         //new subject in existing routine
    //         ExamRoutineSubject::create([
    //             'exam_routine_id' => $routine->id,
    //             'subject_id'      => $subjectId
    //         ]);
    //     }
    // }


    public function assignStudentSubject($classSubjectId){
        $classSubject = ClassSubject::find($classSubjectId);
        $commonId  = SubjectType::where('name','common')->first();

        if($classSubject->subject_type_id == $commonId->id){
            $students = Student::where('class_id',$classSubject->ins_class_id)->get();
            foreach ($students as $student){
                StudentSubjectAssign::create([
                   'student_id' =>  $student->id,
                   'class_subject_id' =>  $classSubject->id,
                   'subject_type_id' =>  $classSubject->subject_type_id
                ]);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list($id)
    {
        $subjects = InsClass::with('subjects')->find($id);

        $subjectTypes = SubjectType::orderBy('id','ASC')->get();
        $subjectTypeData = [];
        foreach ($subjectTypes as $type){
            $subjectTypeData[$type['name']] = ClassSubject::with('subject')->where('subject_type_id',$type->id)
                                                ->where('ins_class_id',$id)
                                                ->get();
        }

        $class_name = InsClass::findOrfail($id);
        return view($this->backendTemplate['template']['path_name'].'.subject.list',compact('subjectTypeData','id','class_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subject = Subject::findOrfail($id);
        return response()->json($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        Subject::findOrfail($request->sub_id)->update([
            'sub_name'  => $request->name,
            'sub_code'  => $request->code,
            'type'      => $request->type
        ]);

        $notification = array(
            'message' =>'Action Successfull ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      ClassSubject::where('subject_id',$id)->delete();
      $subject = Subject::findOrfail($id)->delete();

      $notification = array(
        'message' =>'Action Successfull ',
        'alert-type' =>'success'
    );
      return response()->json($subject)->with($notification);
    }

    public function delete($id){
        $subject = Subject::find($id);

        if ($subject){
            $subject->delete();
        }

        $notification = array(
            'message' =>'Action Successfull ',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }


    public  function  getSubjects(Request $request)
    {
        $subjects = Subject::get()->map(function ($item) use($request){
            return [
                'id'       => $item->id,
                'sub_name' => $item->sub_name,
                'sub_code' => $item->sub_code,
                'order'    => $item->order,
                'type_id'  => $this->getTypeId($request->class_id,$item->id),
                'checked'  => $this->subjectChecked($request->class_id,$item->id)
            ];
        });
//        $subs             = $subjects->sortBy('order');
        $subs = [];
        $subsOrders = $subjects->sortBy('order');
        foreach ($subsOrders as $sub){
            array_push($subs,$sub);
        }
        $data['subjects'] = collect($subs)->chunk(3);
        $data['subjects_types'] = SubjectType::all();

        return response()->json($data);
    }




    public  function  getSubjectsByType(Request $request)
    {

       $subjectType = SubjectType::with('subjects')->find($request->type_id);

       $ss = $subjectType->subjects->map(function ($item) use($request){
                   return [
                       'id'       => $item->id,
                       'sub_name' => $item->sub_name,
                       'sub_code' => $item->sub_code,
                       'type_id'  => $this->getTypeId($request->class_id,$item->id),
                       'checked'  => $this->subjectChecked($request->class_id,$item->id)
                   ];
               });

       $data['subjects']       = $ss->chunk(3);
       $data['subjects_types'] = SubjectType::all();

       return $data;
    }

    public function subjectChecked($classId,$subId){
        $classSubject = ClassSubject::where('ins_class_id',$classId)->where('subject_id',$subId)->first();
        if($classSubject){
            return true;
        }
        else{
            return  false;
        }
    }
    public function getTypeId($classId,$subId){
        $classSubject = ClassSubject::where('ins_class_id',$classId)->where('subject_id',$subId)->first();
        if($classSubject){
            return $classSubject->subject_type_id;
        }
        else{
            return  null;
        }
    }

    public function deleteClassSubject($id)
    {
      
       $classSubject = ClassSubject::with('assignStudents','markDists')->find($id);

       //dd($classSubject->markDists); 


        if($classSubject->assignStudents->count() > 0){
            $notification = array(
                'message' =>'Subject Assigned To Student',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }
        elseif($classSubject->markDists->count() > 0){
            $notification = array(
                'message' =>'Please Remove Mark Dist',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }
        else{
            $classSubject->delete();
        }



        //notification
        $notification = array(
            'message' =>'Subject Assign Delete Successfully',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    // public function deleteExamRoutineSubject($classSubject)
    // {
    //      //check class has any routine
    //      $class = InsClass::find($classSubject->ins_class_id);
    //      if(isset($class->examRoutines)){
    //         foreach ($class->examRoutines as $key => $routine) {
    //             ExamRoutineSubject::where('exam_routine_id',$routine->id)->where('subject_id',$classSubject->subject_id)->first()->delete();
    //          }
    //      }
    // }

    public function orderSubjects(Request $request){
        foreach ($request->order as $order){
            $classSubject = ClassSubject::find($order['id']);
            $classSubject->update([
                    'order' => $order['position']
                ]);
        }

        return response()->json(['msg'=>'success','status' => 200]);
    }
}
