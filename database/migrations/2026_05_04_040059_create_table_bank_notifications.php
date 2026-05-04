<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 30)->unique();
            $table->string('bank_transaction_id', 50)->unique();
            $table->string('payment_code', 30);
            $table->json('payload')->nullable();
            $table->decimal('amount', 8, 2);
            $table->boolean('is_deleted')->default(0);
            $table->char('currency', 5);
            $table->string('status', 20);
            $table->timestamp('paid_at')->useCurrent();
            $table->timestamps();

            $table->index('payment_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_bank_notifications');
    }
};
