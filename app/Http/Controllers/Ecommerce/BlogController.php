<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('author')->orderBy('created_at', 'desc')->paginate(10);
        return view('ecommerce.blog.index', compact('blogs'));
    }

    /**
     * Display blog listing for frontend users
     */
    public function blogList()
    {
        $blogs = Blog::with('author')->orderBy('created_at', 'desc')->paginate(9);
        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Blog store method called');
        \Log::info($request->all());
        
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'content' => 'required|string',
                'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
                'is_published' => 'nullable|boolean',
                'published_at' => 'nullable|date',
            ]);
            
            \Log::info('Validation passed');

            $blog = new Blog();
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
            $blog->description = $request->description;
            $blog->content = $request->content;
            $blog->meta_title = $request->meta_title;
            $blog->meta_description = $request->meta_description;
            $blog->meta_keywords = $request->meta_keywords;
            $blog->is_published = (int)$request->is_published;
            $blog->published_at = $request->published_at ?? now();
            $blog->created_by = Auth::id();
            
            \Log::info('Blog object created with data:', ['blog' => $blog->toArray()]);

            // Handle thumbnail upload
            if ($request->hasFile('thumb')) {
                $thumbFile = $request->file('thumb');
                $thumbName = time() . '_' . Str::slug($request->title) . '_thumb.' . $thumbFile->getClientOriginalExtension();
                $thumbPath = $thumbFile->storeAs('uploads/blogs/thumbs', $thumbName, 'public');
                $blog->thumb = $thumbPath;
                \Log::info('Thumbnail uploaded to: ' . $thumbPath);
            }

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $featureFile = $request->file('featured_image');
                $featureName = time() . '_' . Str::slug($request->title) . '_featured.' . $featureFile->getClientOriginalExtension();
                $featurePath = $featureFile->storeAs('uploads/blogs/featured', $featureName, 'public');
                $blog->featured_image = $featurePath;
                \Log::info('Featured image uploaded to: ' . $featurePath);
            }

            try {
                \Log::info('Attempting to save blog to database');
                $result = $blog->save();
                \Log::info('Blog save result: ' . ($result ? 'true' : 'false'));
                
                if ($result) {
                    \Log::info('Blog saved successfully with ID: ' . $blog->id);
                    return redirect()->route('blogs.index')
                        ->with('success', 'Blog created successfully.');
                } else {
                    \Log::error('Blog save returned false but no exception was thrown');
                    return redirect()->back()
                        ->with('error', 'Failed to create blog post: Save operation returned false')
                        ->withInput();
                }
            } catch (\Exception $e) {
                \Log::error('Error saving blog: ' . $e->getMessage());
                \Log::error('Exception trace: ' . $e->getTraceAsString());
                return redirect()->back()
                    ->with('error', 'Failed to create blog post: ' . $e->getMessage())
                    ->withInput();
            }
        } catch (\Exception $e) {
            \Log::error('Error in blog creation process: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Failed to create blog post: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $blog->incrementViewCount();
        
        $relatedProducts = $blog->relatedProducts;
        
        // Get recent posts for sidebar
        $recentPosts = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('ecommerce.blog.show', compact('blog', 'relatedProducts', 'recentPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        $productServices = ProductService::all();
        $relatedProducts = $blog->relatedProducts;
        
        return view('blogs.edit', compact('blog', 'productServices', 'relatedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        $blog = Blog::findOrFail($id);
        
        // Handle scenario where title might change and thus slug would need updating
        if ($blog->title != $request->title) {
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
        }
        
        $blog->description = $request->description;
        $blog->content = $request->content;
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->is_published = (int)$request->is_published;
        $blog->published_at = $request->published_at ?? $blog->published_at;

        // Handle thumbnail upload
        if ($request->hasFile('thumb')) {
            // Delete old thumbnail if exists
            if ($blog->thumb && Storage::disk('public')->exists($blog->thumb)) {
                Storage::disk('public')->delete($blog->thumb);
            }
            
            $thumbFile = $request->file('thumb');
            $thumbName = time() . '_' . Str::slug($request->title) . '_thumb.' . $thumbFile->getClientOriginalExtension();
            $thumbPath = $thumbFile->storeAs('uploads/blogs/thumbs', $thumbName, 'public');
            $blog->thumb = $thumbPath;
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image if exists
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            
            $featureFile = $request->file('featured_image');
            $featureName = time() . '_' . Str::slug($request->title) . '_featured.' . $featureFile->getClientOriginalExtension();
            $featurePath = $featureFile->storeAs('uploads/blogs/featured', $featureName, 'public');
            $blog->featured_image = $featurePath;
        }

        try {
            $blog->save();
            return redirect()->route('blogs.edit', $blog->id)
                ->with('success', 'Blog updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating blog: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update blog post: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        
        // Delete associated images
        if ($blog->thumb && Storage::disk('public')->exists($blog->thumb)) {
            Storage::disk('public')->delete($blog->thumb);
        }
        
        if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        
        // Related products will be deleted automatically due to cascade delete
        
        $blog->delete();
        
        return redirect()->route('blogs.index')
            ->with('success', 'Blog deleted successfully.');
    }
}
