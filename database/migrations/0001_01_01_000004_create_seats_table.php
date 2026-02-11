<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('row');
            $table->unsignedTinyInteger('col');

            $table->enum('type', ['standard', 'vip'])->default('standard');
            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            $table->unique(['hall_id', 'row', 'col']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
