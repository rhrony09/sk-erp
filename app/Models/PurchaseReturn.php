<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'reason',
        'return_date',
    ];

    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\ProductService', 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\warehouse', 'warehouse_id');
    }
}
