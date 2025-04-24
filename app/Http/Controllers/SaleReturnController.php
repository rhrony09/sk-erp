<?php

namespace App\Http\Controllers;

use App\Models\Pos;
use App\Models\ProductService;
use App\Models\SaleReturn;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;

class SaleReturnController extends Controller
{

    public function index()
    {
        $saleReturns = SaleReturn::with('product', 'pos.customer')->get();
        return view('sale_return.index', compact('saleReturns'));
    }

    public function store(Request $request, $product_id, $pos_id)
    {
        $validated = $request->validate([
            'return_quantity' => 'required|integer|min:1',
            'return_details' => 'nullable|string|max:1000',
            'product_condition' => 'required|in:unopened,opened,used',
        ]);

        $pos = Pos::findOrFail($pos_id);

        SaleReturn::create([
            'pos_id' => $pos_id,
            'product_id' => $product_id,
            'customer_id' => $pos->customer_id,
            'quantity' => $validated['return_quantity'],
            'reason' => $validated['return_details'],
            'condition' => $validated['product_condition'],
        ]);

        return redirect()->back()->with('success', 'Products returned successfully.');
    }

    public function updateApprove($id)
    {
        $saleReturn = SaleReturn::findOrFail($id);

        // Toggle approval
        $saleReturn->is_approved = !$saleReturn->is_approved;
        $saleReturn->approved_at = $saleReturn->is_approved ? now() : null;
        $saleReturn->save();

        $war_id = optional($saleReturn->pos)->warehouse_id;
        // If now approved, update warehouse quantity
        if ($saleReturn->is_approved) {

            if ($war_id) {
                $warehouseProduct = WarehouseProduct::firstOrCreate(
                    [
                        'product_id' => $saleReturn->product_id,
                        'warehouse_id' => $war_id
                    ],
                    [
                        'quantity' => 0
                    ]
                );

                $warehouseProduct->decrement('quantity', $saleReturn->quantity);

                $product = ProductService::find($saleReturn->product_id);
                if ($product) {
                    $product->decrement('quantity', $saleReturn->quantity);
                    $product->save();
                }
            }
        }else{
            if ($war_id) {
                $warehouseProduct = WarehouseProduct::firstOrCreate(
                    [
                        'product_id' => $saleReturn->product_id,
                        'warehouse_id' => $war_id
                    ],
                    [
                        'quantity' => 0
                    ]
                );

                $warehouseProduct->increment('quantity', $saleReturn->quantity);

                $product = ProductService::find($saleReturn->product_id);
                if ($product) {
                    $product->increment('quantity', $saleReturn->quantity);
                    $product->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Sale return approval status updated successfully.');
    }

    public function delete($id)
    {
        $saleReturn = SaleReturn::findOrFail($id);
        $saleReturn->delete();

        return redirect()->back()->with('success', 'Sale return deleted successfully.');
    }

}
