<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class warehouse extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public static function warehouse_id($warehouse_name)
    {
        $warehouse = DB::table('warehouses')
        ->where('id', $warehouse_name)
        ->where('created_by', Auth::user()->creatorId())
        ->select('id')
        ->first();

        return ($warehouse != null) ? $warehouse->id : 0;
    }

    public function warehouseProducts()
    {
        return $this->hasMany('App\Models\WarehouseProduct', 'warehouse_id');
    }

    public function poses()
    {
        return $this->hasMany('App\Models\Pos', 'warehouse_id');
    }
}
