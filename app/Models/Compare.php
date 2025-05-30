<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo('App\Models\ProductService', 'product_id');
    }
}
