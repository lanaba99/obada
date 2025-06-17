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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('final_amount', 8, 2); // Amount after discounts
            $table->decimal('discount_amount', 8, 2)->default(0); // If a promo code was applied
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->onDelete('set null'); // Link to promo code
            $table->enum('status', ['pending_payment', 'processing', 'shipped', 'delivered', 'cancelled', 'payment_failed'])->default('pending_payment');
            $table->text('shipping_address')->nullable(); // Store serialized address or link to addresses table
            $table->text('billing_address')->nullable(); // Store serialized address or link to addresses table
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