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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percentage']); // fixed amount or percentage discount
            $table->decimal('value', 8, 2); // The discount value
            $table->decimal('min_order_amount', 8, 2)->nullable(); // Minimum order amount to apply promo code
            $table->timestamp('expires_at')->nullable();
            $table->integer('usage_limit')->nullable(); // How many times this code can be used in total
            $table->integer('used_count')->default(0); // How many times this code has been used
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
