<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function providentFund()
    {
        return $this->belongsTo(ProvidentFund::class, 'provident_fund_id');
    }
}
