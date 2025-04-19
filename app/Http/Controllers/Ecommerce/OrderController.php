<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductServiceOrder;
use App\Models\ProductServiceOrderItem;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class OrderController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        if($carts->isEmpty()) {
            return redirect('/')->with('error', 'Your cart is empty.');
        }

        return view('ecommerce.checkout.checkout', compact('carts'));
    }

    public function makeOrder(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_address' => 'required',
            'billing_city_name' => 'required',
            'billing_province_name' => 'required', 
            'billing_zip_code' => 'required',
            'billing_country_id' => 'required',
            // Add shipping validation if needed
        ]);

        $order = new ProductServiceOrder();
        $carts = Cart::where('user_id', Auth::id())->get();

        $orderId = $this->generateUniqueOrderId();
        $order->user_id = Auth::id();
        $order->order_id = $orderId;
        
        // Fix field name mismatches
        $order->billing_name = $request->billing_first_name . ' ' . $request->billing_last_name;
        $order->billing_email = $request->billing_email;
        $order->billing_phone = $request->billing_tel_number; // Changed from billing_phone
        $order->billing_address = $request->billing_address;
        $order->billing_city = $request->billing_city_name; // Changed from billing_city
        $order->billing_state = $request->billing_province_name; // Changed from billing_state
        $order->billing_zip = $request->billing_zip_code; // Changed from billing_zip
        $order->billing_country = $request->billing_country_id; // Changed from billing_country
        
        // Only set shipping fields if shipping to different address is checked
        if ($request->has('different_shipping')) {
            $order->shipping_name = $request->shipping_first_name . ' ' . $request->shipping_last_name;
            $order->shipping_email = $request->shipping_email_address; // Changed from shipping_email
            $order->shipping_phone = null; // Not in the form
            $order->shipping_address = $request->shipping_address;
            $order->shipping_city = $request->shipping_city_name; // Changed from shipping_city
            $order->shipping_state = $request->shipping_province_name; // Changed from shipping_state
            $order->shipping_zip = $request->shipping_zip_code; // Changed from shipping_zip
            $order->shipping_country = $request->shipping_country_id; // Changed from shipping_country
        } else {
            // Use billing address as shipping address
            $order->shipping_name = $request->billing_first_name . ' ' . $request->billing_last_name;
            $order->shipping_email = $request->billing_email;
            $order->shipping_phone = $request->billing_tel_number;
            $order->shipping_address = $request->billing_address;
            $order->shipping_city = $request->billing_city_name;
            $order->shipping_state = $request->billing_province_name;
            $order->shipping_zip = $request->billing_zip_code;
            $order->shipping_country = $request->billing_country_id;
        }
        
        $order->shipping_method = 'standard'; // Add payment method
        $order->order_note = $request->order_note; // Add order notes
        $order->estimated_delivery_date = Carbon::now()->addDays(7); // Example logic for estimated delivery date
        
        $order->subtotal = $carts->sum(function($cart) {
            $price = $cart->product->discount_price ?? $cart->product->sale_price;
            return $cart->quantity * $price;
        });
        
        $order->discount = 0;
        $order->tax = 0;
        $order->shipping_cost = 0;
        $order->total = $carts->sum(function($cart) {
            $price = $cart->product->discount_price ?? $cart->product->sale_price;
            return $cart->quantity * $price;
        });

        $order->save();

        foreach($carts as $cart) {
            $orderItem = new ProductServiceOrderItem();

            $orderItem->product_service_order_id = $order->id;
            $orderItem->product_service_id = $cart->product_id;
            $orderItem->unit_price = $cart->product->discount_price ?? $cart->product->sale_price;
            $orderItem->quantity = $cart->quantity;
            $orderItem->price = $cart->quantity * ($cart->product->discount_price ?? $cart->product->sale_price);

            $orderItem->save();
            $cart->delete();
        }

        return redirect('/')->with('success', 'Order placed successfully! Your order ID is: ' . $orderId);
    }

    /**
     * Generate a unique 8-character alphanumeric ID for order_id
     * 
     * @return string
     */
    private function generateUniqueOrderId()
    {
        // Characters to use (alphanumeric without confusing characters like 0/O, 1/I/l)
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        
        $maxAttempts = 10; // Prevent infinite loop
        $attempt = 0;
        
        do {
            $orderId = '';
            
            // Generate random 8-character string
            for ($i = 0; $i < 8; $i++) {
                $randomIndex = random_int(0, strlen($characters) - 1);
                $orderId .= $characters[$randomIndex];
            }
            
            // Check if this ID already exists in the database
            $exists = ProductServiceOrder::where('order_id', $orderId)->exists();
            
            $attempt++;
            
            // If unique or max attempts reached, break the loop
            if (!$exists || $attempt >= $maxAttempts) {
                break;
            }
        } while (true);
        
        // If we couldn't generate a unique ID after max attempts, use a fallback method
        if ($exists) {
            // Use timestamp to ensure uniqueness
            $timestamp = now()->format('mdHis'); // month, day, hour, minute, second
            $random = strtoupper(Str::random(2));
            $orderId = $random . substr($timestamp, -6); // Combine for 8 chars
        }
        
        return $orderId;
    }
}
