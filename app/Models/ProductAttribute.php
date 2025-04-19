<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    public function attribute()
    {
        return $this->hasOneThrough(
            Attribute::class, 
            AttributeValue::class,
            'id', // Foreign key on AttributeValue
            'id', // Foreign key on Attribute
            'attribute_value_id', // Local key on ProductAttribute
            'attribute_id' // Local key on AttributeValue
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
