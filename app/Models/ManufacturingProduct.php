<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingProduct extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function raw_material() {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    public function product() {
        return $this->belongsTo(ProductService::class, 'product_id');
    }
}
