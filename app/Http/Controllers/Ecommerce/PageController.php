<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\ProductServiceOrder;
use Auth;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $userId = Auth::id();
        
        $featuredCategories = ProductServiceCategory::where('type','product & service')->where('is_featured', 1)->select('id','name', 'slug','thumbnail','slug')->with('products:id,category_id')->get(); 
        $featuredProducts = ProductService::where('type', 'product')
    ->where('is_featured', 1)
    ->select('id', 'category_id', 'description', 'name', 'slug', 'pro_image', 'sale_price', 'discount_price', 'created_at')
    ->with(['category:id,name'])
    ->with(['wishlist' => function($query) use ($userId) {
        $query->where('user_id', $userId);
    }])
    ->get();

        return view('ecommerce.home.index', compact('featuredCategories', 'featuredProducts'));
    } 
    
    public function myAccount()
    {
        $orders = ProductServiceOrder::where('user_id', Auth::id())->with('items')->get();
        return view('ecommerce.account.index',compact('orders'));
    }

    public function orderDetails($order_id)
    {
        $order = ProductServiceOrder::where('order_id', $order_id)->with('items')->first();

        return view('ecommerce.account.order-details',compact('order'));
    }
}
