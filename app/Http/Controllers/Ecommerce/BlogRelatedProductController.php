<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogRelatedProduct;
use App\Models\ProductService;
use Illuminate\Http\Request;

class BlogRelatedProductController extends Controller
{
    /**
     * Display a page to manage products for a blog
     */
    public function manageBlogProducts($blogId)
    {
        $blog = Blog::findOrFail($blogId);
        $productServices = ProductService::where('status', 'active')->get();
        $relatedProducts = $blog->relatedProducts;
        
        return view('blogs.manage-products', compact('blog', 'productServices', 'relatedProducts'));
    }
    
    /**
     * Add a product to a blog's related products list
     */
    public function addProduct(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'product_id' => 'required|exists:product_services,id',
            'order' => 'nullable|integer'
        ]);
        
        // Check if the relation already exists
        $exists = BlogRelatedProduct::where('blog_id', $request->blog_id)
            ->where('product_id', $request->product_id)
            ->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'This product is already related to the blog'
            ], 422);
        }
        
        // Get highest order if not provided
        if (!$request->has('order')) {
            $maxOrder = BlogRelatedProduct::where('blog_id', $request->blog_id)
                ->max('order');
            $order = is_null($maxOrder) ? 0 : $maxOrder + 1;
        } else {
            $order = $request->order;
        }
        
        // Create the relation
        $relatedProduct = BlogRelatedProduct::create([
            'blog_id' => $request->blog_id,
            'product_id' => $request->product_id,
            'order' => $order
        ]);
        
        $product = ProductService::find($request->product_id);
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product added to related products',
                'related_product' => $relatedProduct,
                'product' => $product
            ]);
        }
        
        return redirect()->back()->with('success', 'Product added to related products');
    }
    
    /**
     * Remove a product from a blog's related products list
     */
    public function removeProduct(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'product_id' => 'required|exists:product_services,id'
        ]);
        
        $deleted = BlogRelatedProduct::where('blog_id', $request->blog_id)
            ->where('product_id', $request->product_id)
            ->delete();
            
        if (!$deleted) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Related product not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Related product not found');
        }
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from related products'
            ]);
        }
        
        return redirect()->back()->with('success', 'Product removed from related products');
    }
    
    /**
     * Update the order of related products
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:blog_related_products,id',
            'items.*.order' => 'required|integer'
        ]);
        
        foreach ($request->items as $item) {
            BlogRelatedProduct::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Related products order updated'
        ]);
    }
    
    /**
     * Get related products for a blog
     */
    public function getRelatedProducts($blogId)
    {
        $blog = Blog::findOrFail($blogId);
        $relatedProducts = $blog->relatedProducts()->with('product')->orderBy('order')->get();
        
        return response()->json([
            'status' => 'success',
            'related_products' => $relatedProducts
        ]);
    }
    
    /**
     * Add multiple products to a blog
     */
    public function addMultipleProducts(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:product_services,id',
        ]);
        
        $blogId = $request->blog_id;
        $productIds = $request->product_ids;
        $addedCount = 0;
        
        // Get current max order
        $maxOrder = BlogRelatedProduct::where('blog_id', $blogId)->max('order') ?? 0;
        
        foreach ($productIds as $productId) {
            // Check if relation already exists
            $exists = BlogRelatedProduct::where('blog_id', $blogId)
                ->where('product_id', $productId)
                ->exists();
                
            if (!$exists) {
                // Create new relation
                BlogRelatedProduct::create([
                    'blog_id' => $blogId,
                    'product_id' => $productId,
                    'order' => ++$maxOrder
                ]);
                
                $addedCount++;
            }
        }
        
        return redirect()->back()->with('success', $addedCount . ' products added to blog');
    }
}
