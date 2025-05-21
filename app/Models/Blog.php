<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'thumb',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'published_at',
        'created_by'
    ];
    
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];
    
    /**
     * Get the user who created the blog post
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the related products for the blog post
     */
    public function relatedProducts()
    {
        return $this->belongsToMany(ProductService::class, 'blog_related_products', 'blog_id', 'product_id')
            ->withPivot('order')
            ->orderBy('order');
    }
    
    /**
     * Generate a slug from the title
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    
    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
        return $this;
    }
    
    /**
     * Scope a query to only include published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }
}
