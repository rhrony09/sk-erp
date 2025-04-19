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
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->integer('type')->nullable();
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('status')->default(0)->comment('0 = pending, 1 = received, 2 = in progress, 3 = on hold, 4 = completed, 5 = cancelled');
            $table->integer('is_paid')->default(0);
            $table->float('product_price', 25, 2)->default(0.00);
            $table->float('service_charge', 25, 2)->nullable();
            $table->string('employee_location')->nullable();
            $table->integer('created_by')->default('1');
            $table->integer('vendor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
