<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        // Check if the product already exists in the cart
        $cartItem = Cart::where('product_id', $productId)
            ->where(function ($query) use ($request) {
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', $request->session()->getId());
                }
            })
            ->first();

        if ($cartItem) {
            // If product exists, increment quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Otherwise, create new cart item
            $cart = new Cart();

            if (Auth::check()) {
                $cart->user_id = Auth::id();
            } else {
                $cart->session_id = $request->session()->getId();
            }

            $cart->product_id = $productId;
            $cart->quantity = $quantity;
            $cart->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully'
        ]);
    }

    public function getCartCount()
    {
        $cartCount = Cart::where('user_id', Auth::id())
            ->orWhere('session_id', request()->session()->getId())
            ->sum('quantity');

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount
        ]);
    }

    public function cartPage()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->orWhere('session_id', request()->session()->getId())->with('product')->get();
        return view('ecommerce.cart.cart', compact('carts'));
    }

    public function cartPageData()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->orWhere('session_id', request()->session()->getId())->with('product')->get();

        return response()->json([
            'carts' => $carts
        ]);
    }

    public function deleteCart($id)
    {
        $cart = Cart::find($id);

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product delete from cart successfully'
        ]);
    }

    public function increaseCartQty($id)
    {
        $cart = Cart::find($id);

        $cart->quantity += 1;

        $cart->update();

        return response()->json([
            'success' => true,
            'message' => 'Cart quantity updated successfully'
        ]);
    }

    public function decreaseCartQty($id)
    {
        $cart = Cart::find($id);
        if ($cart->quantity > 1) {
            $cart->quantity -= 1;

            $cart->update();

            return response()->json([
                'success' => true,
                'message' => 'Cart quantity updated successfully'
            ]);
        }
    }

    public function cartNavData()
    {
        $carts = Cart::where('user_id', Auth::id())
            ->orWhere('session_id', request()->session()->getId())->with('product')->take(3)->get();

        return response()->json([
            'carts' => $carts
        ]);
    }
}