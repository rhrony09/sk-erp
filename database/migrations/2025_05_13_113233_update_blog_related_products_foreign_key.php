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
        Schema::table('blog_related_products', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['product_id']);
            
            // Add the corrected foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('product_services') // Correct table name with underscore
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_related_products', function (Blueprint $table) {
            // Drop the corrected foreign key
            $table->dropForeign(['product_id']);
            
            // Restore the original foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('productservices')
                  ->onDelete('cascade');
        });
    }
};
