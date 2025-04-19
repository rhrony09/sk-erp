<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseRawMaterial extends Model
{
    use HasFactory;

    public function product() {
        return $this->hasOne('App\Models\RawMaterial', 'id', 'raw_material_id')->first();
    }
    public function warehouse() {
        return $this->hasOne('App\Models\warehouse', 'id', 'warehouse_id');
    }
}
