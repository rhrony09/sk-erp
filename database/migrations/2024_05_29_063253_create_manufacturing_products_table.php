<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manufacturing_products', function (Blueprint $table) {
            $table->id();
            $table->integer('raw_material_id');
            $table->integer('product_id');
            $table->float('quantity', 25, 2)->default(0.00);
            $table->decimal('price', 15, 2)->default('0.00');
            $table->text('description')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_products');
    }
};
