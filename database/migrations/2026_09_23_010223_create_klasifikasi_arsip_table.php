<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_arsip', function (Blueprint $table) {
            $table->id();

            // Untuk struktur klasifikasi bertingkat
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('klasifikasi_arsip')
                ->nullOnDelete();

            // Nomor pada tabel sumber
            $table->string('nomor', 50)->nullable();

            // Contoh: HK, HK01, HK0101
            $table->string('kode_klasifikasi', 50)->nullable()->index();

            // Nama / uraian jenis arsip
            $table->text('jenis_arsip');

            // Contoh: Terbatas, Rahasia, Biasa/Umum/Terbuka
            $table->string('klasifikasi_keamanan', 100)->nullable();

            // Contoh: Pimpinan Tinggi Pratama
            $table->string('hak_akses', 255)->nullable();

            // Alasan penentuan keamanan
            $table->text('dasar_pertimbangan')->nullable();

            // Contoh: Biro Hukum
            $table->string('unit_pengolah', 255)->nullable();

            // Status data
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Mempercepat pencarian berdasarkan parent
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_arsip');
    }
};