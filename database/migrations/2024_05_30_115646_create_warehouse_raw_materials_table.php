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
        Schema::create('warehouse_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_id')->default(0);
            $table->integer('raw_material_id')->default(0);
            $table->float('quantity', 25, 2)->default(0.00);
            $table->integer('created_by')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_raw_materials');
    }
};
