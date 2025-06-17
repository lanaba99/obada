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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->nullable()->unique(); // From payment gateway
            $table->decimal('amount', 8, 2);
            $table->string('currency')->default('EUR'); // Or your default currency
            $table->string('method')->nullable(); // e.g., 'credit_card', 'paypal', 'bank_transfer'
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('details')->nullable(); // Any additional payment gateway details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
