<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_audits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('payment_id');

            $table->string('action', 50);
            $table->string('status', 20);
            $table->text('description');
            $table->timestamps();
            $table->index('payment_id');
            $table->index('action');
            $table->index(['payment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_audits');
    }
};