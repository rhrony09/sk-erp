<?php

namespace App\Http\Controllers;

use App\Models\ProductService;
use App\Models\Purchase;
use App\Models\Utility;
use App\Models\warehouse;
use App\Models\WarehouseProduct;
use App\Models\WarehouseTransfer;
use DB;
use Illuminate\Http\Request;

class WarehouseTransferController extends Controller
{

    public function index()
    {
        $warehouse_transfers = WarehouseTransfer::with(['product','fromWarehouse'])->get();
        return view('warehouse-transfer.index',compact('warehouse_transfers'));
    }

    public function create()
    {
        $from_warehouses      = warehouse::get();
        $to_warehouses     = warehouse::get()->pluck('name', 'id');
        $to_warehouses->prepend('Select Warehouse', '');
        $ware_pro= WarehouseProduct::join('product_services', 'warehouse_products.product_id', '=', 'product_services.id')
                                ->pluck('name','product_id');
        $ware_pro->prepend('Select products', '');

        return view('warehouse-transfer.create',compact('from_warehouses','to_warehouses','ware_pro'));

    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create warehouse'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'from_warehouse' => 'required',
                    'to_warehouse' => 'required',
                    'product_id' => 'required|array',
                    'product_id.*' => 'required',
                    'quantity' => 'required|array',
                    'quantity.*' => 'required|numeric|min:1',
                    'date' => 'required|array',
                    'date.*' => 'required|date',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $product_ids = $request->product_id;
            $quantities = $request->quantity;
            $dates = $request->date;

            foreach ($product_ids as $index => $product_id) {
                $quantity = $quantities[$index];
                $date = $dates[$index];

                $fromWarehouse = WarehouseProduct::where('warehouse_id', $request->from_warehouse)
                                ->where('product_id', $product_id)
                                ->sum('quantity');
                $product = ProductService::where('id', $product_id)
                                ->first();

                $availableQuantity = $product->quantity - $fromWarehouse;

                if($quantity <= $availableQuantity)
                {
                    $warehouse_transfer                  = new WarehouseTransfer();
                    $warehouse_transfer->from_warehouse  = $request->from_warehouse;
                    $warehouse_transfer->to_warehouse    = $request->to_warehouse;
                    $warehouse_transfer->product_id      = $product_id;
                    $warehouse_transfer->quantity        = $quantity;
                    $warehouse_transfer->date            = $date;
                    $warehouse_transfer->created_by      = \Auth::user()->creatorId();
                    $warehouse_transfer->save();

                    Utility::warehouse_transfer_qty($request->from_warehouse, $request->to_warehouse, $product_id, $quantity);
                }
                else
                {
                    return redirect()->route('warehouse-transfer.index')->with('error', __('Product out of stock for one or more items!'));
                }
            }

            return redirect()->route('warehouse-transfer.index')->with('success', __('Warehouse Transfer(s) successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show()
    {
        return redirect()->route('warehouse-transfer.index');

    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete warehouse'))
        {
            $warehouseTransfer = WarehouseTransfer::find($id);
            // Utility::warehouse_transfer_qty($warehouseTransfer->to_warehouse,$warehouseTransfer->from_warehouse,$warehouseTransfer->product_id,$warehouseTransfer->quantity);

            $warehouseTransfer->delete();

            return redirect()->route('warehouse-transfer.index')->with('success', __('Warehouse Transfer successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getproduct(Request $request)
    {
        if($request->warehouse_id == 0)
        {
            // Get all products with their warehouse quantity sums
            $products = ProductService::leftJoin(
                \DB::raw("(SELECT product_id, SUM(quantity) as warehouse_quantity FROM warehouse_products GROUP BY product_id) as wp"),
                'product_services.id', '=', 'wp.product_id'
            )
            ->select(
                'product_services.id',
                'product_services.name',
                'product_services.quantity',
                \DB::raw('COALESCE(wp.warehouse_quantity, 0) as warehouse_quantity')
            )
            ->get();
            
            $ware_products = [];
            
            foreach($products as $product) {
                // Calculate available quantity
                $availableQuantity = $product->quantity - $product->warehouse_quantity;
                
                // Only include products with available quantity > 0
                if($availableQuantity > 0) {
                    $ware_products[$product->id] = $product->name;
                }
            }
            
            $to_warehouses = warehouse::get()->pluck('name', 'id');
        }
        else
        {
            $ware_products = WarehouseProduct::join('product_services', 'warehouse_products.product_id', '=', 'product_services.id')
                ->where('warehouse_id', $request->warehouse_id)
                ->get()
                ->pluck('name', 'product_id')->toArray();
            $to_warehouses = warehouse::where('id','!=',$request->warehouse_id)->get()->pluck('name', 'id');
        }
        
        $result = [];
        $result['ware_products'] = $ware_products;
        $result['to_warehouses'] = $to_warehouses;
        return response()->json($result);
    }

    public function getquantity(Request $request)
    {
        if($request->product_id == 0)
        {
            $pro_qty = WarehouseProduct::get()->pluck('quantity', 'product_id')->toArray();
        }
        else
        {
            $pro_qty = WarehouseProduct::where('product_id', $request->product_id)
                        ->get()->pluck('quantity');
    
        }
        return response()->json($pro_qty);
    }
}
