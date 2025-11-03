<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // --- Kolom Tambahan Kita ---
            $table->enum('role', ['admin', 'customer'])->default('customer');
            $table->string('profile_photo_path')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('google_id')->nullable();
            // --- Akhir Kolom Tambahan ---
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};