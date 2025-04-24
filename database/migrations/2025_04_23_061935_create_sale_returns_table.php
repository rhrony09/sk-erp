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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id')->constrained('pos')->onDelete('cascade');
            $table->bigInteger('customer_id')->nullable();
            $table->foreignId('product_id')->constrained('product_services')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('reason')->nullable(); 
            $table->enum('product_condition',['unopened','unused','used'])->default('unopened');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
