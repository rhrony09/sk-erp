<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\EmployeeServiceNotification;
use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\Employee;
use App\Models\ProductService;
use App\Models\ProvidentFund;
use App\Models\ProvidentFundTransaction;
use App\Models\ServiceProduct;
use App\Models\User;
use Google\Service\ServiceControl\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProvidentFundController extends Controller
{
    public function index(Request $request)
    {

        if (\Auth::user()->can('manage provident fund')) {

            if (\Auth::user()->type == 'Employee' || \Auth::user()->type == 'accountant') {
                $employees = Employee::where('id', '=', \Auth::user()->employee->id)->get();
            } else {
                $employees = Employee::all();
            }


            return view('provident_fund.index', compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function fund_initiate($id)
    {
        if (\Auth::user()->can('initiate provident fund')) {
            $employee = Employee::find($id);

            if ($employee->provident_fund) {
                return response()->json(['error' => __('Already Initiated.')], 401);
            }

            if (!$employee->salary) {
                return response()->json(['error' => __('Please Set Employee Salary First.')], 401);
            }

            return view('provident_fund.initiate', compact('employee'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('initiate provident fund')) {

            $rules = [
                'employee_id' => 'required',
                'contribution_rate' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('provident_fund.index')->with('error', $messages->first());
            }


            $employee = Employee::find($request->employee_id);

            $provident_fund                      = new ProvidentFund();
            $provident_fund->employee_id         = $request->employee_id;
            $provident_fund->contribution_rate   = $request->contribution_rate;
            $provident_fund->total_amount        = $employee->salary * ($request->contribution_rate / 100);
            $provident_fund->note                = $request->note;
            $provident_fund->save();

            $provident_fund_transaction                    = new ProvidentFundTransaction();
            $provident_fund_transaction->provident_fund_id = $provident_fund->id;
            $provident_fund_transaction->transaction_type  = 1;
            $provident_fund_transaction->amount            = $provident_fund->total_amount;
            $provident_fund_transaction->status            = 1;
            $provident_fund_transaction->note              = 'PF Initiated for ' . $employee->name;
            $provident_fund_transaction->save();

            return redirect()->route('provident_fund.index')->with('success', __('Provident Fund successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function add_transaction($id)
    {
        if (\Auth::user()->can('create provident fund')) {
            $employee = Employee::find($id);
            $provident_fund = $employee->provident_fund;

            return view('provident_fund.add_transaction', compact('employee', 'provident_fund'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store_transaction(Request $request)
    {

        if (\Auth::user()->can('create provident fund')) {

            $rules = [
                'employee_id' => 'required',
                'transaction_type' => 'required|not_in:0',
                'amount' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('provident_fund.index')->with('error', $messages->first());
            }


            $employee = Employee::find($request->employee_id);


            $provident_fund_transaction                    = new ProvidentFundTransaction();
            $provident_fund_transaction->provident_fund_id = $employee->provident_fund->id;
            $provident_fund_transaction->transaction_type  = $request->transaction_type;
            $provident_fund_transaction->amount            = $request->amount;
            if (Auth::user()->type != 'company') {
                $provident_fund_transaction->status            = 0;
            } else {
                $provident_fund_transaction->status            = 1;
            }

            $provident_fund_transaction->note              = $request->note;
            $provident_fund_transaction->save();

            return redirect()->route('provident_fund.index')->with('success', __('Transaction successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (\Auth::user()->can('edit provident fund')) {
            $provident_fund = ProvidentFund::find($id);
            $employee = $provident_fund->employee;

            return view('provident_fund.edit', compact('provident_fund', 'employee'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit provident fund')) {

            $provident_fund = ProvidentFund::find($id);

            $rules = [
                'employee_id' => 'required',
                'contribution_rate' => 'required|integer|min:5',
                'status' => 'required|not_in:0',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('provident_fund.index')->with('error', $messages->first());
            }

            $provident_fund->contribution_rate         = $request->contribution_rate;
            $provident_fund->status                    = $request->status;
            $provident_fund->note                      = $request->note;
            $provident_fund->save();

            return redirect()->route('provident_fund.index')->with('success', __('Provident Fund successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function transactions($id)
    {
        $employee = Employee::find($id);
        $provident_fund = $employee->provident_fund;
        $transactions =  $provident_fund->provident_fund_transactions;

        return view('provident_fund.transactions', compact('employee', 'provident_fund', 'transactions'));
    }

    public function edit_transaction($id)
    {
        $transaction = ProvidentFundTransaction::find($id);

        return view('provident_fund.edit_transaction', compact('transaction'));
    }

    public function update_transaction(Request $request, $id)
    {

        $transaction = ProvidentFundTransaction::find($id);

        $rules = [
            'transaction_type' => 'required|not_in:0',
            'amount' => 'required',
            'status' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->route('provident_fund.index')->with('error', $messages->first());
        }

        $transaction->transaction_type          = $request->transaction_type;
        $transaction->amount                    = $request->amount;
        $transaction->status                    = $request->status;
        $transaction->note                      = $request->note;
        $transaction->save();

        return redirect()->route('provident_fund.index')->with('success', __('Transaction successfully updated.'));
    }

    public function destroy_transaction($id)
    {
        if (\Auth::user()->can('delete provident fund')) {
            $transaction = ProvidentFundTransaction::find($id);

            $transaction->delete();
            return redirect()->route('provident_fund.index')->with('success', __('Transaction successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
