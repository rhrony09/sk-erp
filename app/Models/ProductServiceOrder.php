<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductServiceOrder extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany('App\Models\ProductServiceOrderItem', 'product_service_order_id');
    }
}
