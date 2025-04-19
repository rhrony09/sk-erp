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
        Schema::create('provident_fund_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('provident_fund_id');
            $table->integer('transaction_type')->default(1);
            $table->float('amount', 25, 2)->default(0.00);
            $table->integer('status')->default(0);
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
