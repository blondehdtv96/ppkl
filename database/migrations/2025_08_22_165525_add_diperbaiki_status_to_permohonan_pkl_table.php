<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'diperbaiki' status
        DB::statement("ALTER TABLE permohonan_pkl MODIFY COLUMN status ENUM(
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
            'dicetak_hubin',
            'diperbaiki'
        ) NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum back to original values
        DB::statement("ALTER TABLE permohonan_pkl MODIFY COLUMN status ENUM(
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
        ) NOT NULL DEFAULT 'draft'");
    }
};
