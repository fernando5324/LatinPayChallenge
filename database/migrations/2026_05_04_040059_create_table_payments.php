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
    $table->string('payment_code', 30)->unique();
    $table->integer('merchant_id')->nullable();
    $table->string('customer_document', 20)->nullable();
    $table->decimal('amount', 8, 2);
    $table->char('currency', 5);
    $table->boolean('is_deleted')->default(0);
    $table->string('status', 20);
    $table->string('description')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps(); // created_at + updated_at
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_payments');
    }
};
