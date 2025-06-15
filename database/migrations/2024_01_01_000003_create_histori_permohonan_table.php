<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_permohonan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained('permohonan_pkl')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user yang melakukan aksi
            $table->string('status_dari');
            $table->string('status_ke');
            $table->string('role_processor'); // role yang memproses
            $table->text('catatan')->nullable();
            $table->enum('aksi', ['disetujui', 'ditolak', 'dikembalikan', 'diteruskan']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_permohonan');
    }
};