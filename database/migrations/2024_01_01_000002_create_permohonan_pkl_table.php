<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->text('alamat_perusahaan');
            $table->string('telepon_perusahaan');
            $table->string('email_perusahaan')->nullable();
            $table->string('bidang_usaha');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan_pkl');
            $table->string('dokumen_pendukung')->nullable(); // path file upload
            $table->enum('status', [
                'draft',
                'diajukan',
                'ditolak_wali',
                'disetujui_wali',
                'ditolak_bp',
                'disetujui_bp',
                'ditolak_kaprog',
                'disetujui_kaprog',
                'ditolak_tu',
                'disetujui_tu',
                'dicetak_hubin'
            ])->default('draft');
            $table->text('catatan_penolakan')->nullable();
            $table->string('current_role')->nullable(); // role yang sedang memproses
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_pkl');
    }
};