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
        // Perintah untuk MENGUBAH (Schema::table) tabel 'users'
        Schema::table('users', function (Blueprint $table) {
            
            // ===== TAMBAHAN OPSI 1 (Alamat di profil) =====
            // Kita tambahkan kolom-kolom baru
            // after('password') agar posisinya rapi di database
            
            $table->string('telepon')->nullable()->after('password');
            $table->text('alamat_lengkap')->nullable()->after('telepon');
            $table->string('provinsi')->default('Sumatera Barat')->nullable()->after('alamat_lengkap');
            $table->string('kota')->default('Padang')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kota'); // <-- KUNCI ONGKIR
            $table->string('kode_pos')->nullable()->after('kecamatan');

            // PENTING:
            // Kita pakai ->nullable() agar data user lama yang
            // belum punya data ini tidak error. Ini kuncinya!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fungsi 'down' adalah kebalikannya (jika kita perlu rollback)
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telepon',
                'alamat_lengkap',
                'provinsi',
                'kota',
                'kecamatan',
                'kode_pos'
            ]);
        });
    }
};