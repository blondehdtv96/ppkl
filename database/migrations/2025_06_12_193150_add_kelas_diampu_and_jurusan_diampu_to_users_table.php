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
        Schema::table('users', function (Blueprint $table) {
            $table->json('kelas_diampu')->nullable()->after('jurusan')->comment('Kelas yang diampu oleh wali kelas');
            $table->json('jurusan_diampu')->nullable()->after('kelas_diampu')->comment('Jurusan yang diampu oleh kaprog');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kelas_diampu', 'jurusan_diampu']);
        });
    }
};
