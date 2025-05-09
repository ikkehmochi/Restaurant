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
        //
        Schema::create('ingredient_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained(table: 'menus', column: 'id')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained(table: 'ingredients', column: 'id')->onDelete('cascade');
            $table->integer('quantity')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_menu');
    }
};
