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
        Schema::create('blog_related_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->unsignedBigInteger('product_id'); // Links to productservice table
            $table->integer('order')->default(0); // For sorting related products
            
            $table->foreign('blog_id')
                  ->references('id')
                  ->on('blogs')
                  ->onDelete('cascade');
                  
            $table->foreign('product_id')
                  ->references('id')
                  ->on('productservices')
                  ->onDelete('cascade');
                  
            // Prevent duplicate relations
            $table->unique(['blog_id', 'product_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_related_products');
    }
};
