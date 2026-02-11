<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->unsignedInteger('price_standard')->default(0);
            $table->unsignedInteger('price_vip')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('halls', function (Blueprint $table) {
            $table->dropColumn(['price_standard', 'price_vip']);
        });
    }
};
