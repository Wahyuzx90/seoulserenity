<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Cek apakah kolom image sudah ada
            if (!Schema::hasColumn('menus', 'image')) {
                $table->string('image')->nullable()->after('emoji');
            }
            
            // Cek apakah kolom stock sudah ada
            if (!Schema::hasColumn('menus', 'stock')) {
                $table->integer('stock')->default(99)->after('price');
            }
            
            // Cek apakah kolom discount sudah ada
            if (!Schema::hasColumn('menus', 'discount')) {
                $table->integer('discount')->default(0)->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['image', 'stock', 'discount']);
        });
    }
};