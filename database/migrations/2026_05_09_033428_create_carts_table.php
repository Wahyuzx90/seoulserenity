<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            // Unique agar tidak duplikat menu di keranjang
            $table->unique(['user_id', 'menu_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};