<?php

namespace App\Http\Controllers;

use App\Models\TerminationType;
use Illuminate\Http\Request;

class TerminationTypeController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage termination type'))
        {
            $terminationtypes = TerminationType::get();

            return view('terminationtype.index', compact('terminationtypes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create termination type'))
        {
            return view('terminationtype.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create termination type'))
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

            $terminationtype             = new TerminationType();
            $terminationtype->name       = $request->name;
            $terminationtype->created_by = \Auth::user()->creatorId();
            $terminationtype->save();

            return redirect()->route('terminationtype.index')->with('success', __('TerminationType  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(TerminationType $terminationtype)
    {
        return redirect()->route('terminationtype.index');
    }

    public function edit(TerminationType $terminationtype)
    {
        if(\Auth::user()->can('edit termination type'))
        {
            if($terminationtype->created_by == \Auth::user()->creatorId())
            {

                return view('terminationtype.edit', compact('terminationtype'));
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

    public function update(Request $request, TerminationType $terminationtype)
    {
        if(\Auth::user()->can('edit termination type'))
        {
            if($terminationtype->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',

                                   ]
                );

                $terminationtype->name = $request->name;
                $terminationtype->save();

                return redirect()->route('terminationtype.index')->with('success', __('TerminationType successfully updated.'));
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

    public function destroy(TerminationType $terminationtype)
    {
        if(\Auth::user()->can('delete termination type'))
        {
            if($terminationtype->created_by == \Auth::user()->creatorId())
            {
                $terminationtype->delete();

                return redirect()->route('terminationtype.index')->with('success', __('TerminationType successfully deleted.'));
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
}
