<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\GoalTracking;
use App\Models\GoalType;
use Illuminate\Http\Request;

class GoalTrackingController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage goal tracking'))
        {
            $user = \Auth::user();
            if($user->type == 'employee')
            {
                $employee      = Employee::where('user_id', $user->id)->first();
                $goalTrackings = GoalTracking::where('branch', $employee->branch_id)->with(['goalType','branches'])->get();
            }
            else
            {
                $goalTrackings = GoalTracking::with(['goalType','branches'])->get();
            }

            return view('goaltracking.index', compact('goalTrackings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create goal tracking'))
        {

            $brances = Branch::get()->pluck('name', 'id');
            $brances->prepend('Select Branch', '');
            $goalTypes = GoalType::get()->pluck('name', 'id');
            $goalTypes->prepend('Select Goal Type', '');
            $status = GoalTracking::$status;

            return view('goaltracking.create', compact('brances', 'goalTypes','status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create goal tracking'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'goal_type' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   'subject' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $goalTracking                     = new GoalTracking();
            $goalTracking->branch             = $request->branch;
            $goalTracking->goal_type          = $request->goal_type;
            $goalTracking->start_date         = $request->start_date;
            $goalTracking->end_date           = $request->end_date;
            $goalTracking->subject            = $request->subject;
            $goalTracking->target_achievement = $request->target_achievement;
            $goalTracking->description        = $request->description;
            $goalTracking->created_by         = \Auth::user()->creatorId();
            $goalTracking->save();

            return redirect()->route('goaltracking.index')->with('success', __('Goal tracking successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(GoalTracking $goalTracking)
    {
        //
    }


    public function edit($id)
    {

        if(\Auth::user()->can('edit goal tracking'))
        {
            $goalTracking = GoalTracking::find($id);
            $brances      = Branch::get()->pluck('name', 'id');
            $brances->prepend('Select Branch', '');
            $goalTypes = GoalType::get()->pluck('name', 'id');
            $goalTypes->prepend('Select Goal Type', '');
            $status = GoalTracking::$status;

            $ratings = json_decode($goalTracking->rating,true);

            return view('goaltracking.edit', compact('brances', 'goalTypes', 'goalTracking', 'ratings','status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit goal tracking'))
        {
            $goalTracking = GoalTracking::find($id);
            $validator    = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'goal_type' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   'subject' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $goalTracking->branch             = $request->branch;
            $goalTracking->goal_type          = $request->goal_type;
            $goalTracking->start_date         = $request->start_date;
            $goalTracking->end_date           = $request->end_date;
            $goalTracking->subject            = $request->subject;
            $goalTracking->target_achievement = $request->target_achievement;
            $goalTracking->status             = $request->status;
            $goalTracking->progress           = $request->progress;
            $goalTracking->description        = $request->description;
            $goalTracking->rating         = json_encode($request->rating, true);
            $goalTracking->rating        = $request->rating;
            $goalTracking->save();

            return redirect()->route('goaltracking.index')->with('success', __('Goal tracking successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function destroy($id)
    {

        if(\Auth::user()->can('delete goal tracking'))
        {
            $goalTracking = GoalTracking::find($id);
            if($goalTracking->created_by == \Auth::user()->creatorId())
            {
                $goalTracking->delete();

                return redirect()->route('goaltracking.index')->with('success', __('GoalTracking successfully deleted.'));
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
