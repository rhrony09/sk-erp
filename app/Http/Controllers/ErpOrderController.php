<?php

namespace App\Http\Controllers;

use App\Models\ProductServiceOrder;
use Illuminate\Http\Request;

class ErpOrderController extends Controller
{
    public function index()
    {
        $orders = ProductServiceOrder::all();
        return view('erp_order.index', compact('orders'));
    }

    public function show($id)
    {
        $order = ProductServiceOrder::find($id);
        return view('erp_order.details', compact('order'));
    }

    public function updateEstimatedDate(Request $request, $id)
    {
        $order = ProductServiceOrder::findOrFail($id);
        $order->estimated_delivery_date = $request->estimated_delivery_date; // Example logic
        $order->save();

        return redirect()->back()->with('success', 'Estimated date updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = ProductServiceOrder::findOrFail($id);
        $order->status = $request->status; // Example logic
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function delete($id)
    {
        $order = ProductServiceOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('erp_order.index')->with('success', 'Order deleted successfully.');
    }
}
