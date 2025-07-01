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
            // Remove the legacy kelas_diampu column as we now use custom_kelas_diampu exclusively
            if (Schema::hasColumn('users', 'kelas_diampu')) {
                $table->dropColumn('kelas_diampu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add kelas_diampu as JSON column if needed for rollback
            $table->json('kelas_diampu')->nullable()->after('jurusan_diampu')->comment('Legacy: Kelas yang diampu oleh wali kelas (replaced by custom_kelas_diampu)');
        });
    }
};