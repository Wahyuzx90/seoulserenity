<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->enum('payment_method', ['qris', 'transfer_bca', 'transfer_mandiri', 'cod']);
            $table->text('notes')->nullable();
            $table->integer('subtotal');
            $table->integer('tax');
            $table->integer('delivery_fee')->default(10000);
            $table->integer('total');
            $table->enum('status', ['pending', 'process', 'ready', 'done', 'cancelled'])->default('pending');
            $table->enum('delivery_type', ['delivery', 'pickup'])->default('delivery');
            $table->string('proof_image')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};