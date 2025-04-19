<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Compare;
use Auth;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function addToCompare($productId)
    {
        $exisitingCompare = Compare::where('product_id', $productId)->where('user_id', Auth::id())->first();

        if ($exisitingCompare) {
            $exisitingCompare->delete();
        } else {
            $compare = new Compare();

            $compare->user_id = Auth::id();
            $compare->product_id = $productId;

            $compare->save();
        }
    }

    public function getCompareCount()
    {
        $compareCount = Compare::where('user_id', Auth::id())
            ->count();

        return response()->json([
            'success' => true,
            'compareCount' => $compareCount
        ]);
    }

    public function comparePage()
    {
        $compares = Compare::where('user_id', Auth::id())
        ->with([
            'product.attributes' => function ($query) {
                $query->with([
                    'attributeValue' => function ($q) {
                        $q->with('attribute');
                    }
                ]);
            }
        ])
        ->get();
        return view('ecommerce.compare.index', compact('compares'));
    }

    public function deleteCompare($id)
    {
    
        try {
            $compare = Compare::findOrFail($id);
            $compare->delete();
    
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
}
