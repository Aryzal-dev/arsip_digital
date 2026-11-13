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
        Schema::table('klasifikasi_arsip', function (Blueprint $table) {
            // Hapus foreign key parent_id terlebih dahulu
            $table->dropForeign(['parent_id']);

            // Baru hapus kolom-kolom lama
            $table->dropColumn([
                'parent_id',
                'nomor',
                'klasifikasi_keamanan',
                'hak_akses',
                'dasar_pertimbangan',
                'unit_pengolah',
                'is_active',
            ]);

            // Tambahkan kolom keterangan
            $table->text('keterangan')->nullable()->after('jenis_arsip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('klasifikasi_arsip', function (Blueprint $table) {
            $table->dropColumn('keterangan');

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('nomor', 50)->nullable();
            $table->string('klasifikasi_keamanan')->nullable();
            $table->string('hak_akses')->nullable();
            $table->text('dasar_pertimbangan')->nullable();
            $table->string('unit_pengolah')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }
};