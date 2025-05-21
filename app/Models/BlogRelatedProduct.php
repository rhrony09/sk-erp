<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogRelatedProduct extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'blog_id',
        'product_id',
        'order'
    ];
    
    /**
     * Get the blog that owns the related product
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
    
    /**
     * Get the product that is related to the blog
     */
    public function product()
    {
        return $this->belongsTo(ProductService::class, 'product_id');
    }
}
