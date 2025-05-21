<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_zip_code',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_phone',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
