<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'pos_id',          // ✅ Add this line
        'quantity',
        'reason',
        'product_condition',
    ];

    public function pos()
    {
        return $this->belongsTo('App\Models\Pos', 'pos_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\ProductService', 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'product_id');
    }
    
}
