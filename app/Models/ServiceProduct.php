<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProduct extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(ProductService::class, 'product_id');
    }
}
