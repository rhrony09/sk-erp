<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addWishlist($productId)
    {
        $exisitingWishlist = Wishlist::where('product_id', $productId)->where('user_id', Auth::id())->first();

        if ($exisitingWishlist) {
            $exisitingWishlist->delete();
        } else {
            $wishlist = new Wishlist();

            $wishlist->user_id = Auth::id();
            $wishlist->product_id = $productId;

            $wishlist->save();
        }
    }

    public function getWishlistCount()
    {
        $wishlistCount = Wishlist::where('user_id', Auth::id())
            ->count();

        return response()->json([
            'success' => true,
            'wishlistCount' => $wishlistCount
        ]);
    }

    public function wishlistPage()
    {
        return view('ecommerce.wishlist.index');
    }

    public function getWishlistData()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())->with('product')
            ->get();

        return response()->json([
            'success' => true,
            'wishlists' => $wishlists
        ]);
    }

}
