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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->default("Guest");
            $table->unsignedBigInteger('table_id')->nullable();
            $table->unsignedInteger('status')->comment('1=serving, 2=completed, 3=cancelled')->default(0);
            $table->double('total_price')->default(0);
            $table->double('paid')->default(0);
            $table->string('notes')->nullable(true);
            $table->string('payment_method')->nullable(false)->default('cash');
            $table->string('payment_status')->nullable(false)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
