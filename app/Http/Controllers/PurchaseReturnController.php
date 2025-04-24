<?php

namespace App\Http\Controllers;

use App\Models\ProductService;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Vender;
use App\Models\warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        try {
            // Check for purchase management permission
            if (!Auth::user()->can('manage purchase')) {
                return redirect()->back()->with('error', __('Permission denied. Required permission: manage purchase'));
            }

            $role = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', Auth::user()->id)
                ->where('model_has_roles.model_type', \App\Models\User::class)
                ->select('roles.name', 'roles.id as role_id')
                ->first();

            $branchId = Auth::user()->employee->branch_id ?? null;

            // Get purchases based on branch access
            if ($branchId && isset($role->role_id) && $role->role_id != 10) {
                $purchases = Purchase::with(['vender','category','warehouse'])
                    ->whereHas('warehouse', function($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })->get();
            } else {
                $purchases = Purchase::with(['vender','category','warehouse'])
                    ->where('created_by', Auth::user()->creatorId())
                    ->get();
            }

            // Get purchase returns with relationships
            $purchaseReturns = PurchaseReturn::with(['purchase', 'product', 'warehouse'])
                ->get();

            $products = ProductService::all();
            $venders = Vender::all();
            $warehouses = warehouse::all();

            return view('purchase.return', compact('purchases', 'products', 'venders', 'warehouses', 'purchaseReturns'));
        } catch (\Exception $e) {
            Log::error('Purchase Return Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading purchase return: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = \Validator::make(
                $request->all(),
                [
                    'purchase_id' => 'required',
                    'return_date' => 'required',
                    'product_id' => 'required',
                    'quantity' => 'required|numeric|min:1',
                    'warehouse_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            // Check warehouse stock
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $request->warehouse_id)
                ->where('product_id', $request->product_id)
                ->first();

            if (!$warehouseProduct) {
                return redirect()->back()->with('error', __('Product not found in selected warehouse.'));
            }

            if ($request->quantity > $warehouseProduct->quantity) {
                return redirect()->back()->with('error', __('Return quantity cannot be greater than available quantity in warehouse.'));
            }

            // Create purchase return
            $purchaseReturn = new PurchaseReturn();
            $purchaseReturn->purchase_id = $request->purchase_id;
            $purchaseReturn->product_id = $request->product_id;
            $purchaseReturn->warehouse_id = $request->warehouse_id;
            $purchaseReturn->quantity = $request->quantity;
            $purchaseReturn->return_date = $request->return_date;
            $purchaseReturn->reason = $request->reason;
            $purchaseReturn->save();

            // Update warehouse stock
            $warehouseProduct->quantity -= $request->quantity;
            $warehouseProduct->save();

            // Update product quantity
            $product = ProductService::find($request->product_id);
            if ($product) {
                $product->quantity -= $request->quantity;
                $product->save();
            }

            return redirect()->back()->with('success', __('Purchase return created successfully.'));
        } catch (\Exception $e) {
            Log::error('Purchase Return Store Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error creating purchase return: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $purchaseReturn = PurchaseReturn::findOrFail($id);
            
            // Restore warehouse stock
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $purchaseReturn->warehouse_id)
                ->where('product_id', $purchaseReturn->product_id)
                ->first();

            if ($warehouseProduct) {
                $warehouseProduct->quantity += $purchaseReturn->quantity;
                $warehouseProduct->save();
            }

            // Restore product quantity
            $product = ProductService::find($purchaseReturn->product_id);
            if ($product) {
                $product->quantity += $purchaseReturn->quantity;
                $product->save();
            }

            $purchaseReturn->delete();

            return redirect()->back()->with('success', __('Purchase return deleted successfully.'));
        } catch (\Exception $e) {
            Log::error('Purchase Return Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting purchase return: ' . $e->getMessage());
        }
    }
} 