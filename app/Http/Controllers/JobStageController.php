<?php

namespace App\Http\Controllers;

use App\Models\JobStage;
use Illuminate\Http\Request;

class JobStageController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage job stage'))
        {
            $stages = JobStage::orderBy('order', 'asc')->get();

            return view('jobStage.index', compact('stages'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('jobStage.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create job stage'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $jobStage             = new JobStage();
            $jobStage->title      = $request->title;
            $jobStage->created_by = \Auth::user()->creatorId();
            $jobStage->save();

            return redirect()->back()->with('success', __('Job stage  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(JobStage $jobStage)
    {
        //
    }


    public function edit(JobStage $jobStage)
    {
        return view('jobStage.edit', compact('jobStage'));
    }


    public function update(Request $request, JobStage $jobStage)
    {
        if(\Auth::user()->can('edit job stage'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $jobStage->title      = $request->title;
            $jobStage->created_by = \Auth::user()->creatorId();
            $jobStage->save();

            return redirect()->back()->with('success', __('Job stage  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(JobStage $jobStage)
    {
        if(\Auth::user()->can('delete job stage'))
        {
            if($jobStage->created_by == \Auth::user()->creatorId())
            {
                $jobStage->delete();

                return redirect()->back()->with('success', __('Job stage successfully deleted.'));
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

    public function order(Request $request)
    {
        $post = $request->all();
        foreach($post['order'] as $key => $item)
        {
            $stage        = JobStage::where('id', '=', $item)->first();
            $stage->order = $key;
            $stage->save();
        }
    }
}
