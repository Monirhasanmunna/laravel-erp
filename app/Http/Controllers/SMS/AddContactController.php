<?php

namespace App\Http\Controllers\SMS;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SmsSendJob;
use App\Models\Contact;
use App\Models\ContactNumber;
use App\Models\InstituteBranch;
use App\Models\SmsTemplates;
use Illuminate\Http\Request;
use SebastianBergmann\Template\Template;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ManualSmsNumberImport;

class AddContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::all();
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.manual-sms.index',compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.manual-sms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = Contact::create([
            'file_name' => $request->file_name
        ]);

        foreach ($request->numbers as $key => $number) {
            ContactNumber::create([
                'contact_id'    => $contact->id,
                'number'        => $number
            ]);
        }
        
        //notification
        $notification = array(
            'message' =>'Contact Add Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('sms.contact.index')->with($notification);
    }

    public function getNumber($id)
    {
        return Contact::with('numbers')->find($id);
    }

    public function smsForm()
    {
        $templates = SmsTemplates::all();
        $contacts = Contact::all();
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.manual-sms.send-sms',compact('templates','contacts'));
    }

    public function smsSend(Request $request)
    {
        $request->validate([
            'contact_id' => 'required',
            'content'    => 'required'
        ]);

       // return $request->all();
        $numbers = Contact::where('id',$request->contact_id)->first()->numbers;
        $content = $request->content;
        @$stockSms = Helper::smsBalance()->currentBalance;
        $branch    = InstituteBranch::find(Helper::getBranchId());

        if($content && @$branch){
            if($request->check != ''){
                if($stockSms > 0){
                    foreach ($request->check as $number) {
                        SmsSendJob::dispatch('Student Sms Send',Helper::getInstituteId(),$branch->id,$number,$content);
                    }
    
                    //notification
                    $notification = array(
                        'message' =>'Sms Send Successfully',
                        'alert-type' =>'success'
                    );
                }else {
                    //notification
                    $notification = array(
                        'message' =>'Not Enough SMS',
                        'alert-type' =>'error'
                    );
                }
            }else{
                 //notification
                 $notification = array(
                    'message' =>'Number Not Selected',
                    'alert-type' =>'error'
                );
            }
        }else{
            //notification
            $notification = array(
                'message' =>'SMS Content is Empty',
                'alert-type' =>'error'
            );
        }
        return redirect()->back()->with($notification);
    }  

    public function ExcelImport()
    {
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.manual-sms.import-number');
    }


    public function ExcelImportSmsList(Request $request)
    {
       $file = $request->file('files_excel');

       $excel = Excel::toArray(new ManualSmsNumberImport, $file);
       $data = $excel[0];
       $numbers = [];
       for ($i=1; $i < count($data); $i++) { 
           array_push($numbers,$data[$i][0]);
       }

       $templates = SmsTemplates::all();
       return view($this->backendTemplate['template']['path_name'].'.smsmanagement.manual-sms.import-number-list',compact('numbers','templates'));
    }


    public function ExcelSmsSend(Request $request)
    {
       // return $request->all();
        $content = $request->sms_content;
        @$stockSms = Helper::smsBalance()->currentBalance;
        $branch    = InstituteBranch::find(Helper::getBranchId());

        if($content && @$branch){
            if($request->check != ''){
                if($stockSms > 0){
                    foreach ($request->check as $number) {
                        SmsSendJob::dispatch('Student Sms Send',Helper::getInstituteId(),$branch->id,$number,$content);
                    }
    
                    //notification
                    $notification = array(
                        'message' =>'Sms Send Successfully',
                        'alert-type' =>'success'
                    );
                }else {
                    //notification
                    $notification = array(
                        'message' =>'Not Enough SMS',
                        'alert-type' =>'error'
                    );
                }
            }else{
                 //notification
                 $notification = array(
                    'message' =>'Number Not Selected',
                    'alert-type' =>'error'
                );
            }
        }else{
            //notification
            $notification = array(
                'message' =>'SMS Content is Empty',
                'alert-type' =>'error'
            );
        }
        return redirect()->route('sms.contact.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::find($id);
        return view($this->backendTemplate['template']['path_name'].'.smsmanagement.manual-sms.edit',compact('contact'));
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
        $contact = Contact::find($id);

        $contact->update([
            'file_name' => $request->file_name
        ]);

       $numbers = ContactNumber::where('contact_id',$contact->id)->get();
       foreach ($numbers as $key => $number) {
        $number->delete();
       }

       foreach ($request->numbers as $key => $number) {
        ContactNumber::create([
            'contact_id'    => $contact->id,
            'number'        => $number
        ]);
        }

        //notification
        $notification = array(
            'message' =>'Contact updated successfully',
            'alert-type' =>'success'
        );

        return redirect()->route('sms.contact.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $contact = Contact::find($request->id);
        $contact->delete();

        //notification
        $notification = array(
            'message' =>'Contact deleted successfully',
            'alert-type' =>'success'
        );

        return redirect()->back()->with($notification);
    }
}
