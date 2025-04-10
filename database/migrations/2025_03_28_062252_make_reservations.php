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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string("customer_name");
            $table->string("customer_phone");
            $table->unsignedBigInteger('table_id')->nullable(false);
            $table->unsignedBigInteger('number_of_people')->default(1);
            $table->dateTime('reservation_time');
            $table->unsignedBigInteger('reservation_status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
