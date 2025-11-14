<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jika kamu yakin pakai enum, gunakan ini (ganti daftarnya):
            $table->enum('status', [
                'pending', 'waiting_confirmation', 'paid', 'packaging', 
                'shipping', 'completed', 'cancelled'
            ])->default('pending')->change();
        });
    }
};