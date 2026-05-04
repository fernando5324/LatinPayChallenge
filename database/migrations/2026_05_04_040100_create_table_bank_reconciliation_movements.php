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
        Schema::create('bank_reconciliation_movements', function (Blueprint $table) {
            $table->id();
            $table->string('bank', 60);
            $table->string('bank_transaction_id', 50);
            $table->string('bank_movement_id', 30);
            $table->date('process_date');
            $table->string('status', 20);
            $table->string('payment_code', 30);
            $table->decimal('amount', 8, 2);
            $table->char('currency', 5);
            $table->timestamps();

            $table->unique(['bank', 'bank_movement_id']); // 🔥 importante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_bank_reconciliation_movements');
    }
};
