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
        Schema::create('product_service_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_service_order_id')->constrained('product_service_orders')->onDelete('cascade');
            $table->foreignId('product_service_id')->constrained('product_services')->onDelete('cascade');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_service_order_items');
    }
};
