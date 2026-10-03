<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
     DB::statement("ALTER TABLE users MODIFY role ENUM('admin','customer','organizer') NOT NULL DEFAULT 'customer'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','customer') NOT NULL DEFAULT 'customer'");
    }
};
