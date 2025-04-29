<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignToPos extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_id',
        'employee_id',
        'phone_number'
    ];
}
