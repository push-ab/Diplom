<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('showings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->timestamps();

            $table->index(['hall_id', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showings');
    }
};
