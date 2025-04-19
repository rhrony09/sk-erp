<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\ProductService;
use App\Models\ProductServiceCategory;
use App\Models\Review;
use Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function categoryProducts($slug)
    {
        $category = ProductServiceCategory::where('slug', $slug)->select('id','name','slug')->first();
        
        return view('ecommerce.categoryProduct.index',compact('category'));
    }
    
    public function getCategoryProducts(Request $request, $id)
    {
        $perPage = $request->get('perPage', 10);
        $sortby = $request->get('sortby', 'trending');
        
        $query = ProductService::where('category_id', $id)
            ->select('id', 'category_id', 'description', 'name', 'slug', 'pro_image', 'sale_price', 'discount_price', 'created_at');
        
        // Apply sorting based on sortby parameter
        switch ($sortby) {
            case 'aToZ':
                $query->orderBy('name', 'asc');
                break;
            case 'zToA':
                $query->orderBy('name', 'desc');
                break;
            case 'lowToHigh':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'highToLow':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'trending':
            default:
                // For trending, you might want to use a relevance score, popularity field, or just default order
                $query->orderBy('created_at', 'desc'); // Default to newest first for trending
                break;
        }
        
        $products = $query->paginate($perPage);
        return response()->json($products);
    }

    public function singleProduct($slug)
    {
        $product = ProductService::where('slug', $slug)->select('id','category_id','description','name','pro_image','sale_price','discount_price','created_at','sku','quantity')->with('category:id,name,slug','attributes.attribute','reviews.user')->first();

        $relatedProducts = ProductService::where('category_id', $product
            ->category_id)
            ->where('id', '!=', $product->id)
            ->select('id', 'category_id', 'description', 'name', 'slug', 'pro_image', 'sale_price', 'discount_price', 'created_at')
            ->limit(10)
            ->get();
            
        return view('ecommerce.productDetails.index', compact('product','relatedProducts'));
    }

    public function generateProductSlugs()
    {
        // Get total count of products without slugs
        $totalProducts = \DB::table('product_services')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->count();
        
        $batchSize = 100;
        $totalBatches = ceil($totalProducts / $batchSize);
        $processedCount = 0;
        
        echo "Found {$totalProducts} products without slugs. Processing in {$totalBatches} batches.\n";
        
        // Process products in batches
        for ($batch = 0; $batch < $totalBatches; $batch++) {
            // Get batch of products without slugs
            $products = \DB::table('product_services')
                ->select('id', 'name')
                ->whereNull('slug')
                ->orWhere('slug', '')
                ->limit($batchSize)
                ->get();
            
            if ($products->isEmpty()) {
                echo "No more products without slugs found.\n";
                break;
            }
            
            // Process each product in the batch
            foreach ($products as $product) {
                // Generate initial slug from name
                $baseSlug = \Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;
                
                // Check if slug already exists and make it unique if needed
                while (\DB::table('product_services')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                // Update product with new slug
                \DB::table('product_services')
                    ->where('id', $product->id)
                    ->update(['slug' => $slug]);
                
                $processedCount++;
            }
            
            echo "Processed batch " . ($batch + 1) . " of {$totalBatches}. Progress: {$processedCount}/{$totalProducts}.\n";
        }
        
        echo "Completed generating slugs for {$processedCount} products.\n";
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category');
        
        $productsQuery = ProductService::where('type', 'product')->with('category');
        
        // Only apply search if query is provided
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'like', "%{$query}%");
                  });
            });
        }
        
        // Filter by category if specified and not "All Categories"
        if ($categoryId && $categoryId != 'all') {
            $productsQuery->where('category_id', $categoryId);
        }


        
        $products = $productsQuery->with('category:id,name')
            ->select('id', 'category_id', 'name', 'slug', 'pro_image', 'sale_price', 'discount_price', 'created_at', 'description')
            ->paginate(16); // Using pagination is better for large result sets
        
        return view('ecommerce.searchResult.index', compact('products', 'query', 'categoryId'));
    }

    public function makeReview(Request $request, $productId)
    {
        $review = new Review();

        $review->user_id = Auth::user()->id;
        $review->product_id = $productId;
        $review->rating = $request->rating;
        $review->review_text = $request->review_text;

        $review->save();

        return redirect()->back();
    }
}