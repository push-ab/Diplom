<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('showing_id')->constrained()->cascadeOnDelete();

            $table->string('code', 64)->unique();
            $table->integer('total');
            $table->enum('status', ['booked', 'paid', 'canceled'])->default('booked');

            $table->string('customer_email', 180)->nullable();
            $table->string('customer_phone', 40)->nullable();

            $table->timestamps();

            $table->unique(['showing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
