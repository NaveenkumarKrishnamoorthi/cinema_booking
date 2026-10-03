<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->integer('total_seats')->default(100)->after('price');
            $table->integer('available_seats')->default(100)->after('total_seats');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('available_seats');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['total_seats', 'available_seats', 'status']);
        });
    }
};