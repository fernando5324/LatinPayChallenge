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
        Schema::create('external_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->unique();
            $table->string('status', 20);
            $table->integer('attempts')->default(0);
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_external_notifications');
    }
};
