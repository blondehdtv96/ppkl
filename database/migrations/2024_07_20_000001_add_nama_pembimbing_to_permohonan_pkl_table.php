<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->string('nama_pembimbing')->nullable()->after('kontak_perusahaan');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_pkl', function (Blueprint $table) {
            $table->dropColumn('nama_pembimbing');
        });
    }
};