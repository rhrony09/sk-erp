<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage constant category'))
        {
            $attributes = Attribute::with('values')->get();

            return view('attribute.index', compact('attributes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create constant category'))
        {
            return view('attribute.create');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create constant category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:200',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $attribute             = new Attribute();
            $attribute->name       = $request->name;

            $attribute->save();

            return redirect()->route('attributes.all')->with('success', __('Attribute created successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {

        if(\Auth::user()->can('edit constant category'))
        {
            $attribute = Attribute::find($id);

            return view('attribute.edit', compact('attribute'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('create constant category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:200',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $attribute             =  Attribute::find($id);
            $attribute->name       = $request->name;

            $attribute->save();

            return redirect()->route('attributes.all')->with('success', __('Attribute updated successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function delete($id)
    {
        $attribute             =  Attribute::find($id);

        $attribute->delete();

        return redirect()->route('attributes.all')->with('success', __('Attribute deleted successfully.'));
    }

    public function valueIndex($id)
    {
        if(\Auth::user()->can('manage constant category'))
        {
            $attribute = Attribute::where('id', $id)->with('values')->first();
            return view('attributeValue.index', compact('attribute'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function valueCreate($current = null)
    {
        if(\Auth::user()->can('create constant category'))
        {
            $attributes = Attribute::all();
            return view('attributeValue.create', compact('attributes','current'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function valueStore(Request $request)
    {
        if(\Auth::user()->can('create constant category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'attribute_id' => 'required',
                    'name' => 'required|max:200',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $attribute             = new AttributeValue();
            $attribute->name       = $request->name;
            $attribute->attribute_id       = $request->attribute_id;

            $attribute->save();

            return redirect()->back()->with('success', __('Attribute Value created successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function valueEdit($id)
    {

        if(\Auth::user()->can('edit constant category'))
        {

            $attributes = Attribute::all();
            $attributeValue = AttributeValue::find($id);

            return view('attributeValue.edit', compact('attributes','attributeValue'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function valueUpdate(Request $request, $id)
    {
        if(\Auth::user()->can('create constant category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'attribute_id' => 'required',
                    'name' => 'required|max:200',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $attribute             = AttributeValue::find($id);
            $attribute->name       = $request->name;
            $attribute->attribute_id       = $request->attribute_id;

            $attribute->save();

            return redirect()->back()->with('success', __('Attribute Value created successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function valueDelete($id)
    {
        $attributevalue             =  AttributeValue::find($id);

        $attributevalue->delete();

        return redirect()->back()->with('success', __('Attribute Value deleted successfully.'));
    }

}
