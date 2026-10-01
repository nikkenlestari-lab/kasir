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
    Schema::create('held_transactions', function (Blueprint $table) {
        $table->id();
        $table->string('transaction_number')->unique();
        $table->json('cart');
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('tax', 12, 2)->default(0);
        $table->decimal('fee', 12, 2)->default(0);
        $table->decimal('grand_total', 12, 2)->default(0);
        $table->string('cashier')->default('Admin');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('held_transactions');
}
};
