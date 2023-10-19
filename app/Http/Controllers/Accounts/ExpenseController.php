<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Account;
use App\Models\ChartofAccount;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\Vendor;
use App\Services\TransactionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest('id')->get();
        return view($this->backendTemplate['template']['path_name'].'.accountsmanagement.expense.index', compact('expenses'));
    }
    public function create()
    {
        $accounts = Account::all();
        return view('template1.accountsmanagement.expense.create', compact('accounts'));
    }


    public function store(Request $request)
    {

        Expense::create([
            'invoice_no'   => rand(1000,10000),
            'date'         => $request->date,
            'amount'       => $request->amount,
            'details'      => $request->details,
            'account_id'   => $request->account_id,
            'pay_to'    => $request->pay_to,
            'reference_no' => $request->reference_no,
        ]);

        //notification
        $notification = array(
            'message' =>'Expense Create Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('expense.index')->with($notification);
    }

    public function edit($id){
        $expense  = Expense::find($id);
        $accounts = Account::all();

        return view('template1.accountsmanagement.expense.create', compact('expense','accounts'));
    }

    public function update(Request $request,$id){
        $expense  = Expense::find($id);
        $expense->update([
            'date'         => $request->date,
            'amount'       => $request->amount,
            'details'      => $request->details,
            'account_id'   => $request->account_id,
            'pay_to'    => $request->pay_to,
            'reference_no' => $request->reference_no,
        ]);

        //notification
        $notification = array(
            'message' =>'Expense update Successfully ',
            'alert-type' =>'success'
        );
        return redirect()->route('expense.index')->with($notification);
    }

    public function expenseDetails(Request $request)
    {

        $expenseDetails = ExpenseDetail::with('chart_of_account')->where('expense_id', $request->expense_id)->get();
        return response()->json($expenseDetails);
    }


    public function downloadInvoice($id)
    {
        $expense_details = ExpenseDetail::with('chart_of_account')->where('expense_id', $id)->get();
        $pdf = Pdf::loadView($this->backendTemplate['template']['path_name'].'.accountsmanagement.expense.invoice', compact('expense_details'))->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->download('expense.pdf');
    }
}
