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
        Schema::create('provident_funds', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('contribution_rate')->nullable();
            $table->float('total_amount', 25, 2)->default(0.00);
            $table->float('withdrawn_amount', 25, 2)->default(0.00);
            $table->integer('status')->default(1);
            $table->text('note')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provident_funds');
    }
};
