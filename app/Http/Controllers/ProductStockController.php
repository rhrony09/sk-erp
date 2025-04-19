<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ProductService;
use App\Models\ProductStock;
use App\Models\RawMaterial;
use App\Models\Utility;
use App\Models\warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', \Auth::user()->id)
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->select('roles.name', 'roles.id as role_id')
            ->first();
        
        // Get the current user's branch ID
        $branchId = \Auth::user()->employee->branch_id ?? null;

        if(\Auth::user()->can('manage product & service'))
        {
            // Get all products of type 'product'
            $productServices = ProductService::where('type', '=', 'product')->get();
            
            // Get warehouse stock for current branch
            $branchStock = [];
            if ($branchId) {
                $warehouseProducts = WarehouseProduct::with(['stockProduct', 'warehouse'])
                    ->whereHas('warehouse', function ($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->get();
                    
                // Create a map of product_id => quantity for the current branch
                foreach ($warehouseProducts as $wp) {
                    if (isset($branchStock[$wp->product_id])) {
                        $branchStock[$wp->product_id] += $wp->quantity;
                    } else {
                        $branchStock[$wp->product_id] = $wp->quantity;
                    }
                }
            }
            
            // Add branch stock to each product
            foreach ($productServices as $product) {
                $product->branch_quantity = $branchStock[$product->id] ?? 0;
            }
            
            // Sort products - stocked items first
            $productServices = $productServices->sortByDesc('branch_quantity')->values();
            
            $raw_materials = RawMaterial::all();

            return view('productstock.index', compact('productServices', 'raw_materials', 'branchId', 'role'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductStock $productStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', \Auth::user()->id)
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->select('roles.name', 'roles.id as role_id')
            ->first();
        if($role->role_id != 10 && \Auth::user()->employee->branch_id != null){
            $warehouses = warehouse::where('branch_id',\Auth::user()->employee->branch_id)->get();
        }else{
            $warehouses = [];
        }

        $productService = ProductService::find($id);
        if(\Auth::user()->can('edit product & service'))
        {
            return view('productstock.edit', compact('productService','warehouses'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit product & service'))
        {
            $productService = ProductService::find($id);
            $totalQuantityAdded = 0;
            
            // Check if we have warehouse-specific quantities
            $warehouseQuantities = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'quantity-') === 0) {
                    $warehouseId = intval(substr($key, 9)); // Extract warehouse ID from field name
                    if ($value > 0) { // Only process positive quantities
                        $warehouseQuantities[$warehouseId] = $value;
                        $totalQuantityAdded += $value;
                    }
                }
            }
            
            // If we have warehouse quantities, process them
            if (!empty($warehouseQuantities)) {
                foreach ($warehouseQuantities as $warehouseId => $quantity) {
                    // Find existing warehouse product or create new one
                    $warehouseProduct = WarehouseProduct::where('warehouse_id', $warehouseId)
                        ->where('product_id', $id)
                        ->first();
                        
                    if ($warehouseProduct) {
                        // Update existing warehouse product
                        $warehouseProduct->quantity += $quantity;
                        $warehouseProduct->save();
                    } else {
                        // Create new warehouse product
                        $warehouseProduct = new WarehouseProduct();
                        $warehouseProduct->warehouse_id = $warehouseId;
                        $warehouseProduct->product_id = $id;
                        $warehouseProduct->quantity = $quantity;
                        $warehouseProduct->save();
                    }
                    
                    // Product Stock Report for this warehouse
                    $type = 'manually';
                    $type_id = 0;
                    $description = $quantity . ' ' . __('quantity added manually to warehouse ID') . ' ' . $warehouseId;
                    Utility::addProductStock($productService->id, $quantity, $type, $description, $type_id);
                }
            } 
            // If we have a general quantity field
            elseif ($request->has('quantity') && $request->quantity > 0) {
                $totalQuantityAdded = $request->quantity;
                
                // Product Stock Report
                $type = 'manually';
                $type_id = 0;
                $description = $request->quantity . ' ' . __('quantity added manually');
                Utility::addProductStock($productService->id, $request->quantity, $type, $description, $type_id);
            }
            
            // Update the main product quantity
            if ($totalQuantityAdded > 0) {
                $productService->quantity += $totalQuantityAdded;
                $productService->created_by = \Auth::user()->creatorId();
                $productService->save();
            }

            return redirect()->route('productstock.index')->with('success', __('Product quantity updated manually.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductStock $productStock
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductStock $productStock)
    {
        //
    }
}
