<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_audits', function (Blueprint $table) {
            $table->id();

            $table->string('payment_code', 30)->nullable();

            $table->string('action', 50);
            $table->string('status', 20);
            $table->text('description');
            $table->timestamps();
            $table->index('payment_code');
            $table->index('action');
            $table->index(['payment_code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_audits');
    }
};