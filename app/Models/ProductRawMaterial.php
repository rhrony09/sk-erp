<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRawMaterial extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id')->first();
    }

    public function rawMaterial()
    {
        return $this->hasOne('App\Models\RawMaterial', 'id', 'raw_material_id')->first();
    }
}
