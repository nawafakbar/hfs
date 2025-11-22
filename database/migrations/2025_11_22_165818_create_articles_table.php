<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL cantik (seo-friendly)
            $table->text('excerpt'); // Ringkasan pendek untuk di beranda
            $table->longText('content'); // Isi lengkap artikel
            $table->string('image')->nullable(); // Foto kegiatan
            $table->string('author')->default('Admin BGD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
