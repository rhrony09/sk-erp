<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage branch'))
        {
            $branches = Branch::get();

            return view('branch.index', compact('branches'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create branch'))
        {
            return view('branch.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create branch'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $branch             = new Branch();
            $branch->name       = $request->name;
            $branch->created_by = \Auth::user()->creatorId();
            $branch->save();

            return redirect()->route('branch.index')->with('success', __('Branch  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Branch $branch)
    {
        return redirect()->route('branch.index');
    }

    public function edit(Branch $branch)
    {
        if(\Auth::user()->can('edit branch'))
        {
            if($branch->created_by == \Auth::user()->creatorId())
            {

                return view('branch.edit', compact('branch'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Branch $branch)
    {
        if(\Auth::user()->can('edit branch'))
        {
            if($branch->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $branch->name = $request->name;
                $branch->save();

                return redirect()->route('branch.index')->with('success', __('Branch successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Branch $branch)
    {
        if(\Auth::user()->can('delete branch'))
        {
            if($branch->created_by == \Auth::user()->creatorId())
            {
                $branch->delete();

                return redirect()->route('branch.index')->with('success', __('Branch successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getdepartment(Request $request)
    {

        if($request->branch_id == 0)
        {
            $departments = Department::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $departments = Department::where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function getemployee(Request $request)
    {
        if(in_array('0', $request->department_id))
        {
            $employees = Employee::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $employees = Employee::whereIn('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($employees);
    }

    public function allBranch()
    {
        if(\Auth::user()->can('manage global branches')){
            $branches = Branch::with(['employees', 'warehouses.warehouseProducts', 'warehouses.poses.posPayment'])->get();
            
            $branches = $branches->map(function ($branch) {
                $branch->totalDiscount = $branch->warehouses
                    ->flatMap(function ($warehouse) {
                        return $warehouse->poses ?? collect();
                    })
                    ->filter(function ($pos) {
                        return $pos->posPayment !== null;
                    })
                    ->sum(function ($pos) { 
                        return $pos->posPayment->discount_amount ?? 0;
                    });
                
                return $branch;
            });

            return view('branch.all', compact('branches'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function singleBranch($id)
    {
        $branch = Branch::where('id',$id)->with(['employees', 'warehouses.warehouseProducts.stockProduct', 'warehouses.poses.posPayment', 'warehouses.poses.items'])->orderBy('created_at', 'desc')->first();
        $totalDiscount = $branch->warehouses
        ->flatMap(function ($warehouse) {
            return $warehouse->poses;
        })
        ->filter(function ($pos) {
            return $pos->posPayment !== null;
        })
        ->map(function ($pos) {
            return $pos->posPayment->discount_amount ?? 0;
        })
        ->sum();

        return view('branch.statistic', compact('branch','totalDiscount'));
    }
}
