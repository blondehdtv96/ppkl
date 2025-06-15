<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk telepon_perusahaan ke kontak_perusahaan
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->string('kontak_perusahaan')->after('alamat_perusahaan')->nullable();
        });
        
        // Salin data dari kolom lama ke kolom baru
        DB::statement('UPDATE permohonan_pkl SET kontak_perusahaan = telepon_perusahaan');
        
        // Hapus kolom lama
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->dropColumn('telepon_perusahaan');
        });
        
        // Untuk alasan_pkl ke alasan
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->text('alasan')->after('tanggal_selesai')->nullable();
        });
        
        // Salin data dari kolom lama ke kolom baru
        DB::statement('UPDATE permohonan_pkl SET alasan = alasan_pkl');
        
        // Hapus kolom lama
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->dropColumn('alasan_pkl');
        });
    }

    public function down(): void
    {
        // Untuk kontak_perusahaan ke telepon_perusahaan
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->string('telepon_perusahaan')->after('alamat_perusahaan')->nullable();
        });
        
        // Salin data dari kolom baru ke kolom lama
        DB::statement('UPDATE permohonan_pkl SET telepon_perusahaan = kontak_perusahaan');
        
        // Hapus kolom baru
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->dropColumn('kontak_perusahaan');
        });
        
        // Untuk alasan ke alasan_pkl
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->text('alasan_pkl')->after('tanggal_selesai')->nullable();
        });
        
        // Salin data dari kolom baru ke kolom lama
        DB::statement('UPDATE permohonan_pkl SET alasan_pkl = alasan');
        
        // Hapus kolom baru
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->dropColumn('alasan');
        });
    }
};