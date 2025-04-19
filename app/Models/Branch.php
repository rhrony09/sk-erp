<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name','created_by'
    ];

    public function employees()
    {
        return $this->hasMany('App\Models\Employee', 'branch_id');
    }

    public function warehouses()
    {
        return $this->hasMany('App\Models\Warehouse', 'branch_id');
    }
}
